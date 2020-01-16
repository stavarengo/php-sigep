<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Bootstrap;
use PhpSigep\Model\Dimensao;
use PhpSigep\Model\ServicoAdicional;
use PhpSigep\Services\Exception;
use PhpSigep\Services\Result;

/**
 * @author: Stavarengo
 */
class CalcPrecoPrazo
{
    public static $calcPrecosPrazosServiceUnavailable = false;

    /**
     * @param \PhpSigep\Model\CalcPrecoPrazo $params
     *
     * @throws Exception
     * @throws \Exception
     * @return Result<\PhpSigep\Model\CalcPrecoPrazoRespostaIterator[]>
     */
    public function execute(\PhpSigep\Model\CalcPrecoPrazo $params)
    {
        $larguraMinima     = 0;
        $alturaMinima      = 0;
        $comprimentoMinimo = 16;
        $diametroMinimo    = 0;

        $tipoEmbalagem = $params->getDimensao()->getTipo();
        $ehEnvelope    = false;
        if ($tipoEmbalagem == Dimensao::TIPO_ENVELOPE) {
            $ehEnvelope       = true;
            $formatoEncomenda = 3;
            $larguraMinima    = 11;
        } else if ($tipoEmbalagem == Dimensao::TIPO_PACOTE_CAIXA) {
            $formatoEncomenda = 1;
            $alturaMinima     = 2;
            $larguraMinima    = 11;
        } else {
            if ($tipoEmbalagem == Dimensao::TIPO_ROLO_CILINDRO) {
                $formatoEncomenda  = 2;
                $comprimentoMinimo = 18;
                $diametroMinimo    = 5;
            } else {
                throw new Exception('Tipo de embalagem "' . $tipoEmbalagem . '" não reconhecido.');
            }
        }

        $maoPropria         = false;
        $valorDeclarado     = 0;
        $avisoRecebimento   = false;
        $servicosAdicionais = $params->getServicosAdicionais();
        $servicosAdicionais = ($servicosAdicionais ? $servicosAdicionais : array());
        foreach ($servicosAdicionais as $servicoAdicional) {
            if ($servicoAdicional->is(ServicoAdicional::SERVICE_MAO_PROPRIA)) {
                $maoPropria = true;
            } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_VALOR_DECLARADO_SEDEX) || $servicoAdicional->is(ServicoAdicional::SERVICE_VALOR_DECLARADO_PAC)) {
                if (!$servicoAdicional->getValorDeclarado()) {
                    throw new Exception('Para usar o serviço "valor declarado" é necessário declarar o valor da mercadoria.');
                }
                $valorDeclarado = $servicoAdicional->getValorDeclarado();
            } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_AVISO_DE_RECEBIMENTO)) {
                $avisoRecebimento = true;
            }
        }

        $servivosDePostagem = array();
        /** @var $servivoDePostagem ServicoDePostagem */
        foreach ($params->getServicosPostagem() as $servivoDePostagem) {
            $servivosDePostagem[] = $servivoDePostagem->getCodigo();
        }

        $ajustarDimensaoMinima = $params->getAjustarDimensaoMinima();

        $comprimento = (int)$params->getDimensao()->getComprimento();
        $comprimento = $comprimento < $comprimentoMinimo && $ajustarDimensaoMinima ? $comprimentoMinimo : $comprimento;
        $altura      = $ehEnvelope ? 0 : ((int)$params->getDimensao()->getAltura());
        $altura      = $altura < $alturaMinima && $ajustarDimensaoMinima ? $alturaMinima : $altura;
        $largura     = (int)$params->getDimensao()->getLargura();
        $largura     = $largura < $larguraMinima && $ajustarDimensaoMinima ? $larguraMinima : $largura;
        $diametro    = (int)$params->getDimensao()->getDiametro();
        $diametro    = $diametro < $diametroMinimo && $ajustarDimensaoMinima ? $diametroMinimo : $largura;

        $soapArgs = array(
            'nCdEmpresa'          => $params->getAccessData()->getCodAdministrativo(),
            'sDsSenha'            => $params->getAccessData()->getSenha(),
            'nCdServico'          => implode(',', $servivosDePostagem),
            'sCepOrigem'          => str_replace('-', '', $params->getCepOrigem()),
            'sCepDestino'         => str_replace('-', '', $params->getCepDestino()),
            'nVlPeso'             => $params->getPeso(),
            'nCdFormato'          => $formatoEncomenda,
            'nVlComprimento'      => $comprimento,
            'nVlAltura'           => $altura,
            'nVlLargura'          => $largura,
            'nVlDiametro'         => $diametro,
            'sCdMaoPropria'       => ($maoPropria ? 'S' : 'N'),
            'nVlValorDeclarado'   => $valorDeclarado,
            'sCdAvisoRecebimento' => ($avisoRecebimento ? 'S' : 'N'),
        );

        $cacheKey = md5(serialize($soapArgs));
        $cache    = Bootstrap::getConfig()->getCacheInstance();
        if ($cachedResult = $cache->getItem($cacheKey)) {
            return unserialize($cachedResult);
        }

        $result = new Result();
        try {
            $r = SoapClientFactory::getSoapCalcPrecoPrazo()->calcPrecoPrazo($soapArgs);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            if ($message == 'Service Unavailable') {
                if (!self::$calcPrecosPrazosServiceUnavailable) {
                    //Tenta mais uma vez
                    self::$calcPrecosPrazosServiceUnavailable = true;
                    sleep(1);

                    return $this->calcPrecoPrazo($params);
                }
            }
            if ($e instanceof \SoapFault) {
                $result->setIsSoapFault(true);
                $result->setErrorCode($e->getCode());
                $result->setErrorMsg(SoapClientFactory::convertEncoding($e->getMessage()));
            } else {
                $result->setErrorCode($e->getCode());
                $result->setErrorMsg($e->getMessage());
            }
        }

        if (class_exists('\StaLib_Logger',false)) {
            \StaLib_Logger::log('Retorno SIGEP: ' . print_r($r, true));
        }

        $retorno = array();
        if (isset($r) && is_object($r) && property_exists($r, 'CalcPrecoPrazoResult') && is_object($r->CalcPrecoPrazoResult)
            && $r->CalcPrecoPrazoResult->Servicos && is_object($r->CalcPrecoPrazoResult->Servicos)
        ) {
            if ($r->CalcPrecoPrazoResult->Servicos->cServico) {
                $servicos = $r->CalcPrecoPrazoResult->Servicos->cServico;
                $servicos = (is_array($servicos) ? $servicos : array($servicos));

                foreach ($servicos as $servico) {
                    $valor                 = (float)str_replace(',', '.', str_replace('.', '', $servico->Valor));
                    $valorMaoPropria       = str_replace('.', '', $servico->ValorMaoPropria);
                    $valorMaoPropria       = (float)str_replace(',', '.', $valorMaoPropria);
                    $valorAvisoRecebimento = str_replace('.', '', $servico->ValorAvisoRecebimento);
                    $valorAvisoRecebimento = (float)str_replace(',', '.', $valorAvisoRecebimento);
                    $valorValorDeclarado   = str_replace('.', '', $servico->ValorValorDeclarado);
                    $valorValorDeclarado   = (float)str_replace(',', '.', $valorValorDeclarado);

                    $item = new \PhpSigep\Model\CalcPrecoPrazoResposta(array(
                        'servico'               => new \PhpSigep\Model\ServicoDePostagem($servico->Codigo),
                        'valor'                 => $valor,
                        'prazoEntrega'          => (int)$servico->PrazoEntrega,
                        'valorMaoPropria'       => $valorMaoPropria,
                        'valorAvisoRecebimento' => $valorAvisoRecebimento,
                        'valorValorDeclarado'   => $valorValorDeclarado,
                        'entregaDomiciliar'     => ($servico->EntregaDomiciliar == 'S'),
                        'entregaSabado'         => ($servico->EntregaSabado == 'S'),
                    ));

                    $item->setErroCodigo($servico->Erro);
                    if ($item->getErroCodigo() && ($item->getErroCodigo() != 10 || !$item->getValor())) {
                        // Se entrar aqui significa que tem Erro e que esse Erro é diferente de 10 ou o Erro é 10 mas não
                        // não foi retornando o Valor.
                        // O erro "10" é mais um aviso do que um erro. Se for erro 10 e tiver valor, não considera um erro.
                        $msgErro = $servico->MsgErro;
                        $msgErro = SoapClientFactory::convertEncoding($msgErro);
                        $item->setErroMsg($msgErro);
                    } else {
                        $item->setErroCodigo(null);
                    }
                    $retorno[] = $item;
                }
            } else {
                $result->setErrorCode(0);
                $result->setErrorMsg('A resposta do Correios não está no formato esperado. Detalhes do problema: "Faltando a entrada \'cServico\'."');
            }
        } else {
            $result->setErrorCode(0);
            if (isset($r)) {
                if (is_object($r)) {
                    $result->setErrorMsg('A resposta do Correios não está no formato esperado. Detalhes do problema: "A resposta recebida é um objeto, mas este objeto não possui todos as entradas necessárias."');
                } else {
                    $result->setErrorMsg('A resposta do Correios não está no formato esperado. Resposta recebida: "' .
                        $r . '"');
                }
            } else {
                $result->setErrorMsg('Nenhuma resposta foi recebida dos correios.');
            }
        }

        $retornoIterator = new \PhpSigep\Model\CalcPrecoPrazoRespostaIterator($retorno);
        $result->setResult($retorno);

        if (!$result->hasError()) {
            if ($retornoIterator->todosTemErro()) {
                $erros = array();
                /** @var $retItem \PhpSigep\Model\CalcPrecoPrazoResposta */
                foreach ($retornoIterator as $retItem) {
                    $erros[$retItem->getErroCodigo()] = $retItem;
                }
                /** @var $exception \PhpSigep\Model\CalcPrecoPrazoResposta */
                $exception         = null;
                $errosPrioritarios = array('999', '-3', '-2', '-4', '-33', '7');
                foreach ($errosPrioritarios as $erroPrioritario) {
                    if (isset($erros[$erroPrioritario])) {
                        $exception = $erros[$erroPrioritario];
                        break;
                    }
                }
                if (!$exception) {
                    $exception = reset($erros);
                }
                $result->setErrorCode($exception->getErroCodigo());
                $result->setErrorMsg($exception->getErroMsg());
            } else {
                $cache->setItem($cacheKey, serialize($result));
            }
        }

        return $result;
    }
}

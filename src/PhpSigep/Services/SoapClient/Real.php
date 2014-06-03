<?php
namespace PhpSigep\Services\SoapClient;

use PhpSigep\Bootstrap;
use PhpSigep\Model\Dimensao;
use PhpSigep\Model\Etiqueta;
use PhpSigep\Model\ServicoAdicional;
use PhpSigep\Model\ServicoDePostagem;

/**
 * @author: Stavarengo
 */
class Real implements SoapClientInterface
{
    private static $calcPrecosPrazosServiceUnavailable = false;

    /**
	 * @var \SoapClient
	 */
	protected $_soapClient;
	/**
	 * @var \SoapClient
	 */
	protected $_soapCalcPrecoPrazo;

	private function _getSoapClient()
	{
		if (!$this->_soapClient) {
			$wsdl              = Bootstrap::getConfig()->getWsdlAtendeCliente();
			$this->_soapClient = new \SoapClient($wsdl, array(
				"trace"      => Bootstrap::getConfig()->isDebug(),
				"exceptions" => Bootstrap::getConfig()->isDebug(),
				'encoding'   => 'ISO-8859-1',
                'connection_timeout' => 60,
			));
		}
		return $this->_soapClient;
	}

	private function _getSoapCalcPrecoPrazo()
	{
		if (!$this->_soapCalcPrecoPrazo) {
			$wsdl                      = Bootstrap::getConfig()->getWsdlCalcPrecoPrazo();
			$this->_soapCalcPrecoPrazo = new \SoapClient($wsdl, array(
				"trace"      => Bootstrap::getConfig()->isDebug(),
				"exceptions" => Bootstrap::getConfig()->isDebug(),
				'encoding'   => 'ISO-8859-1',
                'connection_timeout' => 60,
			));
		}
		return $this->_soapCalcPrecoPrazo;
	}

	/**
	 * @param \PhpSigep\Model\VerificaDisponibilidadeServico $params
	 *
	 * @return bool
	 */
	public function verificaDisponibilidadeServico(\PhpSigep\Model\VerificaDisponibilidadeServico $params)
	{
		$soapArgs = array(
			'codAdministrativo' => $params->getAccessData()->getCodAdministrativo(),
			'numeroServico'     => $params->getServico()->getCodigo(),
			'cepOrigem'         => $params->getCepOrigem(),
			'cepDestino'        => $params->getCepDestino(),
			'usuario'           => $params->getAccessData()->getUsuario(),
			'senha'             => $params->getAccessData()->getSenha(),
		);
		$r        = $this->_getSoapClient()->verificaDisponibilidadeServico($soapArgs);
		return ($r && $r->return);
	}

	/**
	 * @param \PhpSigep\Model\SolicitaEtiquetas $params
	 *
	 * @throws \SoapFault
	 * @throws \Exception
	 * @return Etiqueta[]
	 */
	public function solicitaEtiquetas(\PhpSigep\Model\SolicitaEtiquetas $params)
	{
		$soapArgs = array(
			'tipoDestinatario' => 'C',
			'identificador'    => $params->getAccessData()->getCnpjEmpresa(),
			'idServico'        => $params->getServicoDePostagem()->getIdServico(),
			'qtdEtiquetas'     => 1,
			'usuario'          => $params->getAccessData()->getUsuario(),
			'senha'            => $params->getAccessData()->getSenha(),
		);

		$etiquetasReservadas = array();
		for ($i = 0; $i < $params->getQtdEtiquetas(); $i++) {
			$r = $this->_getSoapClient()->solicitaEtiquetas($soapArgs);
			if ($r && is_object($r) && isset($r->return) && !($r instanceof \SoapFault)) {
				$r = explode(',', $r->return);
//				$etiquetasReservadas[] = str_replace(' ', '', $r[0]);
				$etiqueta = new Etiqueta();
				$etiqueta->setEtiquetaSemDv($r[0]);
				$etiquetasReservadas[] = $etiqueta;
			} else {
				if ($r instanceof \SoapFault) {
					throw $r;
				}
                throw new \Exception('Não foi possível obter as etiquetas solicitadas. Retorno: "' . $r . '"');
			}
		}

		return $etiquetasReservadas;
	}

	/**
	 * @param \PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params
	 *
	 * @throws \SoapFault
	 * @throws \Exception
     * @return Etiqueta[]
	 */
	public function geraDigitoVerificadorEtiquetas(\PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params)
	{
		$soapArgs = array(
			'etiquetas' => array(),
			'usuario'   => $params->getAccessData()->getUsuario(),
			'senha'     => $params->getAccessData()->getSenha(),
		);

		// É necessário garantir que o array estará indexado por order natural começando do zero para setarmos os
		// DV retornados pelo webservice.
		$etiquetas = array_values($params->getEtiquetas());

		/** @var $etiqueta Etiqueta */
		foreach ($etiquetas as $etiqueta) {
			$soapArgs['etiquetas'][] = $etiqueta->getEtiquetaSemDv();
		}

		$soapReturn = $this->_getSoapClient()->geraDigitoVerificadorEtiquetas($soapArgs);
		if ($soapReturn && is_object($soapReturn) && $soapReturn->return) {
			if (!is_array($soapReturn->return)) {
				$soapReturn->return = (array)$soapReturn->return;
			}

			foreach ($soapReturn->return as $k => $dv) {
				$etiquetas[$k]->setDv((int)$dv);
			}
		}


		return $etiquetas;
	}

	public function fechaPlpVariosServicos(\PhpSigep\Model\PreListaDePostagem $params, \XMLWriter $xmlDaPreLista)
	{
//		$idPlpCorreios = time();
//		return ++$idPlpCorreios;

//		ob_clean();
		$listaEtiquetas = array();
		foreach ($params->getEncomendas() as $objetoPostal) {
			$listaEtiquetas[] = $objetoPostal->getEtiqueta()->getEtiquetaSemDv();
		}

		$xml = utf8_encode($xmlDaPreLista->flush());
//		$xml = utf8_encode($xml);
//		$xml = iconv('UTF-8', 'ISO-8859-1', $xml);


//		if (isset($_GET['xml'])) {
//			header("Content-Type:text/xml; charset=ISO-8859-1");
//			echo $xml;
//			exit;
//		}

//		$xml = preg_replace('/\n/', '', $xml);
//		if (isset($_GET['file'])) {
//			file_put_contents('c:/sigep.xml', $xml);
//			echo 'veja aqui c:/sigep.xml';
//			exit;
//		}
//		$domDoc = new \DOMDocument();
//		$domDoc->loadXML($xml);
//		if (!$domDoc->schemaValidate(Bootstrap::getConfig()->getXsdDir() . '/plp_schema.xsd')) {
//			echo 'falhou';
//			exit;
//		}

		$soapArgs = array(
			'xml'            => $xml,
			'idPlpCliente'   => '',
			'cartaoPostagem' => $params->getAccessData()->getCartaoPostagem(),
			'listaEtiquetas' => $listaEtiquetas,
//			'listaEtiquetas' => '{"20046776", "20046877", "20046878"}',
//			'listaEtiquetas' => 'DL20046776BR',
//			'listaEtiquetas' => 'PD389251751BR',
//			'listaEtiquetas' => json_encode($listaEtiquetas),
//			'faixaEtiquetas' => '"DL20046776 BR","DL20046876 BR"',
//			'faixaEtiquetas' => '["DL20046776 BR","DL20046876 BR"]',
//			'faixaEtiquetas' => 'DL20046776BR,DL20046876BR',
//			'faixaEtiquetas' => 'DL20046776,DL20046876',
//			'faixaEtiquetas' => '20046776,20046876"',
			'usuario'        => $params->getAccessData()->getUsuario(),
			'senha'          => $params->getAccessData()->getSenha(),
		);

		try {
//			echo "<pre>";
			$r = $this->_getSoapClient()->fechaPlpVariosServicos($soapArgs);
//			$r = $this->soapClient->fechaPlp($soapArgs);
//			echo "<pre>";
//			print_r($r);
//			exit;
			if ($r && $r->return) {
				return $r->return;
			} else {
                if ($r instanceof \SoapFault) {
                    $r = $r->getMessage();
                } else {
                    $r = $r . '';
                }
				throw new Exception('Resposta recebida do serviço "fechaPlpVariosServicos" está em um formato inválido. Resposta foi: "' . $r . '"');
			}
		} catch (\Exception $e) {
//			echo $e;
//			echo "\n\n\REQUEST:\n" . htmlentities($this->soapClient->__getLastRequest()) . "\n";
//			echo "\n\nREQUEST HEADERS:\n" . htmlentities($this->soapClient->__getLastRequestHeaders()) . "\n";
//			exit;
			throw $e;
		}
	}

    /**
     * @param \PhpSigep\Model\CalcPrecoPrazo $params
     * @return \PhpSigep\Model\CalcPrecoPrazoRespostaIterator
     * @throws Exception
     * @throws \Exception
     */
    public function calcPrecoPrazo(\PhpSigep\Model\CalcPrecoPrazo $params)
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
        } else if ($tipoEmbalagem == Dimensao::TIPO_ROLO_CILINDRO) {
            $formatoEncomenda  = 2;
            $comprimentoMinimo = 18;
            $diametroMinimo    = 5;
        } else {
            throw new Exception('Tipo de embalagem "' . $tipoEmbalagem . '" não reconhecido.');
        }

        $maoPropria         = false;
        $valorDeclarado     = 0;
        $avisoRecebimento   = false;
        $servicosAdicionais = $params->getServicosAdicionais();
        $servicosAdicionais = ($servicosAdicionais ? $servicosAdicionais : array());
        foreach ($servicosAdicionais as $servicoAdicional) {
            if ($servicoAdicional->is(ServicoAdicional::SERVICE_MAO_PROPRIA)) {
                $maoPropria = true;
            } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_VALOR_DECLARADO)) {
                if (!$servicoAdicional->getValorDeclarado()) {
                    throw new Exception('Para usar o serviço "valor declarado" é necessário informar o valor declarado.');
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

        $comprimento = (int)$params->getDimensao()->getComprimento();
        $altura      = $ehEnvelope ? 0 : ((int)$params->getDimensao()->getAltura());
        $largura     = (int)$params->getDimensao()->getLargura();
        $diametro    = (int)$params->getDimensao()->getDiametro();

        $soapArgs = array(
            'nCdEmpresa'          => $params->getAccessData()->getCodAdministrativo(),
            'sDsSenha'            => $params->getAccessData()->getSenha(),
            'nCdServico'          => implode(',', $servivosDePostagem),
            'sCepOrigem'          => str_replace('-', '', $params->getCepOrigem()),
            'sCepDestino'         => str_replace('-', '', $params->getCepDestino()),
            'nVlPeso'             => $params->getPeso(),
            'nCdFormato'          => $formatoEncomenda,
            'nVlComprimento'      => ($comprimento < $comprimentoMinimo && $params->getAjustarDimensaoMinima() ? $comprimentoMinimo : $comprimento),
            'nVlAltura'           => ($altura < $alturaMinima && $params->getAjustarDimensaoMinima() ? $alturaMinima : $altura),
            'nVlLargura'          => ($largura < $larguraMinima && $params->getAjustarDimensaoMinima() ? $larguraMinima : $largura),
            'nVlDiametro'         => ($diametro < $diametroMinimo && $params->getAjustarDimensaoMinima() ? $diametroMinimo : $largura),
            'sCdMaoPropria'       => ($maoPropria ? 'S' : 'N'),
            'nVlValorDeclarado'   => $valorDeclarado,
            'sCdAvisoRecebimento' => ($avisoRecebimento ? 'S' : 'N'),
        );

        try {
            $r = $this->_getSoapCalcPrecoPrazo()->calcPrecoPrazo($soapArgs);
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
            throw $e;
        }

        if (class_exists('\StaLib_Logger')) {
            \StaLib_Logger::log('Retorno SIGEP: ' . print_r($r, true));
        }
        
        $retorno = array();
        if (is_object($r) && $r->CalcPrecoPrazoResult) {
            if (is_object($r->CalcPrecoPrazoResult) && $r->CalcPrecoPrazoResult->Servicos) {
                if (is_object($r->CalcPrecoPrazoResult->Servicos) && $r->CalcPrecoPrazoResult->Servicos->cServico) {
                    $servicos = $r->CalcPrecoPrazoResult->Servicos->cServico;
                    $servicos = (is_array($servicos) ? $servicos : array($servicos));
                    
                    foreach ($servicos as $servico) {
                        $item = new \PhpSigep\Model\CalcPrecoPrazoResposta(array(
                            'servico'               => new \PhpSigep\Model\ServicoDePostagem($servico->Codigo),
                            'valor'                 => (float)str_replace(',', '.', str_replace('.', '', $servico->Valor)),
                            'prazoEntrega'          => (int)$servico->PrazoEntrega,
                            'valorMaoPropria'       => (float)str_replace(',', '.', str_replace('.', '', $servico->ValorMaoPropria)),
                            'valorAvisoRecebimento' => (float)str_replace(',', '.', str_replace('.', '', $servico->ValorAvisoRecebimento)),
                            'valorValorDeclarado'   => (float)str_replace(',', '.', str_replace('.', '', $servico->ValorValorDeclarado)),
                            'entregaDomiciliar'     => ($servico->EntregaDomiciliar == 'S'),
                            'entregaSabado'         => ($servico->EntregaSabado == 'S'),
                        ));
                        $item->setErroCodigo($servico->Erro);
                        if ($item->getErroCodigo() && ($item->getErroCodigo() != 10 || !$item->getValor())) {
                            // Esse IF retorna true quando tem Erro e esse Erro é diferente de 10 ou quando
                            // o Erro for 10 mas não tiver Valor.
                            // Idendificamos que o erro "10" é mais um aviso do que um erro.
                            // Se for erro 10 e tiver valor, agente considera que não houve erros
                            $msgErro         = $servico->MsgErro;
                            $msgErro         = utf8_encode($msgErro);
                            $item->setErroMsg($msgErro);
                        }
                        $retorno[] = $item;
                    }
                }
            }
        }
        
        $retorno = new \PhpSigep\Model\CalcPrecoPrazoRespostaIterator($retorno);
        if ($retorno->todosTemErro()) {
            $erros = array();
            /** @var $retItem \PhpSigep\Model\CalcPrecoPrazoResposta */
            foreach ($retorno as $retItem) {
                $erros[$retItem->getErroCodigo()] = $retItem->getErroMsg();
            }
            $exception = null;
            $errosPrioritarios = array('999', '-3', '-2', '-4', '-33', '7');
            foreach ($errosPrioritarios as $erroPrioritario)
            if (isset($erros[$erroPrioritario])) {
                $exception = $erros[$erroPrioritario];
                break;
            }
            if (!$exception) {
                $exception = reset($erros);
            }
            throw new Exception("Erro ao calcular prazos.\nResposta do Correios: \"" . ($exception ? $exception : 'não houve resposta') . '"');
        }

        return $retorno;
    }
}

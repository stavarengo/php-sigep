<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\Etiqueta;
use PhpSigep\Model\RastrearObjetoEvento;
use PhpSigep\Model\RastrearObjetoResultado;
use PhpSigep\Services\Real\Exception\RastrearObjeto\ExibirErrosException;
use PhpSigep\Services\Real\Exception\RastrearObjeto\RastrearObjetoException;
use PhpSigep\Services\Real\Exception\RastrearObjeto\TipoInvalidoException;
use PhpSigep\Services\Result;
use Symfony\Polyfill\Php56\Php56;

/**
 * @author: Stavarengo
 * @author: davidalves1
 */
class RastrearObjeto
{

    /**
     * @param \PhpSigep\Model\RastrearObjeto $params
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\RastrearObjetoResultado[]>
     * @throws Exception\RastrearObjeto\TipoResultadoInvalidoException
     * @throws Exception\RastrearObjeto\TipoInvalidoException
     */
    public function execute(\PhpSigep\Model\RastrearObjeto $params)
    {
        switch ($params->getTipo()) {
            case \PhpSigep\Model\RastrearObjeto::TIPO_LISTA_DE_OBJETOS:
                $tipo = 'L';
                break;
            case \PhpSigep\Model\RastrearObjeto::TIPO_INTERVALO_DE_OBJETOS:
                $tipo = 'F';
                break;
            default:
                throw new \PhpSigep\Services\Real\Exception\RastrearObjeto\TipoInvalidoException("Tipo '" . $params->getTipo(
                    ) . "' não é valido");
                break;
        }
        switch ($params->getTipoResultado()) {
            case \PhpSigep\Model\RastrearObjeto::TIPO_RESULTADO_APENAS_O_ULTIMO_EVENTO:
                $tipoResultado = 'U';
                break;
            case \PhpSigep\Model\RastrearObjeto::TIPO_RESULTADO_TODOS_OS_EVENTOS:
                $tipoResultado = 'T';
                break;
            default:
                throw new \PhpSigep\Services\Real\Exception\RastrearObjeto\TipoResultadoInvalidoException("Tipo de resultado '" . $params->getTipo(
                    ) . "' não é valido");
                break;
        }

        switch ($params->getExibirErros()) {
            case \PhpSigep\Model\RastrearObjeto::EXIBIR_RESULTADOS_COM_ERRO:
                $exibir_erro = true;
                break;
            case \PhpSigep\Model\RastrearObjeto::ESCONDER_RESULTADOS_COM_ERRO:
                $exibir_erro = false;
                break;
            default:
                throw new TipoInvalidoException("Tipo '" . $params->getExibirErros(
                    ) . "' não é válido para esta opção'");
                break;
        }

        $soapArgs = array(
            'usuario'   => $params->getAccessData()->getUsuario(),
            'senha'     => $params->getAccessData()->getSenha(),
            'tipo'      => $tipo,
            'resultado' => $tipoResultado,
            'lingua' => $params->getIdioma(),
            'objetos'    => implode(
                '',
                array_map(
                    function (\PhpSigep\Model\Etiqueta $etiqueta) {
                        return $etiqueta->getEtiquetaComDv();
                    },
                    $params->getEtiquetas()
                )
            ),
        );

        $result = new Result();

        try {
            $soapReturn = SoapClientFactory::getRastreioObjetos()->buscaEventos($soapArgs);

            if ($soapReturn && is_object($soapReturn) && $soapReturn->return) {

                try {
                    if (!is_array($soapReturn->return->objeto)) {
                        $soapReturn->return->objeto = array($soapReturn->return->objeto);
                    }

                    $resultado = array();

                    foreach ($soapReturn->return->objeto as $objeto) {

                        $eventos = new RastrearObjetoResultado();
                        $eventos->setEtiqueta(new Etiqueta(array('etiquetaComDv' => $objeto->numero)));

                        // Verifica se ocorreu algum erro ao consultar a etiqueta
                        if (isset($objeto->erro)) {
                            // Se estiver configurado para não exibir erros, não insere os resultados com erros
                            if (!$exibir_erro) {
                                continue;
                            }

                            $evento = new RastrearObjetoEvento();

                            $evento->setError(SoapClientFactory::convertEncoding('(' . $objeto->numero . ') ' . $objeto->erro));

                            // Adiciona o evento ao resultado
                            $eventos->addEvento($evento);

                        } else {

                            if (!is_array($objeto->evento))
                                $objeto->evento = array($objeto->evento);

                            foreach ($objeto->evento as $ev) {

                                $evento = new RastrearObjetoEvento();
                                $evento->setTipo($ev->tipo);
                                $evento->setStatus($ev->status);
                                $evento->setDataHora(\DateTime::createFromFormat('d/m/Y H:i', $ev->data . ' ' . $ev->hora));
                                $evento->setDescricao(SoapClientFactory::convertEncoding($ev->descricao));
                                $evento->setDetalhe(SoapClientFactory::convertEncoding(isset($ev->detalhe) ? $ev->detalhe : ''));
                                $evento->setLocal($ev->local);
                                $evento->setCodigo($ev->codigo);
                                $evento->setCidade(isset($ev->cidade) ? $ev->cidade : '');
                                $evento->setUf(isset($ev->uf) ? $ev->uf : '');

                                // Sempre adiciona o recebedor ao resultado, mesmo ele sendo exibido apenas quanto 'tipo' = BDE e 'status' = 01
                                $evento->setRecebedor(
                                    isset($ev->recebedor) && !empty($ev->recebedor) ? trim($ev->recebedor) : ''
                                );

                                // Adiciona o evento ao resultado
                                $eventos->addEvento($evento);
                            }
                        }

                        $resultado[] = $eventos;
                    }

                    $result->setResult($resultado);

                } catch (RastrearObjetoException $e) {
                    $result->setErrorCode(0);
                    $result->setErrorMsg("Erro de comunicação com o Correios ao tentar buscar os dados de rastreamento. Detalhes: " . $e->getMessage());
                }

            } else {
                $result->setErrorCode(0);
                $result->setErrorMsg('A resposta do Correios não está no formato esperado. Resposta recebida: "' .
                    $soapReturn . '"');
            }
        } catch (\Exception $e) {
            if ($e instanceof \SoapFault) {
                $result->setIsSoapFault(true);
                $result->setErrorCode($e->getCode());
                $result->setErrorMsg(SoapClientFactory::convertEncoding($e->getMessage()));
            } else {
                $result->setErrorCode($e->getCode());
                $result->setErrorMsg($e->getMessage() . ' - ' . $e->getLine());
            }
        }

        return $result;
    }
}

<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\Etiqueta;
use PhpSigep\Model\RastrearObjetoEvento;
use PhpSigep\Model\RastrearObjetoResultado;
use PhpSigep\Services\Real\Exception\RastrearObjeto\RastrearObjetoException;
use PhpSigep\Services\Result;

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
                if (!is_array($soapReturn->return)) {
                    $soapReturn->return = (array)$soapReturn->return;
                }

                try {
                    $evento = new RastrearObjetoEvento();
                    $eventos = new RastrearObjetoResultado();

                    if (!is_array($soapReturn->return['objeto'])) {
                        $soapReturn->return['objeto'] = array($soapReturn->return['objeto']);
                    }

                    foreach ($soapReturn->return['objeto'] as $objeto) {
                        $ev = $objeto->evento;

                        $evento->setTipo($ev->tipo);
                        $evento->setStatus($ev->status);
                        $evento->setData(\DateTime::createFromFormat('d/m/Y', $ev->data));
                        $evento->setHora(\DateTime::createFromFormat('H:i', $ev->hora));
                        $evento->setDescricao(SoapClientFactory::convertEncoding($ev->descricao));
                        $evento->setDetalhe(isset($ev->detalhe) ? $ev->detalhe : '');
                        $evento->setLocal($ev->local);
                        $evento->setCodigo($ev->codigo);
                        $evento->setCidade($ev->cidade);
                        $evento->setUf($ev->uf);

                        // Adiciona o evento ao resultado
                        $eventos->addEvento($evento);
                    }

                    $result->setResult($eventos->getEventos());

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
                $result->setErrorMsg($e->getMessage());
            }
        }

        return $result;
    }
}

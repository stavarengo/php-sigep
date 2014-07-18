<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\Etiqueta;
use PhpSigep\Model\RastrearObjetoEvento;
use PhpSigep\Model\RastrearObjetoResultado;
use PhpSigep\Services\Real\Exception\RastrearObjeto\RastrearObjetoException;
use PhpSigep\Services\Result;

/**
 * @author: Stavarengo
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

        $post = array(
            'usuario'   => $params->getAccessData()->getUsuario(),
            'senha'     => $params->getAccessData()->getSenha(),
            'tipo'      => $tipo,
            'Resultado' => $tipoResultado,
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

        $postContent = http_build_query($post);

        $ch = curl_init();

        curl_setopt_array(
            $ch,
            array(
                CURLOPT_URL            => 'http://websro.correios.com.br/sro_bin/sroii_xml.eventos',
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS     => $postContent,
                CURLOPT_SSL_VERIFYPEER => false,
            )
        );

        // "usuario=ECT  &senha=SRO   &tipo=L&Resultado=T&objetos=SQ458226057BRRA132678652BRSX142052885BR"
        // "Usuario=sigep&Senha=n5f9t8&Tipo=L&Resultado=T&Objetos=SQ458226057BR"
        
        $curlResult = curl_exec($ch);
        $curlErrno  = curl_errno($ch);
        $curlErr    = curl_error($ch);
        curl_close($ch);

        $result = new Result();

        if ($curlErrno) {
            $result->setErrorMsg("Erro de comunicação com o Correios ao tentar buscar os dados de rastreamento. Detalhes: \"$curlErrno - $curlErr\".");
            $result->setErrorCode($curlErrno);
        } else if (!$curlResult) {
            $result->setErrorMsg("Resposta do Correios veio vazia");
        } else {
            try {
                $eventos = $this->_parseResult($curlResult);
                $result->setResult($eventos);
            } catch (RastrearObjetoException $e) {
                $result->setErrorCode(0);
                $result->setErrorMsg($e->getMessage());
            }
        }

        return $result;
    }

    /**
     * @param $curlResult
     * @throws Exception\RastrearObjeto\RastrearObjetoException
     * @return RastrearObjetoResultado[]
     */
    private function _parseResult($curlResult)
    {
        $result = null;
//        $curlResult = SoapClientFactory::convertEncoding($curlResult);
        $simpleXml = new \SimpleXMLElement($curlResult);
        if ($simpleXml->error) {
            throw new RastrearObjetoException('Erro ao rastrear objetos. Resposta do Correios: "' . $simpleXml->error . '"');
        } else if ($simpleXml->objeto) {
            $qtdObjetos = $simpleXml->qtd;
            $objetos    = $simpleXml->objeto;
            $result    = array();
            for ($i = 0; $i < $qtdObjetos; $i++) {
                $objeto      = $objetos[$i];
                $resultado   = new RastrearObjetoResultado();
                $resultado->setEtiqueta(new Etiqueta(array('etiquetaComDv' => $objeto->numero)));
                foreach ($objeto->evento as $evento) {
                    $dataHoraStr = $evento->data . ' ' . $evento->hora;
                    $dataHora    = \DateTime::createFromFormat('d/m/Y H:i', $dataHoraStr);
                    $tipo = strtoupper($evento->tipo);
                    $status = (int)$evento->status;
                    $descricao = $evento->descricao;
                    $detalhes = null;
                    if ($tipo == 'PO' && $status === 9) {
                        $detalhes = 'Objeto sujeito a encaminhamento no próximo dia útil.';
                    } else if ($evento->destino
                        && (($tipo == 'DO' && in_array($status, array(0, 1, 2)))
                        || ($tipo == 'PMT' && $status === 1)
                        || ($tipo == 'TRI' && $status === 1)
                        || ($tipo == 'RO' && in_array($status, array(0, 1)))
                    )) {
                        $detalhes = 'Objeto encaminhado para ' . $evento->destino->cidade . '/' . $evento->destino->uf;
                        if ($evento->destino->bairro) {
                            $detalhes .= ' - Bairro: ' . $evento->destino->bairro;
                        }
                        if ($evento->destino->local) {
                            $detalhes .= ' - Local: ' . $evento->destino->local;
                        }
                    }

                    $resultado->addEvento(new RastrearObjetoEvento(array(
                        'tipo'      => $tipo,
                        'status'    => $status,
                        'dataHora'  => $dataHora,
                        'descricao' => $descricao,
                        'detalhes'  => $detalhes,
                        'local'     => $evento->local,
                        'codigo'    => $evento->codigo,
                        'cidade'    => $evento->cidade,
                        'uf'        => $evento->uf,
                    )));
                }
                
                $result[] = $resultado;
            }
        }
        
        return $result;
    }

}

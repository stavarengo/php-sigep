<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\Destinatario;
use PhpSigep\Model\SolicitaPostagemReversaRetorno;
use PhpSigep\Model\SolicitaPostagemReversa;
use PhpSigep\Services\Result;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class SolicitarPostagemReversa
{

    /**
     * @param SolicitaPostagemReversa $params
     *
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\SolicitaPostagemReversa>
     */
    public function execute(\PhpSigep\Model\SolicitaPostagemReversa $params)
    {

        // return $params;

        /**
         * TODO criar mapa para validação dos dados.
         */
        $soapArgs = array(
            'codAdministrativo'     => $params->getAccessData()->getCodAdministrativo(),
            'codigo_servico'        => $params->getAccessData()->getCodigoServico(),
            'cartao'                => $params->getAccessData()->getCartaoPostagem(),
            'destinatario'          => $params->getDestinatario()->getObjects(),
            'coletas_solicitadas'   => $params->getColetasSolicitadas()->getObjects()
        );

        $soapArgs['coletas_solicitadas']['remetente'] = $params->getColetasSolicitadas()->getRemetente()->getObjects();

        /**
         * Se não houver dados do objeto produto, não incluir no escopo da solicitação.
         *
         */
        if ($params->getColetasSolicitadas()->getProduto() != null ) {
            $soapArgs['coletas_solicitadas']['produto'] = $params->getColetasSolicitadas()->getProduto()->getObjects();
        } else {
            unset($soapArgs['coletas_solicitadas']['produto']);
        }

        /**
         * Se não houver dados do objeto obj_col, não incluir no escopo da solicitação.
         *
         */
        if ( $params->getColetasSolicitadas()->getObjCol() != null ) {
            $soapArgs['coletas_solicitadas']['obj_col'] =  $params->getColetasSolicitadas()->getObjCol();
        } else {
            unset($soapArgs['coletas_solicitadas']['obj_col']);
        }

        $result = new Result();
        try {
            $r = SoapClientFactory::getSoapClient()->solicitarPostagemReversa($soapArgs);

            if (class_exists('\StaLib_Logger',false)) {
                \StaLib_Logger::log('Retorno SIGEP solicitarPostagemReversa: ' . print_r($r, true));
            }

            if ($r instanceof \SoapFault) {
                throw $r;
            }

            if ($r && $r->solicitarPostagemReversa) {
                return $r->solicitarPostagemReversa->resultado_solicitacao;

            } else {
                $result->setErrorCode(0);
                $result->setErrorMsg('A resposta do Correios não está no formato esperado. Resposta recebida: "' .
                    $r . '"');
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

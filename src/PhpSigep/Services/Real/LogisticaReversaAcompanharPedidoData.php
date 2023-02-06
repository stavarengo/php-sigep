<?php

namespace PhpSigep\Services\Real;
use PhpSigep\Services\Result;
use PhpSigep\Model\LogisticaReversaPedidoResposta;

/**
 * @author rodrigojob
 */
class LogisticaReversaAcompanharPedidoData
{
    public function execute($accessData, $tipoSolicitacao, $data)
    {

        $soapArgs = array(
            'codAdministrativo' => $accessData->getCodAdministrativo(),
            'tipoSolicitacao'   => $tipoSolicitacao,
            'data'              => $data,
        );

        $r = SoapClientFactory::getSoapLogisticaReversa()->acompanharPedidoPorData($soapArgs);

        $errorCode = null;
        $errorMsg = null;
        $result = new Result();
        if (!$r) {
            $errorCode = 0;
        } else if ($r instanceof \SoapFault) {
            $errorCode = $r->getCode();
            $errorMsg = SoapClientFactory::convertEncoding($r->getMessage());
            $result->setSoapFault($r);
        } else if ($r instanceof \stdClass && property_exists($r, 'acompanharPedidoPorData')) {

            $status = new LogisticaReversaPedidoResposta();
            $status->setReturn($r->acompanharPedidoPorData);
            $result->setResult($status);
        } else {
            $errorCode = 0;
            $errorMsg = "A resposta do Correios não está no formato esperado.";
        }

        $result->setErrorCode($errorCode);
        $result->setErrorMsg($errorMsg);

        return $result;
    }

}
<?php

namespace PhpSigep\Services\Real;
use PhpSigep\Services\Result;
use PhpSigep\Model\LogisticaReversaPedidoResposta;

/**
 * @author rodrigojob
 */

class LogisticaReversaCancelarPedido
{
    public function execute($params)
    {
        $authArgs = array(
            'usuario'   => $params["accessData"]->getUsuario(),
            'senha'     => $params["accessData"]->getSenha(),
        );
        $soapArgs = array(
            'codAdministrativo' => $params["accessData"]->getCodAdministrativo(),
            'tipo'              => $params["dados"]["tipoSolicitacao"],
            'numeroPedido'      => $params["dados"]["numeroPedido"],
        );

        $r = SoapClientFactory::getSoapLogisticaReversa()->cancelarPedido($soapArgs, $authArgs);

        $errorCode = null;
        $errorMsg = null;
        $result = new Result();
        if (!$r) {
            $errorCode = 0;
        } else if ($r instanceof \SoapFault) {
            $errorCode = $r->getCode();
            $errorMsg = SoapClientFactory::convertEncoding($r->getMessage());
            $result->setSoapFault($r);
        } else if ($r instanceof \stdClass && property_exists($r, 'cancelarPedido')) {
            print_r($r);
            $status = new LogisticaReversaPedidoResposta();
            $status->setReturn($r->cancelarPedido);
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
<?php

namespace PhpSigep\Services\Real;
use PhpSigep\Services\Result;
use PhpSigep\Model\LogisticaReversaPedidoResposta;

/**
 * @author rodrigojob
 * desenvolvimento@econector.com.br
 */

class LogisticaReversaAcompanharPedidoNumero
{
    public function execute($accessData, $tipoBusca, $tipoSolicitacao, $numPedido)
    {
        $authArgs = array(
            'usuario' => $accessData->getUsuario(),
            'senha' => $accessData->getSenha(),
        );
        $soapArgs = array(
            'codAdministrativo' => $accessData->getCodAdministrativo(),
            'codAdministrativo' => $accessData->getCodAdministrativo(),
            'tipoBusca'         => $tipoBusca,
            'tipoSolicitacao'   => $tipoSolicitacao,
            'numeroPedido'      => $numPedido,
        );

        $r = SoapClientFactory::getSoapLogisticaReversa()->acompanharPedido($soapArgs, $authArgs);

        $errorCode = null;
        $errorMsg = null;
        $result = new Result();
        if (!$r) {
            $errorCode = 0;
        } else if ($r instanceof \SoapFault) {
            $errorCode = $r->getCode();
            $errorMsg = SoapClientFactory::convertEncoding($r->getMessage());
            $result->setSoapFault($r);
        } else if ($r instanceof \stdClass && property_exists($r, 'acompanharPedido')) {

            $status = new LogisticaReversaPedidoResposta();
            $status->setReturn($r->acompanharPedido);
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
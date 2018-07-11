<?php

namespace PhpSigep\Services\Real;

use PhpSigep\Model\BloquearObjetoResposta;
use PhpSigep\Services\Result;

/**
 * @author jonyw4
 */
class BloquearObjeto
{
    public function execute($numeroEtiqueta, $idPlp, $usuario, $senha)
    {
        $soapArgs = array(
            'numeroEtiqueta'    => $numeroEtiqueta,
            'idPlp'             => $idPlp,
            'tipoBloqueio'      => 'FRAUDE_BLOQUEIO',
            'acao'              => 'DEVOLVIDO_AO_REMETENTE',
            'usuario'           => $usuario,
            'senha'             => $senha
        );

        $r = SoapClientFactory::getSoapClient()->bloquearObjeto($soapArgs);

        $errorCode = null;
        $errorMsg = null;
        $result = new Result();
        if (!$r) {
            $errorCode = 0;
        } else if ($r instanceof \SoapFault) {
            $errorCode = $r->getCode();
            $errorMsg = SoapClientFactory::convertEncoding($r->getMessage());
            $result->setSoapFault($r);
        } else if ($r instanceof \stdClass && property_exists($r, 'return')) {
            $status = new BloquearObjetoResposta();
            $status->setReturn($r->return);
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

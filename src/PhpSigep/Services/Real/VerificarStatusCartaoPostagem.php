<?php

namespace PhpSigep\Services\Real;

use PhpSigep\Model\VerificarStatusCartaoPostagemResposta;
use PhpSigep\Services\Result;

/**
 * @author davidalves1
 */
class VerificarStatusCartaoPostagem
{
    public function execute($numeroCartaoPostagem, $usuario, $senha)
    {
        $soapArgs = array(
            'numeroCartaoPostagem' => $numeroCartaoPostagem,
            'usuario' => $usuario,
            'senha' => $senha
        );

        $r = SoapClientFactory::getSoapClient()->getStatusCartaoPostagem($soapArgs);

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
            $status = new VerificarStatusCartaoPostagemResposta();
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
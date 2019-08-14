<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\ConsultaCepResposta;
use PhpSigep\Services\Result;

/**
 * @author: Stavarengo
 */
class ConsultaCep
{

    public function execute($cep)
    {
        $cep = preg_replace('/[^\d]/', '', $cep);

        $soapArgs = array(
            'cep' => $cep,
        );

        $r = SoapClientFactory::getSoapClient()->consultaCep($soapArgs);

        $errorCode = null;
        $errorMsg  = null;
        $result    = new Result();
        if (!$r) {
            $errorCode = 0;
        } else if ($r instanceof \SoapFault) {
            $errorCode = $r->getCode();
            $errorMsg  = SoapClientFactory::convertEncoding($r->getMessage());
            $result->setSoapFault($r);
        } else if ($r instanceof \stdClass) {
             if (property_exists($r, 'return') && $r->return instanceof \stdClass) {
                $consultaCepResposta = new ConsultaCepResposta();
                $consultaCepResposta->setBairro(SoapClientFactory::convertEncoding($r->return->bairro));
                $consultaCepResposta->setCep($r->return->cep);
                $consultaCepResposta->setCidade(SoapClientFactory::convertEncoding($r->return->cidade));
                if (isset($r->return->complemento1)) {
                    $consultaCepResposta->setComplemento1(SoapClientFactory::convertEncoding($r->return->complemento));
                }
                if (isset($r->return->complemento2)) {
                    $consultaCepResposta->setComplemento2(SoapClientFactory::convertEncoding($r->return->complemento2));
                }
                if (isset($r->return->end)) {
                    $consultaCepResposta->setEndereco(SoapClientFactory::convertEncoding($r->return->end));
                }
                if (isset($r->return->id)) {
                    $consultaCepResposta->setId($r->return->id);
                }
                $consultaCepResposta->setUf($r->return->uf);
                
                 
                $result->setResult($consultaCepResposta);
             } else {
                 $errorCode = 0;
                 $errorMsg = "Resposta em branco. Confirme se o CEP '$cep' realmente existe.";
             }
        } else {
            $errorCode = 0;
            $errorMsg  = "A resposta do Correios não está no formato esperado.";
        }

        $result->setErrorCode($errorCode);
        $result->setErrorMsg($errorMsg);

        return $result;
    }

}

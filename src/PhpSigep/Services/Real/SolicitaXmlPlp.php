<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\SolicitaXmlPlpResult;
use PhpSigep\Services\Exception;
use PhpSigep\Services\Result;
use PhpSigep\Bootstrap;
use PhpSigep\Services\Real\Exception\SolicitaXmlPlp\FailedConvertToArrayException;
use PhpSigep\Services\Real\Exception\SolicitaXmlPlp\FailedConvertXmlException;
use PhpSigep\Services\Real\Exception\SolicitaXmlPlp\FailedResultException;

/**
 * @author: Cristiano Soares
 * @link: http://comerciobr.com
 */
class SolicitaXmlPlp
{
    /**
     * @param integer $idPlpMaster
     *
     * @throws \PhpSigep\Services\Exception
     * @return Result<SolicitaXmlPlpResult[]>
     */
    public function execute($idPlpMaster)
    {
        $soapArgs = array(
            'idPlpMaster'   => $idPlpMaster,
            'usuario'        => Bootstrap::getConfig()->getAccessData()->getUsuario(),
            'senha'          => Bootstrap::getConfig()->getAccessData()->getSenha()
        );

        $result = new Result();

        try {
            $r = SoapClientFactory::getSoapClient()->solicitaXmlPlp($soapArgs);
            if (!$r || !is_object($r) || !isset($r->return) || ($r instanceof \SoapFault)) {
                if ($r instanceof \SoapFault) {
                    throw $r;
                }

                throw new FailedResultException('Erro ao consultar XML da PLP. Retorno: "' . $r . '"');
            }

            if (is_string($r->return)) {
                libxml_use_internal_errors(true);
                $xmlString = iconv('utf-8', 'ISO-8859-1//IGNORE', $r->return);
                $xml = simplexml_load_string($xmlString, \SimpleXMLElement::class, LIBXML_NOCDATA);
                if ($xml instanceof \SimpleXMLElement) {
                    $objectToarray = json_decode(json_encode($xml), true);

                    if ($objectToarray) {
                        $result->setResult(new SolicitaXmlPlpResult($objectToarray));
                    } else {
                        throw new FailedConvertToArrayException('Erro ao converter Object para Array da PLP. Retorno: "' . print_r(json_last_error_msg(), true) . '"');
                    }
                } else {
                    throw new FailedConvertXmlException('Erro ao converter XML da PLP. Retorno: "' . print_r(libxml_get_errors(), true) . '"');
                }
            } else {
                throw new FailedResultException('Erro no resultado do XML da PLP. Retorno: "' . print_r($r->return, true) . '"');
            }
        } catch (\Exception $e) {
            if ($e instanceof \SoapFault) {
                $result->setIsSoapFault(true);
                $result->setErrorCode($e->getCode());
                $result->setErrorMsg("Resposta do Correios: " . SoapClientFactory::convertEncoding($e->getMessage()));
            } else {
                $result->setErrorCode($e->getCode());
                $result->setErrorMsg($e->getMessage());
            }
        }

        return $result;
    }
}

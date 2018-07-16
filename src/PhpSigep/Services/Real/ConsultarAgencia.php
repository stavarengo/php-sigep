<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\ConsultarAgenciaResult;
use PhpSigep\Services\Exception;
use PhpSigep\Services\Result;
use PhpSigep\Bootstrap;
use PhpSigep\Services\Real\Exception\AgenciaWS\FailedConvertToArrayException;
use PhpSigep\Services\Real\Exception\AgenciaWS\FailedConvertXmlException;
use PhpSigep\Services\Real\Exception\AgenciaWS\FailedResultException;

/**
 * @author: Cristiano Soares
 * @link: http://comerciobr.com
 */
class ConsultarAgencia
{
    /**
     * @param string $codigo
     *
     * @throws \PhpSigep\Services\Exception
     * @return Result<ConsultarAgenciaResult[]>
     */
    public function execute($codigo)
    {
        $soapArgs = array(
            'codigo'   => $codigo
        );

        $result = new Result();

        try {
            $r = SoapClientFactory::getSoapAgenciaWS()->consultarAgencia($soapArgs);
            if (!$r || !is_object($r) || !isset($r->resultadoAgenciaDetalhe) || ($r instanceof \SoapFault)) {
                if ($r instanceof \SoapFault) {
                    throw $r;
                }

                throw new FailedResultException('Erro ao Consultar Agencia. Retorno: "' . $r . '"');
            }

            if ($r instanceof \stdClass) {
                $objectToarray = json_decode(json_encode($r), true);

                if ($objectToarray) {
                    $result->setResult(new ConsultarAgenciaResult($objectToarray['resultadoAgenciaDetalhe']));
                } else {
                    throw new FailedConvertToArrayException('Erro ao converter Object para Array da Busca. Retorno: "' . print_r(json_last_error_msg(), true) . '"');
                }
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

<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\ListarAgenciasCliqueRetireResult;
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
class ListarAgenciasCliqueRetire
{
    /**
     * @param string $zone
     * @param string $city
     * @param string $address_2
     *
     * @throws \PhpSigep\Services\Exception
     * @return Result<ListarAgenciasCliqueRetireResult[]>
     */
    public function execute($zone, $city, $address_2)
    {
        $soapArgs = array(
            'uf'   => $zone,
            'municipio'   => $city,
            'bairro'   => $address_2
        );

        $result = new Result();

        try {
            $r = SoapClientFactory::getSoapAgenciaWS()->listarAgenciasCliqueERetirePorLocalidade($soapArgs);
            if (!$r || !is_object($r) || !isset($r->resultadoListaAgencia) || ($r instanceof \SoapFault)) {
                if ($r instanceof \SoapFault) {
                    throw $r;
                }

                throw new FailedResultException('Erro ao listar agencias Clique e Retire. Retorno: "' . $r . '"');
            }

            if ($r instanceof \stdClass) {
                $objectToarray = json_decode(json_encode($r), true);

                if ($objectToarray) {
                    $result->setResult(new ListarAgenciasCliqueRetireResult($objectToarray['resultadoListaAgencia']));
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

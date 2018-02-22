<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\CancelaPostagemReversaRetorno;
use PhpSigep\Model\CancelaPostagemReversa;
use PhpSigep\Services\Result;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class CancelarPostagemReversa
{

    /**
     * @param CancelaPostagemReversa $params
     *
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\CancelaPostagemReversa>
     */
    public function execute(CancelaPostagemReversa $params)
    {

        /**
         * TODO criar mapa para validação dos dados.
         */
        $soapArgs = array(
            'codAdministrativo'     => $params->getAccessData()->getCodAdministrativo(),
            'numeroPedido'          => $params->getNumeroPedido(),
            'tipo'                  => $params->getTipo()
        );

        $result = new Result();
        try {
            $r = SoapClientFactory::getSoapClient()->cancelarPedido($soapArgs);

            if (class_exists('\StaLib_Logger',false)) {
                \StaLib_Logger::log('Retorno SIGEP cancelarPedido: ' . print_r($r, true));
            }

            if ($r instanceof \SoapFault) {
                throw $r;
            }

            if ($r && $r->cancelarPedido) {

                $result->setResult(new CancelaPostagemReversaRetorno(
                        array(
                            'objetoPostal' => $r->cancelarPedido
                        )
                    )
                );

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

<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\AcompanhaPostagemReversaRetorno;
use PhpSigep\Model\AcompanhaPostagemReversa;
use PhpSigep\Services\Result;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class AcompanharPostagemReversa
{

    /**
     * @param AcompanhaPostagemReversa $params
     *
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\AcompanhaPostagemReversa>
     */
    public function execute(AcompanhaPostagemReversa $params)
    {

        /**
         * TODO criar mapa para validação dos dados.
         */
        $soapArgs = array(
            'codAdministrativo'     => $params->getAccessData()->getCodAdministrativo(),
            'numeroPedido'          => $params->getNumeroPedido(),
            'tipoBusca'             => $params->getTipoBusca(),
            'tipoSolicitacao'       => $params->getTipoSolicitacao()
        );

        $result = new Result();
        try {
            $r = SoapClientFactory::getSoapClient()->acompanharPedido($soapArgs);

            if (class_exists('\StaLib_Logger',false)) {
                \StaLib_Logger::log('Retorno SIGEP acompanharPedido: ' . print_r($r, true));
            }

            if ($r instanceof \SoapFault) {
                throw $r;
            }

            if ($r && $r->acompanharPedido) {

                $result->setResult(new AcompanhaPostagemReversaRetorno(
                        array(
                            'coleta' => $r->acompanharPedido
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

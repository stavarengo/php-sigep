<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\Destinatario;
use PhpSigep\Model\FechaSolicitacaoReversaRetorno;
use PhpSigep\Model\PreSolicitacaoDePostagemReversa;
use PhpSigep\Services\Result;

/**
 * @author WilliamNovak
 */
class FechaPreSolicitacaoPostagemReversa
{

    /**
     * @param PreListaDePostagem $params
     *
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\FechaPreSolicitacaoPostagemReversa>
     */
    public function execute(\PhpSigep\Model\PreSolicitacaoDePostagemReversa $params)
    {

        $soapArgs = array(
            'codAdministrativo' => $params->getAccessData()->getCodAdministrativo(),
            'codigo_servico' => $params->getAccessData()->getCodigoServico(),
            'cartao' => $params->getAccessData()->getCartaoPostagem(),
            'destinatario' => $params->getDestinatario()->getObjects(),
            'coletas_solicitadas' => $params->getColetasSolicitadas()->getObjects()
        );

        $soapArgs['coletas_solicitadas']['remetente'] = $params->getColetasSolicitadas()->getRemetente()->getObjects();
        $soapArgs['coletas_solicitadas']['produto'] = $params->getColetasSolicitadas()->getProduto()->getObjects();
        $soapArgs['coletas_solicitadas']['obj_col'] = $params->getColetasSolicitadas()->getObjCol()->getObjects();

        $result = new Result();
        try {
            $r = SoapClientFactory::getSoapClient()->solicitarPostagemReversa($soapArgs);

            if (class_exists('\StaLib_Logger',false)) {
                \StaLib_Logger::log('Retorno SIGEP fecha solicitacao de postagem: ' . print_r($r, true));
            }

            if ($r instanceof \SoapFault) {
                throw $r;
            }

            if ($r && $r->solicitarPostagemReversa) {
                $result->setResult(new FechaSolicitacaoReversaRetorno(
                    array(
                        'numeroColeta' => $r->solicitarPostagemReversa)
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

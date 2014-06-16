<?php
namespace PhpSigep\Services\Real;
use PhpSigep\Model\ServicoDePostagem;
use PhpSigep\Model\VerificaDisponibilidadeServicoResposta;
use PhpSigep\Services\Result;

/**
 * @author: Stavarengo
 */
class VerificaDisponibilidadeServico
{

    public function execute(\PhpSigep\Model\VerificaDisponibilidadeServico $params)
    {
        $servicosDePostagem = $params->getServicos();

        $codigoDosServicos = array_map(array($this, 'arrayMapCallback'), $servicosDePostagem);
        
        $soapArgs = array(
            'codAdministrativo' => $params->getAccessData()->getCodAdministrativo(),
            'numeroServico'     => implode(',', $codigoDosServicos),
            'cepOrigem'         => $params->getCepOrigem(),
            'cepDestino'        => $params->getCepDestino(),
            'usuario'           => $params->getAccessData()->getUsuario(),
            'senha'             => $params->getAccessData()->getSenha(),
        );
        
        $r = SoapClientFactory::getSoapClient()->verificaDisponibilidadeServico($soapArgs);

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
            $result->setResult(new VerificaDisponibilidadeServicoResposta(array('disponivel' => (bool)$r->return)));
        } else {
            $errorCode = 0;
            $errorMsg = "A resposta do Correios não está no formato esperado.";
        }
        
        $result->setErrorCode($errorCode);
        $result->setErrorMsg($errorMsg);
        
        return $result;
    }
    
    private function arrayMapCallback(ServicoDePostagem $servicoDePostagem) {
        return $servicoDePostagem->getCodigo();
    }
}

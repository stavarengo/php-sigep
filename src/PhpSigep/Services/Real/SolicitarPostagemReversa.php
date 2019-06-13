<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\AbstractModel;
use PhpSigep\Services\Exception;
use PhpSigep\Services\Result;

/**
 * @author: Stavarengo
 */
class SolicitarPostagemReversa implements RealServiceInterface
{

    /**
     * @param \PhpSigep\Model\AbstractModel|\PhpSigep\Model\SolicitarPostagemReversa $params
     *
     * @throws \PhpSigep\Services\Exception
     * @throws InvalidArgument
     * @return Result
     */
    public function execute(AbstractModel $params)
    {

        if (!$params instanceof \PhpSigep\Model\SolicitarPostagemReversa) {
            throw new InvalidArgument();
        }
        
        $result = new Result();

        try {
            if (!$params->getAccessData() || !$params->getAccessData()->getUsuario() || !$params->getAccessData()->getSenha()
            ) {
                throw new Exception('Para usar este serviço você precisa setar o nome de usuário e senha.');
            }
            $soapArgs = [
                'usuario' => $params->getAccessData()->getUsuario(),
                'senha' => $params->getAccessData()->getSenha(),
                'codAdministrativo' => $params->getAccessData()->getCodAdministrativo(),
                'contrato' => $params->getContrato(),
                'codigo_servico' => $params->getCodigoServico(),
                'cartao' => $params->getAccessData()->getCartaoPostagem(),
                'destinatario' => $params->getDestinatario()->toArray(),
                'coletas_solicitadas' => $params->getColetasSolicitadas()->toArray()
            ];
            
            echo '<pre>'; var_dump($soapArgs);echo'</pre>';exit;
            $r = SoapClientFactory::getSoapReversa()->solicitarPostagemReversa($soapArgs);
            
            if (!$r || !is_object($r) || !isset($r->return) || ($r instanceof \SoapFault)) {
                if ($r instanceof \SoapFault) {
                    throw $r;
                }

                throw new \Exception('Erro ao consultar XML ');
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

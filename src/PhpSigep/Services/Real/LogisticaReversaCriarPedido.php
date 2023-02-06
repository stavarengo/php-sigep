<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\AbstractModel;
use PhpSigep\Model\LogisticaReversaPedido;
use PhpSigep\Model\LogisticaReversaPedidoResposta;
use PhpSigep\Services\Exception;
use PhpSigep\Services\InvalidArgument;
use PhpSigep\Services\Result;

/**
 * @author: rodrigojob
 */
class LogisticaReversaCriarPedido implements RealServiceInterface
{

    private function validAfterRequest($request){
        if (class_exists('\StaLib_Logger',false)) {
            \StaLib_Logger::log('Retorno Logistica Reversa criar pedido: ' . print_r($request, true));
        }
                   
        if ($request && is_object($request) && isset($request->return) && !($request instanceof \SoapFault)) {
            return true;
        } else {
            if ($request instanceof \SoapFault) {
                throw $request;
            }
            throw new \Exception('Não foi possível criar o pedido. Retorno: "' . $request . '"');
        }
    }
    

    /**
     * @param \PhpSigep\Model\AbstractModel|\PhpSigep\Model\LogisticaReversaCriarPedido $params
     *
     * @throws \PhpSigep\Services\Exception
     * @throws \PhpSigep\Services\InvalidArgument
     * @return Result<Pedido[]>
     */
    public function execute(AbstractModel $params)
    {
        if (!$params instanceof LogisticaReversaPedido) {
            throw new InvalidArgument();
        }

        $result = new Result();
   
        try {
            if (!$params->getAccessData() || !$params->getAccessData()->getUsuario()
                || !$params->getAccessData()->getSenha()
            ) {
                throw new Exception('Para usar este serviço você precisa setar o nome de usuário e senha.');
            }

            $authArgs = array(
                'usuario'          => $params->getAccessData()->getUsuario(),
                'senha'            => $params->getAccessData()->getSenha(),
            );
            $parametros = $params->getDados();
            
            $r = SoapClientFactory::getSoapLogisticaReversa()->solicitarPostagemReversa($parametros, $authArgs);
                                  
            if (!$r) {
                $errorCode = 0;
            } else if ($r instanceof \SoapFault) {
                $errorCode = $r->getCode();
                $errorMsg = SoapClientFactory::convertEncoding($r->getMessage());
                $result->setSoapFault($r);
            } else if ($r instanceof \stdClass && property_exists($r, 'solicitarPostagemReversa')) {       
                $status = new LogisticaReversaPedidoResposta();
                $status->setReturn($r->solicitarPostagemReversa);
                $result->setResult($status);
            } else {
                $errorCode = 0;
                $errorMsg = "A resposta do Correios não está no formato esperado.";
            }
        } catch (\Exception $e) {     
        }

        return $result;
    }
    
}

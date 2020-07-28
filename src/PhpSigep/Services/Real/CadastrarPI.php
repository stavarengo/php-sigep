<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Bootstrap;
use PhpSigep\Services\Result;
use PhpSigep\Model\AbstractModel;
use PhpSigep\Services\Exception;

/**
 * @author: Rodrigo Job <email:desenvolvimento@econector.com.br>
 */
class CadastrarPI 
{

    /**
     * @param \PhpSigep\Model\AbstractModel|\PhpSigep\Model\PedidoInformacao $params
     *
     * @throws \PhpSigep\Services\Exception
     * @throws InvalidArgument
     * @return Result
     */
    public function execute(AbstractModel $params)
    {

        $result = new Result();
        if (!$params instanceof \PhpSigep\Model\PedidoInformacao) {
            throw new InvalidArgument();
        }

        try {
            
            if (!Bootstrap::getConfig()->getAccessData()|| !Bootstrap::getConfig()->getAccessData()->getIdCorreiosUsuario() || !Bootstrap::getConfig()->getAccessData()->getIdCorreiosSenha()
            ) {
                throw new Exception('Para usar este serviço você precisa setar o nome de usuário e senha.');
            }
            
            $soapArgs = [
                'contrato' => Bootstrap::getConfig()->getAccessData()->getNumeroContrato(),
                'cartao' => $params->getCartao(),
                'telefone' => $params->getTelefone(),
                'pi' => $params->getPIs(),
            ];
            
            $soapArgs = $this->filtraValNull($soapArgs);

            $result = SoapClientFactory::getSoapPI()->CadastrarPIComContrato($soapArgs);

            if (!$result || !is_object($result) || !isset($result->pedido) || ($result instanceof \SoapFault)) {
                if ($result instanceof \SoapFault) {
                    throw $result;
                }
                if ($result->pedido) {
                    if (!empty($result->pedido->codigoRetorno)) {
                        throw new \Exception($result->pedido->descricaoRetorno, (int) $result->pedido->codigoRetorno);
                    }
                    
                    return $result;
                }
                throw new \Exception('Falha na leitura do XML (' . var_export($result) . ')', 400);
            }
        } catch (Exception $e) {
            print_r($e);
            if ($e instanceof \SoapFault) {
                $result->setIsSoapFault(true);
            }

            $result->setErrorCode($e->getCode());
            $result->setErrorMsg($e->getMessage());
        }

        return $result;
    }

    private function filtraValNull($arr)
    {
        $new_arr = [];
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $new_arr[$key] = $this->filtraValNull($val);
                continue;
            }

            if (!$val) {
                continue;
            }
            $new_arr[$key] = $val;
        }

        return $new_arr;
    }
}

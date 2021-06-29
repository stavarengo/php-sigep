<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Bootstrap;
use PhpSigep\InvalidArgument;
use PhpSigep\Services\Result;
use PhpSigep\Model\AbstractModel;
use PhpSigep\Model\PedidoInformacaoResponse;
use PhpSigep\Services\Exception;

/**
 * @author: Rodrigo Job <email:desenvolvimento@econector.com.br>
 * @author: Bruno Maia <email:brunopmaia@gmail.com>
 */
class CadastrarPI 
{

    /**
     * @param \PhpSigep\Model\AbstractModel|\PhpSigep\Model\PedidoInformacao $params
     *
     * @throws \PhpSigep\InvalidArgument
     * @throws \Exception
     * @throws \SoapFault
     * @return Result
     */
    public function execute(AbstractModel $params)
    {
        $result = new Result();

        if (!$params instanceof \PhpSigep\Model\PedidoInformacao) {
            throw new InvalidArgument();
        }

        try {
            if (!Bootstrap::getConfig()->getAccessData()
                || !Bootstrap::getConfig()->getAccessData()->getIdCorreiosUsuario()
                || !Bootstrap::getConfig()->getAccessData()->getIdCorreiosSenha()
            ) {
                throw new Exception('Para usar este serviço você precisa setar o nome de usuário e senha.');
            }
            
            $soapArgs = array(
                'contrato'               => Bootstrap::getConfig()->getAccessData()->getNumeroContrato(),
                'cartao'                 => Bootstrap::getConfig()->getAccessData()->getCartaoPostagem(),
                'telefone'               => $params->getTelefone(),
                'pi' => array(
                    'codigoObjeto'           => $params->getCodigoObjeto(),
                    'emailResposta'          => $params->getEmailResposta(),
                    'nomeDestinatario'       => $params->getNomeDestinatario(),
                    'codigoMotivoReclamacao' => $params->getCodigoMotivoReclamacao(),
                    'tipoEmbalagem'          => $params->getTipoEmbalagem(),
                    'tipoManifestacao'       => $params->getTipoManifestacao(),
                )
            );
            
            $soapArgs = $this->filtraValNull($soapArgs);

            $retorno = SoapClientFactory::getSoapPI()->cadastrarPIComContrato($soapArgs);

            if (!$retorno || !is_object($retorno) || ($retorno instanceof \SoapFault)) {
                if ($retorno instanceof \SoapFault) {
                    throw $retorno;
                }

                throw new \Exception('Erro ao cadastrar Pedido de Informação. Retorno: "' . var_export($retorno) . '"');
            }

            if ($retorno instanceof \stdClass) {
                $pedido = json_decode(json_encode($retorno->pedido), true);
                $pi     = array_pop($pedido);

                $objectToarray = array_merge($pedido, $pi);

                if ($objectToarray) {
                    $result->setResult(new PedidoInformacaoResponse($objectToarray));
                } else {
                    throw new FailedConvertToArrayExceptio('Erro ao converter Object para Array da Busca. Retorno: "' . print_r(json_last_error_msg(), true) . '"');
                }
            }
        } catch (Exception $e) {
            if ($e instanceof \SoapFault) {
                $result->setIsSoapFault(true);
            }

            $result->setErrorCode($e->getCode());
            $result->setErrorMsg($e->getMessage());
        }

        return $result;
    }

    /**
     * Remove os parâmetros não informados
     *
     * @param $arr
     * @return array
     */
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

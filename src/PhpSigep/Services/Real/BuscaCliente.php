<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\AbstractModel;
use PhpSigep\Model\BuscaClienteResult;
use PhpSigep\Services\Exception;
use PhpSigep\Services\Result;

/**
 * @author: Stavarengo
 */
class BuscaCliente implements RealServiceInterface
{

    /**
     * @param \PhpSigep\Model\AbstractModel|\PhpSigep\Model\SolicitaEtiquetas $params
     *
     * @throws \PhpSigep\Services\Exception
     * @throws InvalidArgument
     * @return BuscaClienteResult
     */
    public function execute(AbstractModel $params)
    {
        if (!$params instanceof \PhpSigep\Model\AccessData) {
            throw new InvalidArgument();
        }

        $soapArgs = array(
            'idContrato'       => $params->getNumeroContrato(),
            'idCartaoPostagem' => $params->getCartaoPostagem(),
            'usuario'          => $params->getUsuario(),
            'senha'            => $params->getSenha(),
        );

        $result = new Result();

        try {
            if (!$params->getUsuario() || !$params->getSenha() || !$params->getNumeroContrato()
                || !$params->getCartaoPostagem()
            ) {
                throw new Exception('Para usar este serviço você precisa setar o nome de usuário, a senha, o numero ' .
                    'do contrato e o número do cartão de postagem.');
            }

            $r = SoapClientFactory::getSoapClient()->buscaCliente($soapArgs);
            if (!$r || !is_object($r) || !isset($r->return) || ($r instanceof \SoapFault)) {
                if ($r instanceof \SoapFault) {
                    throw $r;
                }
                throw new \Exception('Erro ao consultar os dados do cliente. Retorno: "' . $r . '"');
            }

            $result->setResult(new BuscaClienteResult((array)$r->return));
        } catch (\SoapFault $soapFault) {
            $result->setIsSoapFault(true);
            $result->setErrorCode($soapFault->getCode());
            $result->setErrorMsg(SoapClientFactory::convertEncoding($soapFault->getMessage()));
        } catch (\Exception $e) {
            $result->setErrorCode($e->getCode());
            $result->setErrorMsg($e->getMessage());
        }

        return $result;
    }
}

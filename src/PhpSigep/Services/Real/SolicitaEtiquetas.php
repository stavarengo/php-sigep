<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\AbstractModel;
use PhpSigep\Model\Etiqueta;
use PhpSigep\Services\Exception;
use PhpSigep\Services\Result;

/**
 * @author: Stavarengo
 */
class SolicitaEtiquetas implements RealServiceInterface
{

    /**
     * @param \PhpSigep\Model\AbstractModel|\PhpSigep\Model\SolicitaEtiquetas $params
     *
     * @throws \PhpSigep\Services\Exception
     * @throws InvalidArgument
     * @return Result<Etiqueta[]>
     */
    public function execute(AbstractModel $params)
    {
        if (!$params instanceof \PhpSigep\Model\SolicitaEtiquetas) {
            throw new InvalidArgument();
        }
        
        $soapArgs = array(
            'tipoDestinatario' => 'C',
            'identificador'    => $params->getAccessData()->getCnpjEmpresa(),
            'idServico'        => $params->getServicoDePostagem()->getIdServico(),
            'qtdEtiquetas'     => 1,
            'usuario'          => $params->getAccessData()->getUsuario(),
            'senha'            => $params->getAccessData()->getSenha(),
        );

        $result = new Result();
        
        try {
            if (!$params->getAccessData() || !$params->getAccessData()->getUsuario()
                || !$params->getAccessData()->getSenha()
            ) {
                throw new Exception('Para usar este serviço você precisa setar o nome de usuário e senha.');
            }
            
            $etiquetasReservadas = array();
            for ($i = 0; $i < $params->getQtdEtiquetas(); $i++) {
                $r = SoapClientFactory::getSoapClient()->solicitaEtiquetas($soapArgs);
                if (class_exists('\StaLib_Logger')) {
                    \StaLib_Logger::log('Retorno SIGEP solicitar etiquetas: ' . print_r($r, true));
                }
                if ($r && is_object($r) && isset($r->return) && !($r instanceof \SoapFault)) {
                    $r = explode(',', $r->return);
    //				$etiquetasReservadas[] = str_replace(' ', '', $r[0]);
                    $etiqueta = new Etiqueta();
                    $etiqueta->setEtiquetaSemDv($r[0]);
                    $etiquetasReservadas[] = $etiqueta;
                } else {
                    if ($r instanceof \SoapFault) {
                        throw $r;
                    }
                    throw new \Exception('Não foi possível obter as etiquetas solicitadas. Retorno: "' . $r . '"');
                }
            }
            $result->setResult($etiquetasReservadas);
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

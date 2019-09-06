<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\SolicitarPostagemReversaRetorno;
use PhpSigep\Model\AbstractModel;
use PhpSigep\Services\Exception;
use PhpSigep\Services\Result;

/**
 * @author: Renan Zanelato <email:renan.zanelato96@gmail.com>
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
            $params->getColetas_solicitadas()->getRemetente()->setNumeroContrato($params->getAccessData()->getNumeroContrato());
            $params->getColetas_solicitadas()->getRemetente()->setDiretoria($params->getAccessData()->getDiretoria());
            $params->getColetas_solicitadas()->getRemetente()->setCodigoAdministrativo($params->getAccessData()->getCodAdministrativo());

            $soapArgs = [
                'codAdministrativo' => $params->getAccessData()->getCodAdministrativo(),
                'contrato' => $params->getContrato(),
                'codigo_servico' => $params->getCodigo_servico(),
                'cartao' => $params->getAccessData()->getCartaoPostagem(),
                'destinatario' => $params->getDestinatario()->toArray(),
                'coletas_solicitadas' => $params->getColetas_solicitadas()->toArray()
            ];

            $soapArgs = $this->filtraValNull($soapArgs);
//            
//            if ($params->getColetasSolicitadas()->getProduto() == null) {
//                unset($soapArgs['coletas_solicitadas']['produto']);
//            }



            $r = SoapClientFactory::getSoapReversa()->solicitarPostagemReversa($soapArgs);

            if (!$r || !is_object($r) || !isset($r->return) || ($r instanceof \SoapFault)) {
                if ($r instanceof \SoapFault) {
                    throw $r;
                }
                if ($r->solicitarPostagemReversa) {
                    if (!empty($r->solicitarPostagemReversa->msg_erro)) {
                        throw new \Exception($r->solicitarPostagemReversa->msg_erro, (int) $r->solicitarPostagemReversa->cod_erro);
                    }
                    if (!empty($r->solicitarPostagemReversa->resultado_solicitacao)) {
                        if (!empty($r->solicitarPostagemReversa->resultado_solicitacao->descricao_erro))
                            throw new \Exception($r->solicitarPostagemReversa->resultado_solicitacao->descricao_erro, (int) $r->solicitarPostagemReversa->resultado_solicitacao->codigo_erro);
                    }
                    $qtdObjeto = count($params->getColetas_solicitadas()->getObj_col());
                    $ret = (!isset($r->solicitarPostagemReversa->resultado_solicitacao->id_obj))  ? array_shift($r->solicitarPostagemReversa->resultado_solicitacao) : $r->solicitarPostagemReversa->resultado_solicitacao;


                    $SolicitarPostagemReversaRetorno = new SolicitarPostagemReversaRetorno();
                    $SolicitarPostagemReversaRetorno->setId_obj($ret->id_obj)
                        ->setNumero_coleta($ret->numero_coleta)
                        ->setNumero_etiqueta($ret->numero_etiqueta)
                        ->setPrazo($ret->prazo)
                        ->setQtd_objeto($qtdObjeto)
                        ->setStatus_objeto($ret->status_objeto);
                    $result->setResult($SolicitarPostagemReversaRetorno);
                    return $result;
                }
                throw new \Exception('Falha na leitura do XML (' . var_export($r) . ')', 400);
            }
        } catch (\Exception $e) {
            if ($e instanceof \SoapFault) {
                $result->setIsSoapFault(true);
            }
//                $result->setErrorCode($e->getCode());
//                $result->setErrorMsg("Resposta do Correios: " . SoapClientFactory::convertEncoding($e->getMessage()));
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

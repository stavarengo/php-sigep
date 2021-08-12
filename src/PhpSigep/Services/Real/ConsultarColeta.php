<?php

namespace PhpSigep\Services\Real;

use PhpSigep\InvalidArgument;
use PhpSigep\Model\AbstractModel;
use PhpSigep\Services\Exception;
use PhpSigep\Services\Result;

/**
 * @author: Ives Samuel <email:ivessamuel64@gmail.com>
 */
class ConsultarColeta implements RealServiceInterface
{

  /**
   * @param \PhpSigep\Model\AbstractModel|\PhpSigep\Model\ConsultarColeta $params
   *
   * @throws \PhpSigep\Services\Exception
   * @throws \PhpSigep\InvalidArgument
   * @return Result
   */
  public function execute(AbstractModel $params)
  {
    if (!$params instanceof \PhpSigep\Model\ConsultarColeta) {
      throw new InvalidArgument();
    }

    $result = new Result();
    try {
      if (
        !$params->getAccessData() || !$params->getAccessData()->getUsuario() || !$params->getAccessData()->getSenha()
      ) {
        throw new Exception('Para usar este serviço você precisa setar o nome de usuário e senha.');
      }
      $soapArgs = [
        'codAdministrativo' => $params->getAccessData()->getCodAdministrativo(),
        'tipoBusca' => $params->getTipoBusca(),
        'tipoSolicitacao' => $params->getTipoSolicitacao(),
        'numeroPedido' => $params->getNumeroPedido()
      ];
      $r = SoapClientFactory::getSoapReversa()->acompanharPedido($soapArgs);
      if (!$r || !is_object($r) || !isset($r->return) || ($r instanceof \SoapFault)) {
        if ($r instanceof \SoapFault) {
          throw $r;
        }

        if ($r->acompanharPedido) {

          if (!empty($r->acompanharPedido->msg_erro)) {
            throw new \Exception($r->acompanharPedido->msg_erro, (int) $r->acompanharPedido->cod_erro);
          }
          $resultado = new \PhpSigep\Model\ConsultarColetaRetorno();
          $resultado->setTipo($r->acompanharPedido->tipo_solicitacao);
          $resultado->setNumeroPedido($r->acompanharPedido->coleta->numero_pedido);
          $resultado->setControleCliente($r->acompanharPedido->coleta->controle_cliente);
          $resultado->setObjeto($r->acompanharPedido->coleta->objeto);
          $resultado->setHistorico($r->acompanharPedido->coleta->historico);

          $result->setResult($resultado);
          return $result;
        }
        throw new \Exception('Falha na leitura do XML (' . var_export($r) . ')', 400);
      }
    } catch (\Exception $e) {
      if ($e instanceof \SoapFault) {
        $result->setIsSoapFault(true);
      }
      $result->setErrorCode($e->getCode());
      $result->setErrorMsg($e->getMessage());
    }
    return $result;
  }
}

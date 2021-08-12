<?php

namespace PhpSigep\Model;

/**
 * @author: Ives Samuel
 */
class ConsultarColetaRetorno extends AbstractModel
{
  protected $tipo;
  protected $historico;
  protected $objeto;
  protected $numeroPedido;
  protected $controleCliente;
  public function setTipo($tipo)
  {
    $this->tipo = $tipo;
    return $this;
  }
  public function getTipo()
  {
    return $this->tipo;
  }
  public function setHistorico($historico)
  {
    $this->historico = $historico;
    return $this;
  }
  public function getHistorico()
  {
    return $this->historico;
  }
  public function setObjeto($objeto)
  {
    $this->objeto = $objeto;
    return $this;
  }
  public function getObjeto()
  {
    return $this->objeto;
  }
  public function setNumeroPedido($numeroPedido)
  {
    $this->numeroPedido = $numeroPedido;
    return $this;
  }
  public function getNumeroPedido()
  {
    return $this->numeroPedido;
  }
  public function setControleCliente($controleCliente)
  {
    $this->controleCliente = $controleCliente;
    return $this;
  }
  public function getControleCliente()
  {
    return $this->controleCliente;
  }
}

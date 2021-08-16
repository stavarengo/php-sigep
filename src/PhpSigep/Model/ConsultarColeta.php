<?php

namespace PhpSigep\Model;

/**
 * @author: Ives Samuel
 */
class ConsultarColeta extends AbstractModel
{
  /**
   * @var AccessData
   */
  protected $accessData;

  const TIPO_BUSCA_TODOS = 'H';
  const TIPO_BUSCA_SOMENTE_ULTIMOS = 'U';
  const TIPO_SOLICITACAO_DOMICILICAR = 'L';
  const TIPO_SOLICITACAO_AUTORIZACAO = 'A';
  const TIPO_SOLICITACAO_COLETA = 'C';

  protected $tipoSolicitacao = self::TIPO_SOLICITACAO_COLETA;
  protected $tipoBusca = self::TIPO_BUSCA_TODOS;
  protected $numeroPedido = '';

  /**
   * @param \PhpSigep\Model\AccessData $accessData
   */
  public function setAccessData($accessData)
  {
    $this->accessData = $accessData;
  }

  /**
   * @return \PhpSigep\Model\AccessData
   */
  public function getAccessData()
  {
    return $this->accessData;
  }

  /**
   * @param String $tipoSolicitacao
   * @return $this;
   */
  public function setTipoSolicitacao($tipoSolicitacao)
  {
    $this->tipoSolicitacao = $tipoSolicitacao;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getTipoSolicitacao()
  {
    return $this->tipoSolicitacao;
  }


  /**
   * @param String $tipoBusca
   * @return $this;
   */
  public function setTipoBusca($tipoBusca)
  {
    $this->tipoBusca = $tipoBusca;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getTipoBusca()
  {
    return $this->tipoBusca;
  }

  /**
   * @param String $numeroPedido
   * @return $this;
   */
  public function setNumeroPedido($numeroPedido)
  {
    $this->numeroPedido = $numeroPedido;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getNumeroPedido()
  {
    return $this->numeroPedido;
  }
}

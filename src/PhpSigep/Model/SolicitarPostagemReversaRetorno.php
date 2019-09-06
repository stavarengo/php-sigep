<?php
namespace PhpSigep\Model;

/**
 * @author: Renan Zanelato <email:renan.zanelato96@gmail.com>
 */
class SolicitarPostagemReversaRetorno extends AbstractModel
{

    protected $tipo;
    protected $numero_coleta;
    protected $numero_etiqueta;
    protected $id_obj;
    protected $status_objeto;
    protected $prazo;
    protected $data_solicitacao;
    protected $hora_solicitacao;
    protected $codigo_erro;
    protected $qtd_objeto;
    protected $descricao_erro;

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function setNumero_coleta($numero_coleta)
    {
        $this->numero_coleta = $numero_coleta;
        return $this;
    }

    public function setId_obj($id_obj)
    {
        $this->id_obj = $id_obj;
        return $this;
    }

    public function setStatus_objeto($status_objeto)
    {
        $this->status_objeto = $status_objeto;
        return $this;
    }

    public function setPrazo($prazo)
    {
        $this->prazo = $prazo;
        return $this;
    }

    public function setData_solicitacao($data_solicitacao)
    {
        $this->data_solicitacao = $data_solicitacao;
        return $this;
    }

    public function getNumero_etiqueta()
    {
        return $this->numero_etiqueta;
    }

    public function getHora_solicitacao()
    {
        return $this->hora_solicitacao;
    }

    public function getCodigo_erro()
    {
        return $this->codigo_erro;
    }

    public function getDescricao_erro()
    {
        return $this->descricao_erro;
    }

    public function setNumero_etiqueta($numero_etiqueta)
    {
        $this->numero_etiqueta = $numero_etiqueta;
        return $this;
    }

    public function setHora_solicitacao($hora_solicitacao)
    {
        $this->hora_solicitacao = $hora_solicitacao;
        return $this;
    }

    public function setCodigo_erro($codigo_erro)
    {
        $this->codigo_erro = $codigo_erro;
        return $this;
    }

    public function setDescricao_erro($descricao_erro)
    {
        $this->descricao_erro = $descricao_erro;
        return $this;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getNumero_coleta()
    {
        return $this->numero_coleta;
    }

    public function getId_obj()
    {
        return $this->id_obj;
    }

    public function getStatus_objeto()
    {
        return $this->status_objeto;
    }

    public function getPrazo()
    {
        return $this->prazo;
    }

    public function getData_solicitacao()
    {
        return $this->data_solicitacao;
    }

    public function getQtd_objeto()
    {
        return $this->qtd_objeto;
    }

    public function setQtd_objeto($qtd_objeto)
    {
        $this->qtd_objeto = $qtd_objeto;
        return $this;
    }
}

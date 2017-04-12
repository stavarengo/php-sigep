<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 * @author: davidalves1
 */
class RastrearObjetoEvento extends AbstractModel
{
    /**
     * @var string
     */
    protected $tipo;
    /**
     * @var int
     */
    protected $status;
    /**
     * @var string
     */
    protected $dataHora;
    /**
     * @var string
     */
    protected $descricao;
    /**
     * @var string
     */
    protected $recebedor;
    /**
     * @var string
     */
    protected $detalhe;
    /**
     * @var string
     */
    protected $local;
    /**
     * @var string
     */
    protected $codigo;
    /**
     * @var string
     */
    protected $cidade;
    /**
     * @var string
     */
    protected $uf;
    /**
     * @var string
     */
    protected $error;

    /**
     * @param string $tipo
     * @return $this;
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param int $status
     * @return $this;
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $data_hora
     * @return $this;
     */
    public function setDataHora(\DateTime $data_hora)
    {
        $this->dataHora = $data_hora->format('Y-m-d H:i');

        return $this;
    }

    /**
     * @return string
     */
    public function getDataHora()
    {
        return $this->dataHora;
    }

    /**
     * @param string $descricao
     * @return $this;
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $recebedor
     * @return $this;
     */
    public function setRecebedor($recebedor)
    {
        $this->recebedor = $recebedor;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecebedor()
    {
        return $this->recebedor;
    }

    /**
     * @param string $documento
     * @return $this;
     */
    public function setDetalhe($detalhe)
    {
        $this->detalhe = $detalhe;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetalhe()
    {
        return $this->detalhe;
    }

    /**
     * @param string $local
     * @return $this;
     */
    public function setLocal($local)
    {
        $this->local = $local;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @param string $codigo
     * @return $this;
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param string $cidade
     * @return $this;
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;

        return $this;
    }

    /**
     * @return string
     */
    public function getCidade()
    {
        return $this->cidade;
    }


    /**
     * @param string $uf
     * @return $this;
     */
    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrors()
    {
        return $this->error;
    }
}

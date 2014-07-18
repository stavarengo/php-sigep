<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
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
     * @var \DateTime
     */
    protected $dataHora;
    /**
     * @var string
     */
    protected $descricao;
    /**
     * @var string
     */
    protected $detalhes;
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
     * @param \DateTime $dataHora
     * @return $this;
     */
    public function setDataHora(\DateTime $dataHora)
    {
        $this->dataHora = $dataHora;

        return $this;
    }

    /**
     * @return \DateTime
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
     * @param string $detalhes
     * @return $this;
     */
    public function setDetalhes($detalhes)
    {
        $this->detalhes = $detalhes;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetalhes()
    {
        return $this->detalhes;
    }
    
}

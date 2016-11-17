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
     * @var \DateTime
     */
    protected $data;
    /**
     * @var \DateTime
     */
    protected $hora;
    /**
     * @var string
     */
    protected $descricao;
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
     * @param \DateTime $data
     * @return $this;
     */
    public function setData(\DateTime $data)
    {
        $this->data = $data->format('Y-m-d');

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \DateTime $hora
     * @return $this;
     */
    public function setHora(\DateTime $hora)
    {
        $this->hora = $hora->format('H:i');

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getHora()
    {
        return $this->hora;
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
}

<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class CalcPrecoPrazo extends AbstractModel
{

    /**
     * @var AccessData
     */
    protected $accessData;

    /**
     * @var ServicoDePostagem[]
     */
    protected $servicosPostagem;

    /**
     * @var string
     */
    protected $cepOrigem;
    /**
     * @var string
     */
    protected $cepDestino;
    /**
     * Peso da encomenda, incluindo sua embalagem. O peso deve ser informado em quilogramas.
     * Se o formato for Envelope ({@link \PhpSigep\Model\Dimensao::TIPO_ENVELOPE}), o valor máximo permitido será 1 kg.
     * @var float
     */
    protected $peso;
    /**
     * @var Dimensao
     */
    protected $dimensao;
    /**
     * @var ServicoAdicional[]
     */
    protected $servicosAdicionais;

    /**
     * Quando true, o sistema altera o tamanho das dimensões se elas forem menor que o mínimo permitido pelo
     * correios.
     * @var bool
     */
    protected $ajustarDimensaoMinima = true;

    /**
     * @param AccessData $accessData
     * @return CalcPrecoPrazo
     */
    public function setAccessData($accessData)
    {
        $this->accessData = $accessData;
        return $this;
    }

    /**
     * @return AccessData
     */
    public function getAccessData()
    {
        return $this->accessData;
    }

    /**
     * @param string $cepDestino
     * @return CalcPrecoPrazo
     */
    public function setCepDestino($cepDestino)
    {
        $this->cepDestino = $cepDestino;
        return $this;
    }

    /**
     * @return string
     */
    public function getCepDestino()
    {
        return $this->cepDestino;
    }

    /**
     * @param string $cepOrigem
     * @return CalcPrecoPrazo
     */
    public function setCepOrigem($cepOrigem)
    {
        $this->cepOrigem = $cepOrigem;
        return $this;
    }

    /**
     * @return string
     */
    public function getCepOrigem()
    {
        return $this->cepOrigem;
    }

    /**
     * @param Dimensao $dimensao
     * @return CalcPrecoPrazo
     */
    public function setDimensao($dimensao)
    {
        $this->dimensao = $dimensao;
        return $this;
    }

    /**
     * @return Dimensao
     */
    public function getDimensao()
    {
        return $this->dimensao;
    }

    /**
     * @param float $peso
     *      Em kilogramas, ou seja, 0.400 significa 400 gramas.
     * @return CalcPrecoPrazo
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;
        return $this;
    }

    /**
     * @return float
     *      Em kilogramas, ou seja, 0.400 significa 400 gramas.
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * @param ServicoDePostagem[] $servicosPostagem
     * @return CalcPrecoPrazo
     */
    public function setServicosPostagem($servicosPostagem)
    {
        $this->servicosPostagem = $servicosPostagem;
        return $this;
    }

    /**
     * @return ServicoDePostagem[]
     */
    public function getServicosPostagem()
    {
        return $this->servicosPostagem;
    }

    /**
     * @param ServicoAdicional[] $servicosAdicionais
     * @return CalcPrecoPrazo
     */
    public function setServicosAdicionais($servicosAdicionais)
    {
        $this->servicosAdicionais = $servicosAdicionais;
        return $this;
    }

    /**
     * @return ServicoAdicional[]
     */
    public function getServicosAdicionais()
    {
        return $this->servicosAdicionais;
    }

    /**
     * @param boolean $ajustarDimensaoMinima
     *      Quando true, o sistema altera o tamanho das dimensões se elas forem menor que o mínimo permitido pelo
     *      correios.
     * @return CalcPrecoPrazo
     */
    public function setAjustarDimensaoMinima($ajustarDimensaoMinima)
    {
        $this->ajustarDimensaoMinima = $ajustarDimensaoMinima;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getAjustarDimensaoMinima()
    {
        return $this->ajustarDimensaoMinima;
    }

}

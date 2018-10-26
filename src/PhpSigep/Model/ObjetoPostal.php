<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class ObjetoPostal extends AbstractModel
{

    /**
     * A etiqueta gerada para esta encomenda.
     * Número da etiqueta completo, com o DV.
     * @var Etiqueta
     */
    protected $etiqueta;
    /**
     * O serviço de postagem que será usado para enviar esta encomenda.
     * @var ServicoDePostagem
     */
    protected $servicoDePostagem;
    /**
     * Cubagem do Objeto. Não obrigatório.
     * @var float
     */
    protected $cubagem;
    /**
     * Pesto em gramas.
     * @var float
     */
    protected $peso;
    /**
     * Dados da pessoa que vai receber esta encomenda.
     * @var Destinatario
     */
    protected $destinatario;
    /**
     * Dados do endereço de destino da encomenda.
     * Pode ser nacional ou internacional.
     * @var Destino
     */
    protected $destino;
    /**
     * Lista de serviços adicionais usados nesta encomenda.
     * @var ServicoAdicional[]
     */
    protected $servicosAdicionais;
    /**
     * @var Dimensao
     */
    protected $dimensao;
    /**
     * @var string
     */
    protected $observacao;

    /**
     * @param float $cubagem
     */
    public function setCubagem($cubagem)
    {
        $this->cubagem = $cubagem;
    }

    /**
     * @return float
     */
    public function getCubagem()
    {
        return $this->cubagem;
    }

    /**
     * @param \PhpSigep\Model\Destinatario $destinatario
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;
    }

    /**
     * @return \PhpSigep\Model\Destinatario
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * @param \PhpSigep\Model\Destino $destino
     */
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    /**
     * @return \PhpSigep\Model\Destino
     */
    public function getDestino()
    {
        return $this->destino;
    }

    /**
     * @param \PhpSigep\Model\Dimensao $dimensao
     */
    public function setDimensao($dimensao)
    {
        $this->dimensao = $dimensao;
    }

    /**
     * @return \PhpSigep\Model\Dimensao
     */
    public function getDimensao()
    {
        return $this->dimensao;
    }

    /**
     * @param \PhpSigep\Model\Etiqueta $etiqueta
     */
    public function setEtiqueta($etiqueta)
    {
        $this->etiqueta = $etiqueta;
    }

    /**
     * @return \PhpSigep\Model\Etiqueta
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    /**
     * @param float $peso
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;
    }

    /**
     * Peso em kilogramas.
     * Ex: use 0.3 para 300 gramas
     * @return float
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * @param \PhpSigep\Model\ServicoDePostagem $servicoDePostagem
     */
    public function setServicoDePostagem($servicoDePostagem)
    {
        $this->servicoDePostagem = $servicoDePostagem;
    }

    /**
     * @return \PhpSigep\Model\ServicoDePostagem
     */
    public function getServicoDePostagem()
    {
        return $this->servicoDePostagem;
    }

    /**
     * @param \PhpSigep\Model\ServicoAdicional[] $servicosAdicionais
     */
    public function setServicosAdicionais($servicosAdicionais)
    {
        $this->servicosAdicionais = $servicosAdicionais;
    }

    /**
     * @return \PhpSigep\Model\ServicoAdicional[]
     */
    public function getServicosAdicionais()
    {
        return (array)$this->servicosAdicionais;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }
}
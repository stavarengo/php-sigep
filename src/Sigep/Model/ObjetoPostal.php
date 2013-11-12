<?php
namespace Sigep\Model;

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
	 * @param \Sigep\Model\Destinatario $destinatario
	 */
	public function setDestinatario($destinatario)
	{
		$this->destinatario = $destinatario;
	}

	/**
	 * @return \Sigep\Model\Destinatario
	 */
	public function getDestinatario()
	{
		return $this->destinatario;
	}

	/**
	 * @param \Sigep\Model\Destino $destino
	 */
	public function setDestino($destino)
	{
		$this->destino = $destino;
	}

	/**
	 * @return \Sigep\Model\Destino
	 */
	public function getDestino()
	{
		return $this->destino;
	}

	/**
	 * @param \Sigep\Model\Dimensao $dimensao
	 */
	public function setDimensao($dimensao)
	{
		$this->dimensao = $dimensao;
	}

	/**
	 * @return \Sigep\Model\Dimensao
	 */
	public function getDimensao()
	{
		return $this->dimensao;
	}

	/**
	 * @param \Sigep\Model\Etiqueta $etiqueta
	 */
	public function setEtiqueta($etiqueta)
	{
		$this->etiqueta = $etiqueta;
	}

	/**
	 * @return \Sigep\Model\Etiqueta
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
	 * @param \Sigep\Model\ServicoDePostagem $servicoDePostagem
	 */
	public function setServicoDePostagem($servicoDePostagem)
	{
		$this->servicoDePostagem = $servicoDePostagem;
	}

	/**
	 * @return \Sigep\Model\ServicoDePostagem
	 */
	public function getServicoDePostagem()
	{
		return $this->servicoDePostagem;
	}

	/**
	 * @param \Sigep\Model\ServicoAdicional[] $servicosAdicionais
	 */
	public function setServicosAdicionais($servicosAdicionais)
	{
		$this->servicosAdicionais = $servicosAdicionais;
	}

	/**
	 * @return \Sigep\Model\ServicoAdicional[]
	 */
	public function getServicosAdicionais()
	{
		return (array)$this->servicosAdicionais;
	}


}
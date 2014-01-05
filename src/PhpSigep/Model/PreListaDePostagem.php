<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class PreListaDePostagem extends AbstractModel
{

	/**
	 * Identifica o registro da PLP no SIGEP Master.
	 * @var int
	 */
	protected $idPlpCliente;
	/**
	 * @var AccessData
	 */
	protected $accessData;
	/**
	 * Dados da pessoa que está remetendo esta encomenda.
	 * @var Remetente
	 */
	protected $remetente;
	/**
	 * Os objetos que estão sendo postados.
	 * @var ObjetoPostal[]
	 */
	protected $encomendas;

	/**
	 * @param \PhpSigep\Model\AccessData $accessData
	 */
	public function setIdPlpCliente($idPlpCliente)
	{
		$this->idPlpCliente = $idPlpCliente;
	}

	/**
	 * @return \PhpSigep\Model\AccessData
	 */
	public function getIdPlpCliente()
	{
		return $this->idPlpCliente;
	}

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
	 * @param \PhpSigep\Model\ObjetoPostal[] $encomendas
	 */
	public function setEncomendas($encomendas)
	{
		$this->encomendas = $encomendas;
	}

	/**
	 * @return \PhpSigep\Model\ObjetoPostal[]
	 */
	public function getEncomendas()
	{
		return $this->encomendas;
	}

	/**
	 * @param \PhpSigep\Model\Remetente $remetente
	 */
	public function setRemetente($remetente)
	{
		$this->remetente = $remetente;
	}

	/**
	 * @return \PhpSigep\Model\Remetente
	 */
	public function getRemetente()
	{
		return $this->remetente;
	}

}

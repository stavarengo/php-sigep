<?php
namespace Sigep\Model;

/**
 * @author: Stavarengo
 */
class PreListaDePostagem extends AbstractModel
{

//	/**
//	 * Identifica o registro da PLP no SIGEP Master. 
//	 * @var int
//	 */
//	protected $id_plp;
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
	 * @param \Sigep\Model\AccessData $accessData
	 */
	public function setAccessData($accessData)
	{
		$this->accessData = $accessData;
	}

	/**
	 * @return \Sigep\Model\AccessData
	 */
	public function getAccessData()
	{
		return $this->accessData;
	}

	/**
	 * @param \Sigep\Model\ObjetoPostal[] $encomendas
	 */
	public function setEncomendas($encomendas)
	{
		$this->encomendas = $encomendas;
	}

	/**
	 * @return \Sigep\Model\ObjetoPostal[]
	 */
	public function getEncomendas()
	{
		return $this->encomendas;
	}

	/**
	 * @param \Sigep\Model\Remetente $remetente
	 */
	public function setRemetente($remetente)
	{
		$this->remetente = $remetente;
	}

	/**
	 * @return \Sigep\Model\Remetente
	 */
	public function getRemetente()
	{
		return $this->remetente;
	}

}
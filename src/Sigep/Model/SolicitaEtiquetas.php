<?php
namespace Sigep\Model;

/**
 * @author: Stavarengo
 */
class SolicitaEtiquetas extends AbstractModel
{

	/**
	 * @var int
	 */
	protected $servicoDePostagem;
	/**
	 * @var int
	 */
	protected $qtdEtiquetas;
	/**
	 * @var AccessData
	 */
	protected $accessData;

	/**
	 * @return \Sigep\Model\AccessData
	 */
	public function getAccessData()
	{
		return $this->accessData;
	}

	/**
	 * @param AccessData $accessData
	 */
	public function setAccessData(AccessData $accessData)
	{
		$this->accessData = $accessData;
	}

	/**
	 * @return int
	 */
	public function getQtdEtiquetas()
	{
		return $this->qtdEtiquetas;
	}

	/**
	 * @param int $qtdEtiquetas
	 */
	public function setQtdEtiquetas($qtdEtiquetas)
	{
		$this->qtdEtiquetas = $qtdEtiquetas;
	}

	/**
	 * @return ServicoDePostagem
	 */
	public function getServicoDePostagem()
	{
		return $this->servicoDePostagem;
	}

	/**
	 * @param int $servicoDePostagem
	 */
	public function setServicoDePostagem($servicoDePostagem)
	{
		$this->servicoDePostagem = $servicoDePostagem;
	}

}
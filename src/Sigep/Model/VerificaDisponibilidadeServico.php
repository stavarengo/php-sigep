<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class VerificaDisponibilidadeServico extends AbstractModel
{

	/**
	 * @var string
	 */
	protected $numeroServico;
	/**
	 * @var string
	 */
	protected $cepOrigem;
	/**
	 * @var string
	 */
	protected $cepDestino;
	/**
	 * @var AccessData
	 */
	protected $accessData;

	/**
	 * @return \PhpSigep\Model\AccessData
	 */
	public function getAccessData()
	{
		return $this->accessData;
	}

	/**
	 * @param \PhpSigep\Model\AccessData $accessData
	 */
	public function setAccessData(AccessData $accessData)
	{
		$this->accessData = $accessData;
	}

	/**
	 * @return string
	 */
	public function getCepDestino()
	{
		return $this->cepDestino;
	}

	/**
	 * @param string $cepDestino
	 */
	public function setCepDestino($cepDestino)
	{
		$this->cepDestino = preg_replace('/[^\d]/', '', $cepDestino);
	}

	/**
	 * @return string
	 */
	public function getCepOrigem()
	{
		return $this->cepOrigem;
	}

	/**
	 * @param string $cepOrigem
	 */
	public function setCepOrigem($cepOrigem)
	{
		$this->cepOrigem = preg_replace('/[^\d]/', '', $cepOrigem);
	}

	/**
	 * @return string
	 */
	public function getNumeroServico()
	{
		return $this->numeroServico;
	}

	/**
	 * @param string $numeroServico
	 */
	public function setNumeroServico($numeroServico)
	{
		$this->numeroServico = $numeroServico;
	}


}
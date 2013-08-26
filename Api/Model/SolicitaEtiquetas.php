<?php
namespace Sigep\Model;

/**
 * @author: Stavarengo
 */
class SolicitaEtiquetas extends AbstractModel
{

	const SERVICO_ESEDEX           = 104672;
	const SERVICO_PAC              = 109819;
	const SERVICO_SEDEX_40436      = 109810;
	const SERVICO_SEDEX_40096      = 104625;
	const SERVICO_SEDEX_40444      = 109811;
	const SERVICO_SEDEX10_ENVELOPE = 104707;
	const SERVICO_CARTA            = 109480;
	const SERVICO_SEDEX_AGRUPADO   = 119461;
	const SERVICO_CARTA_REGISTRADA = 116985;
	/**
	 * @var int
	 */
	protected $idServico;
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
	 * @return int
	 */
	public function getIdServico()
	{
		return $this->idServico;
	}

	/**
	 * @param int $idServico
	 */
	public function setIdServico($idServico)
	{
		$this->idServico = $idServico;
	}

}
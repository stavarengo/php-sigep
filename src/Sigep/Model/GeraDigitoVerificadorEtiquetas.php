<?php
namespace Sigep\Model;

/**
 * @author: Stavarengo
 */
class GeraDigitoVerificadorEtiquetas extends AbstractModel
{

	/**
	 * @var AccessData
	 */
	protected $accessData;
	/**
	 * @var Etiqueta[]
	 */
	protected $etiquetas;

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
	 * @param Etiqueta $etiqueta
	 */
	public function addEtiqueta($etiqueta)
	{
		$this->etiquetas[] = $etiqueta;
	}

	/**
	 * @param Etiqueta[] $etiquetas
	 */
	public function setEtiquetas(array $etiquetas)
	{
		$this->etiquetas = $etiquetas;
	}

	/**
	 * @return Etiqueta[]
	 */
	public function getEtiquetas()
	{
		return (array)$this->etiquetas;
	}

}
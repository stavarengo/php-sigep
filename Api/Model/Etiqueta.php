<?php
namespace Sigep\Model;

/**
 * @author: Stavarengo
 */
class Etiqueta extends AbstractModel
{

	/**
	 * @var string
	 */
	protected $etiquetaSemDv;
	/**
	 * @var int
	 */
	protected $dv;

	/**
	 * @return int
	 */
	public function getDv()
	{
		if (!$this->dv) {
			$numero              = substr($this->getEtiquetaSemDv(), 2, 8);
			$fatoresDePonderacao = array(8, 6, 4, 2, 3, 5, 9, 7);
			$soma                = 0;
			for ($i = 0; $i < 8; $i++) {
				$soma += ($numero[$i] * $fatoresDePonderacao[$i]);
			}

			$modulo = $soma % 11;
			if ($modulo == 0) {
				$this->dv = 5;
			} else if ($modulo == 1) {
				$this->dv = 0;
			} else {
				$this->dv = 11 - $modulo;
			}
		}
		return $this->dv;
	}

	/**
	 * @param int $dv
	 */
	public function setDv($dv)
	{
		$this->dv = $dv;
	}

	/**
	 * @return string
	 */
	public function getEtiquetaSemDv()
	{
		return $this->etiquetaSemDv;
	}

	/**
	 * @param string $etiquetaSemDv
	 */
	public function setEtiquetaSemDv($etiquetaSemDv)
	{
		$this->etiquetaSemDv = $etiquetaSemDv;
	}

	/**
	 * @return string
	 */
	public function getEtiquetaComDv()
	{
	}
}
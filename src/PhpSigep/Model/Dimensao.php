<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class Dimensao extends AbstractModel
{

	const TIPO_ENVELOPE      = 1;
	const TIPO_PACOTE_CAIXA  = 2;
	const TIPO_ROLO_CILINDRO = 3;
	/**
	 * Deve ser uma das constantes {@link Dimensao}::TIPO_*.
	 * @var int
	 */
	protected $tipo;
	/**
	 * Em centímetros.
	 * @var float
	 */
	protected $altura;
	/**
	 * Em centímetros.
	 * @var float
	 */
	protected $largura;
	/**
	 * Em centímetros.
	 * @var float
	 */
	protected $comprimento;
	/**
	 * Em centímetros.
	 * @var float
	 */
	protected $diametro;

	/**
	 * @param float $altura
	 */
	public function setAltura($altura)
	{
		$this->altura = (float)$altura;
	}

	/**
	 * @return float
	 */
	public function getAltura()
	{
		return $this->altura;
	}

	/**
	 * @param float $comprimento
	 */
	public function setComprimento($comprimento)
	{
		$this->comprimento = (float)$comprimento;
	}

	/**
	 * @return float
	 */
	public function getComprimento()
	{
		return $this->comprimento;
	}

	/**
	 * @param float $diametro
	 */
	public function setDiametro($diametro)
	{
		$this->diametro = (float)$diametro;
	}

	/**
	 * @return float
	 */
	public function getDiametro()
	{
		return $this->diametro;
	}

	/**
	 * @param float $largura
	 */
	public function setLargura($largura)
	{
		$this->largura = (float)$largura;
	}

	/**
	 * @return float
	 */
	public function getLargura()
	{
		return $this->largura;
	}

	/**
	 * @param int $tipo
	 */
	public function setTipo($tipo)
	{
		$this->tipo = $tipo;
	}

	/**
	 * @return int
	 */
	public function getTipo()
	{
		return $this->tipo;
	}

}

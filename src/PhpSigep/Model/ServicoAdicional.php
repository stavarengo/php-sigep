<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class ServicoAdicional extends AbstractModel
{

	const SERVICE_AVISO_DE_RECEBIMENTO = 1;
	const SERVICE_MAO_PROPRIA          = 2;
	const SERVICE_VALOR_DECLARADO      = 19;
	const SERVICE_REGISTRO             = 25;
	/**
	 * Código do serviço adicional Caractere (002) Obrigatório.
	 * Uma das constantes {@link ServicoAdicional}::SERVICE_*.
	 * @var int
	 */
	protected $codigoServicoAdicional;
	/**
	 * Valor declarado do serviço adicional.
	 * Usado epenas quando {@link ServicoAdicional::$codigoServicoAdicional} for igual a
	 * {@link ServicoAdicional::SERVICE_MAO_PROPRIA}.
	 * @var float
	 */
	protected $valorDeclarado = 0;

	public function is($codigo)
	{
		return $codigo == $this->getCodigoServicoAdicional();
	}

	/**
	 * @param int $codigoServicoAdicional
	 */
	public function setCodigoServicoAdicional($codigoServicoAdicional)
	{
		$this->codigoServicoAdicional = $codigoServicoAdicional;
	}

	/**
	 * @return int
	 */
	public function getCodigoServicoAdicional()
	{
		return $this->codigoServicoAdicional;
	}

	/**
	 * @param float $valorDeclarado
	 */
	public function setValorDeclarado($valorDeclarado)
	{
		$this->valorDeclarado = $valorDeclarado;
	}

	/**
	 * @return float
	 */
	public function getValorDeclarado()
	{
		return $this->valorDeclarado;
	}


}

<?php
namespace PhpSigep\Services;

/**
 * @author: Stavarengo
 */
class CalcPrecoPrazo
{

	/**
	 * @param \PhpSigep\Model\CalcPrecoPrazo $params
	 *
	 * @return \Etiqueta[]
	 */
	public function execute(\PhpSigep\Model\CalcPrecoPrazo $params)
	{
		$soap = new SoapClient();
		return $soap->calcPrecoPrazo($params);
	}
}

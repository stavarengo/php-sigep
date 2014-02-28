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
		$soap = SoapClientFactory::create();
		return $soap->calcPrecoPrazo($params);
	}
}

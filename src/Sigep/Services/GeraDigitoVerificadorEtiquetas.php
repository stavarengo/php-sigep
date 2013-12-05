<?php
namespace PhpSigep\Services;

use Sigep\Model\Etiqueta;

/**
 * @author: Stavarengo
 */
class GeraDigitoVerificadorEtiquetas
{

	/**
	 * @param \PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params
	 *
	 * @return Etiqueta[]
	 */
	public function execute(\PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params)
	{
		$soap = new SoapClient();
		return $soap->geraDigitoVerificadorEtiquetas($params);
	}
}
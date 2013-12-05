<?php
namespace Sigep\Services;

use Sigep\Model\Etiqueta;

/**
 * @author: Stavarengo
 */
class GeraDigitoVerificadorEtiquetas
{

	/**
	 * @param \Sigep\Model\GeraDigitoVerificadorEtiquetas $params
	 *
	 * @return Etiqueta[]
	 */
	public function execute(\Sigep\Model\GeraDigitoVerificadorEtiquetas $params)
	{
		$soap = new SoapClient();
		return $soap->geraDigitoVerificadorEtiquetas($params);
	}
}
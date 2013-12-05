<?php
namespace PhpSigep\Services;

/**
 * @author: Stavarengo
 */
class SolicitaEtiquetas
{

	/**
	 * @param \PhpSigep\Model\SolicitaEtiquetas $params
	 *
	 * @return \Etiqueta[]
	 */
	public function execute(\PhpSigep\Model\SolicitaEtiquetas $params)
	{
		$soap = new SoapClient();
		return $soap->solicitaEtiquetas($params);
	}
}

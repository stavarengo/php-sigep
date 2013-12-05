<?php
namespace PhpSigep\Services;

/**
 * @author: Stavarengo
 */
class SolicitaEtiquetas
{

	/**
	 * @param \Sigep\Model\SolicitaEtiquetas $params
	 *
	 * @return \Etiqueta[]
	 */
	public function execute(\Sigep\Model\SolicitaEtiquetas $params)
	{
		$soap = new SoapClient();
		return $soap->solicitaEtiquetas($params);
	}
}

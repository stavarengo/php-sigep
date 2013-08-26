<?php
namespace Sigep\Services;

/**
 * @author: Stavarengo
 */
class SolicitaEtiquetas
{

	public function execute(\Sigep\Model\SolicitaEtiquetas $params)
	{
		$soap     = new SoapClient();
		return $soap->solicitaEtiquetas($params);
	}
}
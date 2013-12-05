<?php
namespace Sigep\Services;

/**
 * @author: Stavarengo
 */
class VerificaDisponibilidadeServico
{

	public function execute(\Sigep\Model\VerificaDisponibilidadeServico $params)
	{
		$soap = new SoapClient();
		return $soap->verificaDisponibilidadeServico($params);
	}
}

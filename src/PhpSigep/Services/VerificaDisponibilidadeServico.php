<?php
namespace PhpSigep\Services;

/**
 * @author: Stavarengo
 */
class VerificaDisponibilidadeServico
{

	public function execute(\PhpSigep\Model\VerificaDisponibilidadeServico $params)
	{
		$soap = new SoapClient();
		return $soap->verificaDisponibilidadeServico($params);
	}
}

<?php
namespace PhpSigep\Services;

/**
 * @author: Stavarengo
 */
class VerificaDisponibilidadeServico
{

	public function execute(\PhpSigep\Model\VerificaDisponibilidadeServico $params)
	{
        $soap = SoapClientFactory::create();
		return $soap->verificaDisponibilidadeServico($params);
	}
}

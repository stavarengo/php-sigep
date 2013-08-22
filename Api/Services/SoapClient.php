<?php
namespace Sigep\Services;

use Sigep\Bootstrap;

/**
 * @author: Stavarengo
 */
class SoapClient
{

	protected $soapClient;

	public function __construct()
	{
		$wsdl             = Bootstrap::getConfig()->getWsdlAtendeCliente();
		$this->soapClient = new \SoapClient($wsdl, array(
			"trace"      => 1,
			"exceptions" => 0,
		));
	}

	/**
	 * @param \Sigep\Model\VerificaDisponibilidadeServico $params
	 *
	 * @return bool
	 */
	public function verificaDisponibilidadeServico(\Sigep\Model\VerificaDisponibilidadeServico $params)
	{		
		$soapArgs = array(
			'codAdministrativo' => $params->getAccessData()->getCodAdministrativo(),
			'numeroServico'     => $params->getNumeroServico(),
			'cepOrigem'         => $params->getCepOrigem(),
			'cepDestino'        => $params->getCepDestino(),
			'usuario'           => $params->getAccessData()->getUsuario(),
			'senha'             => $params->getAccessData()->getSenha(),
		);
		$r = $this->soapClient->verificaDisponibilidadeServico($soapArgs);	
		return ($r && $r->return);
	}
}
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
		$r        = $this->soapClient->verificaDisponibilidadeServico($soapArgs);
		return ($r && $r->return);
	}

	/**
	 * @param \Sigep\Model\SolicitaEtiquetas $params
	 *
	 * @return string[]
	 */
	public function solicitaEtiquetas(\Sigep\Model\SolicitaEtiquetas $params)
	{
		$soapArgs = array(
			'tipoDestinatario' => 'C',
			'identificador'    => $params->getAccessData()->getCnpjEmpresa(),
			'idServico'        => $params->getIdServico(),
			'qtdEtiquetas'     => 1,
			'usuario'          => $params->getAccessData()->getUsuario(),
			'senha'            => $params->getAccessData()->getSenha(),
		);

		$etiquetasReservadas = array();
		for ($i = 0;$i<$params->getQtdEtiquetas(); $i++) {
			$r = $this->soapClient->solicitaEtiquetas($soapArgs);
			if ($r && is_object($r) && isset($r->return) && !($r instanceof \SoapFault)) {
				$r = explode(',', $r->return);
				$etiquetasReservadas[] = str_replace(' ', '', $r[0]);
			} else {
				if ($r instanceof \SoapFault) {
					throw $r;
				}
				throw new Exception('Não foi possível obter as etiquetas solicitadas.');
			}
		}
		
		return $etiquetasReservadas;
	}
}
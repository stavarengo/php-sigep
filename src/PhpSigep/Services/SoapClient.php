<?php
namespace PhpSigep\Services;

use PhpSigep\Bootstrap;
use PhpSigep\Model\Etiqueta;

/**
 * @author: Stavarengo
 */
class SoapClient
{

	/**
	 * @var SoapClient
	 */
	private static $instance;
	/**
	 * @var \SoapClient
	 */
	protected $soapClient;

	public function __construct()
	{
		$wsdl             = Bootstrap::getConfig()->getWsdlAtendeCliente();
		$this->soapClient = new \SoapClient($wsdl, array(
			"trace"      => Bootstrap::getConfig()->isDebug(),
			"exceptions" => Bootstrap::getConfig()->isDebug(),
			'encoding'   => 'ISO-8859-1',
		));
	}

	/**
	 * @return SoapClient
	 */
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @param \PhpSigep\Model\VerificaDisponibilidadeServico $params
	 *
	 * @return bool
	 */
	public function verificaDisponibilidadeServico(\PhpSigep\Model\VerificaDisponibilidadeServico $params)
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
	 * @param \PhpSigep\Model\SolicitaEtiquetas $params
	 *
	 * @throws \SoapFault
	 * @throws \Exception
	 * @return Etiqueta[]
	 */
	public function solicitaEtiquetas(\PhpSigep\Model\SolicitaEtiquetas $params)
	{
		$soapArgs = array(
			'tipoDestinatario' => 'C',
			'identificador'    => $params->getAccessData()->getCnpjEmpresa(),
			'idServico'        => $params->getServicoDePostagem()->getIdServico(),
			'qtdEtiquetas'     => 1,
			'usuario'          => $params->getAccessData()->getUsuario(),
			'senha'            => $params->getAccessData()->getSenha(),
		);

		$etiquetasReservadas = array();
		for ($i = 0; $i < $params->getQtdEtiquetas(); $i++) {
			$r = $this->soapClient->solicitaEtiquetas($soapArgs);
			if ($r && is_object($r) && isset($r->return) && !($r instanceof \SoapFault)) {
				$r = explode(',', $r->return);
//				$etiquetasReservadas[] = str_replace(' ', '', $r[0]);
				$etiqueta = new Etiqueta();
				$etiqueta->setEtiquetaSemDv($r[0]);
				$etiquetasReservadas[] = $etiqueta;
			} else {
				if ($r instanceof \SoapFault) {
					throw $r;
				}
				throw new \Exception('Não foi possível obter as etiquetas solicitadas.');
			}
		}

		return $etiquetasReservadas;
	}

	/**
	 * @param \PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params
	 *
	 * @throws \SoapFault
	 * @throws \Exception
	 * @return string[]
	 */
	public function geraDigitoVerificadorEtiquetas(\PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params)
	{
		$soapArgs = array(
			'etiquetas' => array(),
			'usuario'   => $params->getAccessData()->getUsuario(),
			'senha'     => $params->getAccessData()->getSenha(),
		);

		// É necessário garantir que o array estará indexado por order natural começando do zero para setarmos os
		// DV retornados pelo webservice.
		$etiquetas = array_values($params->getEtiquetas());

		/** @var $etiqueta Etiqueta */
		foreach ($etiquetas as $etiqueta) {
			$soapArgs['etiquetas'][] = $etiqueta->getEtiquetaSemDv();
		}

		$soapReturn = $this->soapClient->geraDigitoVerificadorEtiquetas($soapArgs);
		if ($soapReturn && is_object($soapReturn) && $soapReturn->return) {
			if (!is_array($soapReturn->return)) {
				$soapReturn->return = (array)$soapReturn->return;
			}

			foreach ($soapReturn->return as $k => $dv) {
				$etiquetas[$k]->setDv((int)$dv);
			}
		}


		return $etiquetas;
	}

}

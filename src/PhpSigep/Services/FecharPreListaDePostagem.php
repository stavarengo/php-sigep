<?php
namespace PhpSigep\Services;

use PhpSigep\Model\Destinatario;
use PhpSigep\Model\Destino;
use PhpSigep\Model\DestinoInternacional;
use PhpSigep\Model\DestinoNacional;
use PhpSigep\Model\Dimensao;
use PhpSigep\Model\ObjetoPostal;
use PhpSigep\Model\PreListaDePostagem;
use PhpSigep\Model\ServicoAdicional;

/**
 * @author: Stavarengo
 */
class FecharPreListaDePostagem
{

	/**
	 * @param PreListaDePostagem $params
	 *
	 * @return int
	 */
	public function execute(\PhpSigep\Model\PreListaDePostagem $params)
	{
		$args = array(
			'idPlpCliente'   => '',
			'cartaoPostagem' => $params->getAccessData()->getCartaoPostagem(),
			'usuario'        => $params->getAccessData()->getUsuario(),
			'senha'          => $params->getAccessData()->getSenha(),
		);

		$servicos = array();
		foreach($params->getEncomendas() as $encomenda) {
			$codigo = $encomenda->getServicoDePostagem()->getCodigo();
			if (!in_array($codigo, $servicos)) {
				array_push($servicos, $codigo);
			}
		}

		$encomendas = $params->getEncomendas();
		$varios_servicos = count($servicos) > 1;
		// $varios_servicos = true;
		if ($varios_servicos) {
			$method = 'fechaPlpVariosServicos';
			$args['listaEtiquetas'] = array();
			foreach ($encomendas as $objetoPostal) {
				$args['listaEtiquetas'][] = $objetoPostal->getEtiqueta()->getEtiquetaSemDv();;
			}
		} else {
			$method = 'fechaPlp';
			$args['faixaEtiquetas'] = array_shift($encomendas)->getEtiqueta()->getEtiquetaSemDv();
			if (count($encomendas) > 0) {
				$args['faixaEtiquetas'] .= ',' . end($encomendas)->getEtiqueta()->getEtiquetaSemDv();
			}
		}

		$xml = $this->getPlpXml($params)->flush();

		if(isset($_GET['xml'])) {
			header ("Content-Type:text/xml");
			echo $xml;
			exit;
		}

		$domDoc = new \DOMDocument();
		$domDoc->loadXML($xml);
		if (!$domDoc->schemaValidate(\PhpSigep\Bootstrap::getConfig()->getXsdDir() . '/plp_schema.xsd')) {
			echo 'falhou';
			exit;
		}

		$args['xml'] = preg_replace('/\n/', '', $xml);
		$args['xml'] = str_replace('<?xml version="1.0" encoding="ISO-8859-1"?>', '', $args['xml']);

		return SoapClient::getInstance()->{$method}($args);
	}

	private function getPlpXml(PreListaDePostagem $data)
	{
		$writer = new \XMLWriter();
		$writer->openMemory();
		$writer->setIndentString("");
		$writer->setIndent(false);
		$writer->startDocument('1.0', 'ISO-8859-1');

		$writer->startElement('correioslog');
		$writer->writeElement('tipo_arquivo', 'Postagem');
		$writer->writeElement('versao_arquivo', '2.3');

		$this->writePlp($writer, $data);
		$this->writeRemetente($writer, $data);
		$this->writeFormaPagamento($writer, $data);

		foreach ($data->getEncomendas() as $objetoPostal) {
			$this->writeObjetoPostal($writer, $objetoPostal);
		}

		$writer->endElement();

		return $writer;
	}

	private function writePlp(\XMLWriter $writer, PreListaDePostagem $data)
	{
		$writer->startElement('plp');
		$writer->writeElement('id_plp');
		$writer->writeElement('valor_global');
		$writer->writeElement('mcu_unidade_postagem');
		$writer->writeElement('nome_unidade_postagem');
		$writer->writeElement('cartao_postagem', $data->getAccessData()->getCartaoPostagem());
		$writer->endElement();
	}

	private function writeRemetente(\XMLWriter $writer, PreListaDePostagem $data)
	{
		$writer->startElement('remetente');
		$writer->writeElement('numero_contrato', $data->getRemetente()->getNumeroContrato());
		$writer->writeElement('numero_diretoria', $data->getRemetente()->getDiretoria());
		$writer->writeElement('codigo_administrativo', $data->getRemetente()->getCodigoAdministrativo());
		$writer->startElement('nome_remetente');
		$writer->writeCData($this->_($data->getRemetente()->getNome(), 50));
		$writer->endElement();
		$writer->startElement('logradouro_remetente');
		$writer->writeCdata($this->_($data->getRemetente()->getLogradouro(), 40));
		$writer->endElement();
		$writer->writeElement('numero_remetente', $this->_($data->getRemetente()->getNumero(), 6));
		$writer->startElement('complemento_remetente');
		$writer->writeCdata($this->_($data->getRemetente()->getComplemento(), 20));
		$writer->endElement();
		$writer->startElement('bairro_remetente');
		$writer->writeCdata($this->_($data->getRemetente()->getBairro(), 20));
		$writer->endElement();
		$writer->startElement('cep_remetente');
		$writer->writeCdata($this->_(preg_replace('/[^\d]/', '', $data->getRemetente()->getCep()), 8));
		$writer->endElement();
		$writer->startElement('cidade_remetente');
		$writer->writeCdata($this->_($data->getRemetente()->getCidade(), 30));
		$writer->endElement();
		$writer->writeElement('uf_remetente', $this->_($data->getRemetente()->getUf(), 2, false));
		$writer->startElement('telefone_remetente');
		$writer->writeCdata($this->_(preg_replace('/[^\d]/', '', $data->getRemetente()->getTelefone()), 12));
		$writer->endElement();
		$writer->startElement('fax_remetente');
		$writer->writeCdata($this->_(preg_replace('/[^\d]/', '', $data->getRemetente()->getFax()), 12));
		$writer->endElement();
		$writer->startElement('email_remetente');
		$writer->writeCdata($this->_($data->getRemetente()->getEmail(), 50));
		$writer->endElement();
		$writer->endElement();
	}

	private function writeFormaPagamento(\XMLWriter $writer, PreListaDePostagem $data)
	{
		$writer->writeElement('forma_pagamento');
	}

	private function writeObjetoPostal(\XMLWriter $writer, ObjetoPostal $objetoPostal)
	{
		$writer->startElement('objeto_postal');
		$writer->writeElement('numero_etiqueta', $objetoPostal->getEtiqueta()->getEtiquetaComDv());
		$writer->writeElement('codigo_objeto_cliente');
		$writer->writeElement('codigo_servico_postagem', $objetoPostal->getServicoDePostagem()->getCodigo());
		$writer->writeElement('cubagem', number_format($objetoPostal->getCubagem(), 4, ',', ''));
		$writer->writeElement('peso', (float)$objetoPostal->getPeso() * 1000);
		$writer->writeElement('rt1');
		$writer->writeElement('rt2');
		$this->writeDestinatario($writer, $objetoPostal->getDestinatario());
		$this->writeDestino($writer, $objetoPostal->getDestino());
		$this->writeServicoAdicional($writer, (array)$objetoPostal->getServicosAdicionais());
		$this->writeDimensaoObjeto($writer, $objetoPostal->getDimensao());
		$writer->writeElement('data_postagem_sara');
		$writer->writeElement('status_processamento', 0);
		$writer->writeElement('numero_comprovante_postagem');
		$writer->writeElement('valor_cobrado');
		$writer->endElement();
	}

	private function _($str, $maxLength, $cdata = true, $trim = true)
	{
		if ($str === null) {
			return $str;
		}
		if ($trim) {
			$str = trim($str);
		}
		if ($maxLength) {
			$str = substr($str, 0, $maxLength);
		}
		if ($cdata) {
			//$str = $this->getCData($str);
		}
		return $str;
	}

	private function getCData($str)
	{
		return "<![CDATA[$str]]>";
	}

	private function writeDestinatario(\XMLWriter $writer, Destinatario $destinatario)
	{
		$writer->startElement('destinatario');
		$writer->startElement('nome_destinatario');
		$writer->writeCdata($this->_($destinatario->getNome(), 50));
		$writer->endElement();
		$writer->startElement('telefone_destinatario');
		$writer->writeCdata($this->_(preg_replace('/[^\d]/', '', $destinatario->getTelefone()), 12));
		$writer->endElement();
		$writer->startElement('celular_destinatario');
		$writer->writeCdata($this->_(preg_replace('/[^\d]/', '', $destinatario->getCelular()), 12));
		$writer->endElement();
		$writer->startElement('email_destinatario');
		$writer->writeCdata($this->_($destinatario->getEmail(), 50));
		$writer->endElement();
		$writer->startElement('logradouro_destinatario');
		$writer->writeCdata($this->_($destinatario->getLogradouro(), 50));
		$writer->endElement();
		$writer->startElement('complemento_destinatario');
		$writer->writeCdata($this->_($destinatario->getComplemento(), 30));
		$writer->endElement();
		$writer->writeElement('numero_end_destinatario', $this->_($destinatario->getNumero(), 6));
		$writer->endElement();
	}

	private function writeDestino(\XMLWriter $writer, Destino $destino)
	{
		if ($destino instanceof DestinoNacional) {
			$writer->startElement('nacional');
			$writer->startElement('bairro_destinatario');
			$writer->writeCdata($this->_($destino->getBairro(), 30));
			$writer->endElement();
			$writer->startElement('cidade_destinatario');
			$writer->writeCdata($this->_($destino->getCidade(), 30));
			$writer->endElement();
			$writer->writeElement('uf_destinatario', $this->_($destino->getUf(), 2, false));
			$writer->startElement('cep_destinatario');
			$writer->writeCdata($this->_(preg_replace('/[^\d]/', '', $destino->getCep()), 8));
			$writer->endElement();
			$writer->writeElement('codigo_usuario_postal');
			$writer->writeElement('centro_custo_cliente');
			$writer->writeElement('numero_nota_fiscal', $destino->getNumeroNotaFiscal());
			$writer->writeElement('serie_nota_fiscal', $this->_($destino->getSerieNotaFiscal(), 20));
			$writer->writeElement('valor_nota_fiscal', $destino->getValorNotaFiscal());
			$writer->writeElement('natureza_nota_fiscal', $this->_($destino->getNaturezaNotaFiscal(), 20));
			$writer->startElement('descricao_objeto');
			$writer->writeCdata($this->_($destino->getDescricaoObjeto(), 20));
			$writer->endElement();
			$writer->writeElement('valor_a_cobrar', number_format($destino->getValorACobrar(), 1, ',', ''));
			$writer->endElement();
		} else if ($destino instanceof DestinoInternacional) {
			$writer->startElement('internacional');
			$writer->endElement();
		}
	}

	/**
	 * @param \XMLWriter $writer
	 * @param ServicoAdicional[] $servicosAdicionais
	 */
	private function writeServicoAdicional(\XMLWriter $writer, array $servicosAdicionais)
	{
		$writer->startElement('servico_adicional');

		foreach ($servicosAdicionais as $servicoAdicional) {
			$writer->writeElement('codigo_servico_adicional', str_pad($servicoAdicional->getCodigoServicoAdicional(), 3, '0', STR_PAD_LEFT));
			if ($servicoAdicional->getCodigoServicoAdicional() == ServicoAdicional::SERVICE_VALOR_DECLARADO) {
				$writer->writeElement('valor_declarado', number_format($servicoAdicional->getValorDeclarado(), 2, ',', ''));
			}
		}
		$writer->writeElement('valor_declarado');

		$writer->endElement();
	}

	private function writeDimensaoObjeto(\XMLWriter $writer, Dimensao $dimensao)
	{
		$writer->startElement('dimensao_objeto');
		$writer->writeElement('tipo_objeto', str_pad($dimensao->getTipo(), 3, '0', STR_PAD_LEFT));
		$writer->writeElement('dimensao_altura', $dimensao->getAltura());
		$writer->writeElement('dimensao_largura', $dimensao->getLargura());
		$writer->writeElement('dimensao_comprimento', $dimensao->getComprimento());
//		if (!$dimensao->getDiametro()) {
//			$writer->writeElement('dimensao_diametro');
//		} else {
		$writer->writeElement('dimensao_diametro', $dimensao->getDiametro());
//		}
		$writer->endElement();
	}
}


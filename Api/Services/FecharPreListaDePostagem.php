<?php
namespace Sigep\Services;

use Sigep\Model\Destinatario;
use Sigep\Model\Destino;
use Sigep\Model\DestinoInternacional;
use Sigep\Model\DestinoNacional;
use Sigep\Model\Dimensao;
use Sigep\Model\ObjetoPostal;
use Sigep\Model\PreListaDePostagem;
use Sigep\Model\ServicoAdicional;

/**
 * @author: Stavarengo
 */
class FecharPreListaDePostagem
{

	public function execute(\Sigep\Model\PreListaDePostagem $params)
	{
//		$soap     = SoapClient::getInstance();
		return $this->getPlpXml($params)->flush();
	}

	private function getPlpXml(PreListaDePostagem $data)
	{
		$writer = new \XMLWriter();
		$writer->openMemory();
		$writer->setIndent(true);
		$writer->setIndentString("    ");
		$writer->startDocument('1.0', 'UTF-8');

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
		$writer->writeElement('numero_contrato', $data->getAccessData()->getNumeroContrato());
		$writer->writeElement('numero_diretoria', $data->getRemetente()->getDiretoria());
		$writer->writeElement('codigo_administrativo', $data->getAccessData()->getCodAdministrativo());
		$writer->writeElement('nome_remetente', $this->_($data->getRemetente()->getNome(), 50));
		$writer->writeElement('logradouro_remetente', $this->_($data->getRemetente()->getLogradouro(), 40));
		$writer->writeElement('numero_remetente', $this->_($data->getRemetente()->getNumero(), 6));
		$writer->writeElement('complemento_remetente', $this->_($data->getRemetente()->getComplemento(), 20));
		$writer->writeElement('bairro_remetente', $this->_($data->getRemetente()->getBairro(), 20));
		$writer->writeElement('cep_remetente', $this->_(preg_replace('/[^\d]/', '', $data->getRemetente()->getCep()), 8));
		$writer->writeElement('cidade_remetente', $this->_($data->getRemetente()->getCidade(), 30));
		$writer->writeElement('uf_remetente', $this->_($data->getRemetente()->getUf(), 2, false));
		$writer->writeElement('telefone_remetente', $this->_(preg_replace('/[^\d]/', '', $data->getRemetente()->getTelefone()), 12));
		$writer->writeElement('fax_remetente', $this->_(preg_replace('/[^\d]/', '', $data->getRemetente()->getFax()), 12));
		$writer->writeElement('email_remetente', $this->_($data->getRemetente()->getEmail(), 50));
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
		$writer->writeElement('cubagem', (float)$objetoPostal->getCubagem());
		$writer->writeElement('peso', (float)$objetoPostal->getPeso());
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
		$writer->startElement('objeto_postal');
		$writer->writeElement('nome_destinatario', $this->_($destinatario->getNome(), 50));
		$writer->writeElement('telefone_destinatario', $this->_(preg_replace('/[^\d]/', '', $destinatario->getTelefone()), 12));
		$writer->writeElement('celular_destinatario', $this->_(preg_replace('/[^\d]/', '', $destinatario->getCelular()), 12));
		$writer->writeElement('email_destinatario', $this->_($destinatario->getEmail(), 50));
		$writer->writeElement('logradouro_destinatario', $this->_($destinatario->getLogradouro(), 50));
		$writer->writeElement('complemento_destinatario', $this->_($destinatario->getComplemento(), 30));
		$writer->writeElement('numero_end_destinatario', $this->_($destinatario->getNumero(), 6));
		$writer->endElement();
	}

	private function writeDestino(\XMLWriter $writer, Destino $destino)
	{
		if ($destino instanceof DestinoNacional) {
			$writer->startElement('nacional');
			$writer->writeElement('bairro_destinatario', $this->_($destino->getBairro(), 30));
			$writer->writeElement('cidade_destinatario', $this->_($destino->getCidade(), 30));
			$writer->writeElement('uf_destinatario', $this->_($destino->getUf(), 2, false));
			$writer->writeElement('cep_destinatario', $this->_(preg_replace('/[^\d]/', '', $destino->getCep()), 8));
			$writer->writeElement('codigo_usuario_postal');
			$writer->writeElement('centro_custo_cliente');
			$writer->writeElement('numero_nota_fiscal', $destino->getNumeroNotaFiscal());
			$writer->writeElement('serie_nota_fiscal', $this->_($destino->getSerieNotaFiscal(), 20));
			$writer->writeElement('valor_nota_fiscal', $destino->getValorNotaFiscal());
			$writer->writeElement('natureza_nota_fiscal', $this->_($destino->getNaturezaNotaFiscal(), 20));
			$writer->writeElement('descricao_objeto', $this->_($destino->getDescricaoObjeto(), 20));
			$writer->writeElement('valor_a_cobrar', (float)$destino->getValorACobrar());
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

		// De acordo com o manual este serviço é obrigatório 
		$writer->writeElement('codigo_servico_adicional', ServicoAdicional::SERVICE_REGISTRO);

		foreach ($servicosAdicionais as $servicoAdicional) {
			if ($servicoAdicional->getCodigoServicoAdicional() != ServicoAdicional::SERVICE_REGISTRO) {
				$writer->writeElement('codigo_servico_adicional', $servicoAdicional->getCodigoServicoAdicional());
				if ($servicoAdicional->getCodigoServicoAdicional() == ServicoAdicional::SERVICE_VALOR_DECLARADO()) {
					$writer->writeElement('valor_declarado', (float)$servicoAdicional->getValorDeclarado());
				}
			}
		}

		$writer->endElement();
	}

	private function writeDimensaoObjeto(\XMLWriter $writer, Dimensao $dimensao)
	{
		$writer->startElement('dimensao_objeto');
		$writer->writeElement('codigo_servico_adicional', ServicoAdicional::SERVICE_REGISTRO);
		$writer->writeElement('tipo_objeto', $dimensao->getTipo());
		$writer->writeElement('dimensao_altura', $dimensao->getAltura());
		$writer->writeElement('dimensao_largura', $dimensao->getLargura());
		$writer->writeElement('dimensao_comprimento', $dimensao->getComprimento());
		$writer->writeElement('dimensao_diametro', $dimensao->getDiametro());
		$writer->endElement();
	}
}
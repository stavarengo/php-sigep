<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\FechaPlpVariosServicosRetorno;
use PhpSigep\Model\PreListaDePostagem;
use PhpSigep\Services\Real\PreListaDePostagem\GerarXmlPreListaDePostagem;
use PhpSigep\Services\Result;

/**
 * @author: Stavarengo
 */
class FecharPreListaDePostagem
{

    /**
     * @var \PhpSigep\Services\Real\PreListaDePostagem\GerarXmlPreListaDePostagem
     */
    private $gerarXml;

    public function __construct()
    {
        $this->gerarXml = new GerarXmlPreListaDePostagem();
    }

    /**
     * @param PreListaDePostagem $params
     *
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\FechaPlpVariosServicosRetorno>
     */
    public function execute(\PhpSigep\Model\PreListaDePostagem $params)
    {
        $listaEtiquetas = array();
        foreach ($params->getEncomendas() as $objetoPostal) {
            $listaEtiquetas[] = $objetoPostal->getEtiqueta()->getEtiquetaSemDv();
        }

        $soapArgs = array(
            'xml'            => $this->gerarXml->gerar($params),
            'idPlpCliente'   => '',
            'cartaoPostagem' => $params->getAccessData()->getCartaoPostagem(),
            'listaEtiquetas' => $listaEtiquetas,
            'usuario'        => $params->getAccessData()->getUsuario(),
            'senha'          => $params->getAccessData()->getSenha(),
        );
        
        $result = new Result();
        try {
            $r = SoapClientFactory::getSoapClient()->fechaPlpVariosServicos($soapArgs);
            if (class_exists('\StaLib_Logger',false)) {
                \StaLib_Logger::log('Retorno SIGEP fecha PLP: ' . print_r($r, true));
            }
            if ($r instanceof \SoapFault) {
                throw $r;
            }
            if ($r && $r->return) {
                $result->setResult(new FechaPlpVariosServicosRetorno(array('idPlp' => $r->return)));
            } else {
                $result->setErrorCode(0);
                $result->setErrorMsg('A resposta do Correios não está no formato esperado. Resposta recebida: "' . 
                    $r . '"');
            }
        } catch (\Exception $e) {
            if ($e instanceof \SoapFault) {
                $result->setIsSoapFault(true);
                $result->setErrorCode($e->getCode());
                $result->setErrorMsg(SoapClientFactory::convertEncoding($e->getMessage()));
            } else {
                $result->setErrorCode($e->getCode());
                $result->setErrorMsg($e->getMessage());
            }
        }
        
        return $result;
    }
}

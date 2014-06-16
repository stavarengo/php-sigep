<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\Etiqueta;
use PhpSigep\Services\Result;

/**
 * @author: Stavarengo
 */
class GeraDigitoVerificadorEtiquetas
{

    /**
     * @param \PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params
     *
     * @return Etiqueta[]
     */
    public function execute(\PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params)
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

        $result = new Result();
        try {
            $soapReturn = SoapClientFactory::getSoapClient()->geraDigitoVerificadorEtiquetas($soapArgs);
            if ($soapReturn && is_object($soapReturn) && $soapReturn->return) {
                if (!is_array($soapReturn->return)) {
                    $soapReturn->return = (array)$soapReturn->return;
                }
            
                foreach ($soapReturn->return as $k => $dv) {
                    $etiquetas[$k]->setDv((int)$dv);
                }
                $result->setResult($etiquetas);
            } else {
                $result->setErrorCode(0);
                $result->setErrorMsg('A resposta do Correios não está no formato esperado. Resposta recebida: "' .
                    $soapReturn . '"');
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
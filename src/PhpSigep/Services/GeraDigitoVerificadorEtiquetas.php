<?php
namespace PhpSigep\Services;

use PhpSigep\Model\Etiqueta;

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
        $soap = SoapClientFactory::create();

        return $soap->geraDigitoVerificadorEtiquetas($params);
    }
}
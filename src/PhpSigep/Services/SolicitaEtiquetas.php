<?php
namespace PhpSigep\Services;

use PhpSigep\Model\Etiqueta;

/**
 * @author: Stavarengo
 */
class SolicitaEtiquetas
{

    /**
     * @param \PhpSigep\Model\SolicitaEtiquetas $params
     *
     * @return Etiqueta[]
     */
    public function execute(\PhpSigep\Model\SolicitaEtiquetas $params)
    {
        $soap = SoapClientFactory::create();

        return $soap->solicitaEtiquetas($params);
    }
}

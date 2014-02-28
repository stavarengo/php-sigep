<?php
namespace PhpSigep\Services;

use PhpSigep\Bootstrap;
use PhpSigep\Model\Dimensao;
use PhpSigep\Model\Etiqueta;
use PhpSigep\Model\ServicoAdicional;
use PhpSigep\Model\ServicoDePostagem;
use PhpSigep\Services\SoapClient\Real;
use PhpSigep\Services\SoapClient\Simulador;

/**
 * @author: Stavarengo
 */
class SoapClientFactory
{


    private function __construct()
    {
    }

    /**
     * @return Real|Simulador
     */
    public static function create()
    {
        if (Bootstrap::getConfig()->getSimular()) {
            return new Simulador();
        } else {
            return new Real();
        }
    }
}

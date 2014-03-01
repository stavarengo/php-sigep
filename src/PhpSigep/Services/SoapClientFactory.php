<?php
namespace PhpSigep\Services;

use PhpSigep\Bootstrap;
use PhpSigep\Services\SoapClient\Real;
use PhpSigep\Services\SoapClient\Simulador;
use PhpSigep\Services\SoapClient\SoapClientInterface;

/**
 * @author: Stavarengo
 */
class SoapClientFactory
{


    private function __construct()
    {
    }

    /**
     * @return SoapClientInterface
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

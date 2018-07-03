<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */

namespace PhpSigep\Services\Real;

use PhpSigep\Bootstrap;
use PhpSigep\Config;
use PhpSigep\Services\Real\Exception\SoapExtensionNotInstalled;

class SoapClientFactory
{
    const WEB_SERVICE_CHARSET = 'ISO-8859-1';

    /**
     * @var \SoapClient
     */
    protected static $_soapClient;
    /**
     * @var \SoapClient
     */
    protected static $_soapCalcPrecoPrazo;

    /**
     * @var \SoapClient
     */
    protected static $_soapRastrearObjetos;

    public static function getSoapClient()
    {
        if (!self::$_soapClient) {
            if (!extension_loaded('soap')) {
                throw new SoapExtensionNotInstalled('The "soap" module must be enabled in your PHP installation. The "soap" module is required in order to PHPSigep to make requests to the Correios WebService.');
            }

            $wsdl = Bootstrap::getConfig()->getWsdlAtendeCliente();

            $opts = array(
                'ssl' => array(
                    //'ciphers'           =>'RC4-SHA', // comentado o parâmetro ciphers devido ao erro que ocorre quando usado dados de ambiente de produção em um servidor local conforme issue https://github.com/stavarengo/php-sigep/issues/35#issuecomment-290081903
                    'verify_peer'       =>false, 
                    'verify_peer_name'  =>false
                )
            );
            // SOAP 1.1 client
            $params = array (
                'encoding'              => self::WEB_SERVICE_CHARSET, 
                'verifypeer'            => false, 
                'verifyhost'            => false, 
                'soap_version'          => SOAP_1_1, 
                'trace'                 => Bootstrap::getConfig()->getEnv() != Config::ENV_PRODUCTION,
                'exceptions'            => Bootstrap::getConfig()->getEnv() != Config::ENV_PRODUCTION, 
                "connection_timeout"    => 180, 
                'stream_context'        => stream_context_create($opts) 
            );

            self::$_soapClient = new \SoapClient($wsdl, $params);
        }

        return self::$_soapClient;
    }

    public static function getSoapCalcPrecoPrazo()
    {
        if (!self::$_soapCalcPrecoPrazo) {
            $wsdl = Bootstrap::getConfig()->getWsdlCalcPrecoPrazo();

            $opts = array(
                'ssl' => array(
                    'ciphers'           =>'RC4-SHA',
                    'verify_peer'       =>false,
                    'verify_peer_name'  =>false
                )
            );
            // SOAP 1.1 client
            $params = array (
                'encoding'              => self::WEB_SERVICE_CHARSET,
                'verifypeer'            => false,
                'verifyhost'            => false,
                'soap_version'          => SOAP_1_1,
                'trace'                 => Bootstrap::getConfig()->getEnv() != Config::ENV_PRODUCTION,
                'exceptions'            => Bootstrap::getConfig()->getEnv() != Config::ENV_PRODUCTION,
                "connection_timeout"    => 180,
                'stream_context'        => stream_context_create($opts)
            );

            self::$_soapCalcPrecoPrazo = new \SoapClient($wsdl, $params);
        }

        return self::$_soapCalcPrecoPrazo;
    }

    public static function getRastreioObjetos()
    {
        if (!self::$_soapRastrearObjetos) {
            $wsdl = Bootstrap::getConfig()->getWsdlRastrearObjetos();

            $opts = array(
                'ssl' => array(
                    //'ciphers'           =>'RC4-SHA',
                    'verify_peer'       =>false,
                    'verify_peer_name'  =>false
                )
            );
            // SOAP 1.1 client
            $params = array (
                'encoding'              => self::WEB_SERVICE_CHARSET,
                'verifypeer'            => false,
                'verifyhost'            => false,
                'soap_version'          => SOAP_1_1,
                'trace'                 => Bootstrap::getConfig()->getEnv() != Config::ENV_PRODUCTION,
                'exceptions'            => Bootstrap::getConfig()->getEnv() != Config::ENV_PRODUCTION,
                "connection_timeout"    => 180,
                'stream_context'        => stream_context_create($opts)
            );

            self::$_soapRastrearObjetos = new \SoapClient($wsdl, $params);
        }

        return self::$_soapRastrearObjetos;
    }

    /**
     * Se possível converte a string recebida.
     * @param $string
     * @return bool|string
     */
    public static function convertEncoding($string)
    {
        $to     = 'UTF-8';
        $from   = self::WEB_SERVICE_CHARSET;
        $str = false;
        
        if (function_exists('iconv')) {
            $str = iconv($from, $to . '//TRANSLIT', $string);
        } elseif (function_exists('mb_convert_encoding')) {
            $str = mb_convert_encoding($string, $to, $from);
        }

        if ($str === false) {
            $str = $string;
        }

        return $str;
    }
} 

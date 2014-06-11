<?php
namespace PhpSigep;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Loader.php';
Loader::registerAutoLoader();

/**
 * @author: Stavarengo
 */
class Bootstrap
{

    /**
     * @var \PhpSigep\Config
     */
    protected static $config;

    /**
     * Armazena os a configuração do php-sigep em um local compartilhado pelo projeto.
     * @param Config $config
     */
    public static function start(Config $config)
    {
        self::$config = $config;
    }

    /**
     * @throws BootstrapException
     * @return \PhpSigep\Config
     */
    public static function getConfig()
    {
        if (!self::$config) {
            throw new BootstrapException('Antes de usar o php-sigep você deve setar as configurações invocando o método \PhpSigep\Bootstrap::start().');
        }
        return self::$config;
    }
}

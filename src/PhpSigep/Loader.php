<?php
namespace PhpSigep;

/**
 * @author: Stavarengo
 */
class Loader
{

    /**
     * @var Loader
     */
    private static $instance;

    private static $cache = array();

    public static function registerAutoLoader()
    {
        spl_autoload_register(array(self::getInstance(), 'loadClass'));
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    public function loadClass($className)
    {
        if (class_exists($className, false) || interface_exists($className, false)) {
            return;
        }
        if (isset(self::$cache[$className])) {
            return;
        }

        $namespace = __NAMESPACE__ . '\\';
        if (strpos($className, $namespace) === 0) {
            $c    = 1;
            $file = str_replace($namespace, '', $className, $c);
            $file = dirname(__FILE__) . DIRECTORY_SEPARATOR . $file . '.php';
            $file = str_replace('\\', '/', $file);
            if (file_exists($file)) {
                require_once $file;
                self::$cache[$className] = true;
            }
        }
    }

    public function _loadVendorFile($filePath)
    {
    }

    public static function loadVendorFile($filePath)
    {
        self::getInstance()->_loadVendorFile($filePath);
    }
}

<?php
namespace Sigep;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Loader.php';
Loader::registerAutoLoader();

/**
 * @author: Stavarengo
 */
class Bootstrap
{

	/**
	 * @var \Sigep\Config
	 */
	protected static $config;

	public static function start(Config $config)
	{
		self::$config = $config;
	}

	/**
	 * @return \Sigep\Config
	 */
	public static function getConfig()
	{
		return self::$config;
	}
}

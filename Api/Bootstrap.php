<?php
namespace Sigep;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Loader.php';
Loader::registerAutoLoader();

use Sigep\Model\AccessData;

/**
 * @author: Stavarengo
 */
class Bootstrap
{

	/**
	 * @var \Sigep\Config
	 */
	protected static $config;
	/**
	 * @var AccessData
	 */
	protected static $accessData;

	public static function execute(AccessData $accessData = null)
	{
		self::$accessData = $accessData;
	}
	
	/**
	 * @return \Sigep\Config
	 */
	public static function getConfig()
	{
		if (!self::$config) {
			self::$config = new Config(array(
				'wsdlDir'    => implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), '..', 'wsdl')),
				'accessData' => self::$accessData,
			));
		}

		return self::$config;
	}
}

Bootstrap::execute();
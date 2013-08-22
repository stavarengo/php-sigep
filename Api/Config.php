<?php
namespace Sigep;

/**
 * @author: Stavarengo
 */
class Config
{
	protected $wsdlDir;

	public function __construct(array $configData)
	{
		foreach ($configData as $attr => $value) {
			$this->$attr = $value;
		}
	}

	/**
	 * Return the WSDL directory without slash at the end.
	 * Eg: /dir1/dir2/ become /dir1/dir2
	 * @return string
	 */
	public function getWsdlDir()
	{
		return rtrim($this->wsdlDir, '/\\');
	}

	/**
	 * @return string
	 */
	public function getWsdlAtendeCliente()
	{
		return $this->getWsdlDir() . DIRECTORY_SEPARATOR . 'AtendeClienteService.wsdl';
	}
}
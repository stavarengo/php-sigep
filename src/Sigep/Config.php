<?php
namespace Sigep;

/**
 * @author: Stavarengo
 */
class Config
{

	/**
	 * @var string
	 */
	protected $wsdlDir;
	/**
	 * @var string
	 */
	protected $xsdDir;
	/**
	 * @var bool
	 */
	protected $isDebug;

	public function __construct(array $configData)
	{
		$this->wsdlDir = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'Support', 'wsdl'));
		$this->xsdDir = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'Support', 'xsd'));
		$this->isDebug = false;
		
		foreach ($configData as $attr => $value) {
			if (!property_exists($this, $attr)) {
				throw new Exception('A configuração "' . $attr . '" não existe.');
			}
			$this->$attr = $value;
		}
	}

	/**
	 * Não defina isDebug como true em ambiente de produção.
	 * @return bool
	 */
	public function isDebug()
	{
		return (bool)$this->isDebug;
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
	 * Return the schema files directory without slash at the end.
	 * Eg: /dir1/dir2/ become /dir1/dir2
	 * @return string
	 */
	public function getXsdDir()
	{
		return rtrim($this->xsdDir, '/\\');
	}

	/**
	 * @return string
	 */
	public function getWsdlAtendeCliente()
	{
		return $this->getWsdlDir() . DIRECTORY_SEPARATOR . 'AtendeClienteService.wsdl';
	}

}
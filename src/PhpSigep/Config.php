<?php
namespace PhpSigep;

/**
 * @author: Stavarengo
 */
class Config
{

	/**
	 * @var string
	 */
	protected $xsdDir;
	/**
	 * @var bool
	 */
	protected $isDebug;

    /**
     * @var bool
     */
    protected $simular = false;

	public function __construct(array $configData)
	{
		$this->xsdDir  = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'Support', 'xsd'));
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
		return 'http://sigep.correios.com.br/sigep/update/AtendeClienteService.wsdl';
	}
	
	/**
	 * @return string
	 */
	public function getWsdlCalcPrecoPrazo()
	{
		return 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL';
	}

    /**
     * @param boolean $simular
     */
    public function setSimular($simular)
    {
        $this->simular = $simular;
    }

    /**
     * @return boolean
     */
    public function getSimular()
    {
        return $this->simular;
    }

}
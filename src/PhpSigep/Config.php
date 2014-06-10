<?php
namespace PhpSigep;

use PhpSigep\Cache\Options;
use PhpSigep\Cache\Storage\Adapter\AdapterOptions;
use PhpSigep\Cache\StorageInterface;

/**
 * @author: Stavarengo
 */
class Config extends DefaultStdClass
{

    /**
     * @var bool
     */
    protected $isDebug;

    /**
     * @var bool
     */
    protected $simular = false;

    /**
     * @var AdapterOptions
     */
    protected $cacheOptions = null;

    /**
     * Fábrica que irá criar e retornar uma instância de {@link \PhpSigep\Cache\StorageInterface }
     * @var string|FactoryInterface
     */
    protected $cacheFactory = 'PhpSigep\Cache\Factory';

    /**
     * @var StorageInterface
     */
    protected $cacheInstance;

    public function __construct(array $configData)
    {
        $this->xsdDir  = implode(DIRECTORY_SEPARATOR, array(dirname(__FILE__), 'Support', 'xsd'));
        $this->isDebug = false;

        parent::__construct($configData);
    }

    /**
     * Não defina isDebug como true em ambiente de produção.
     * @return bool
     */
    public function getIsDebug()
    {
        return (bool)$this->isDebug;
    }

    /**
     * @param boolean $isDebug
     */
    public function setIsDebug($isDebug)
    {
        $this->isDebug = $isDebug;

        return $this;
    }

    /**
     * @return string
     */
    public function getWsdlAtendeCliente()
    {
        if ($this->getIsDebug()) {
            return 'https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';
        } else {
            return 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';
        }
        // return 'http://sigep.correios.com.br/sigep/update/AtendeClienteService.wsdl';
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

    /**
     * @param array|\PhpSigep\Cache\Options $cacheOptions
     */
    public function setCacheOptions($cacheOptions)
    {
        if (!($cacheOptions instanceof Options)) {
            $cacheOptions = new Options($cacheOptions);
        }
        $this->cacheOptions = $cacheOptions;
    }

    /**
     * @return Options
     */
    public function getCacheOptions()
    {
        if ($this->cacheOptions === null) {
            $this->setCacheOptions(new Options());
        }

        return $this->cacheOptions;
    }

    /**
     * @param string|FactoryInterface $cacheFactory
     * @throws InvalidArgument
     */
    public function setCacheFactory($cacheFactory)
    {
        if ($cacheFactory != $this->cacheFactory || !($cacheFactory instanceof FactoryInterface)) {
            if (is_string($cacheFactory)) {
                $cacheFactory = new $cacheFactory;
            }
            if (!$cacheFactory || !($cacheFactory instanceof FactoryInterface)) {
                throw new InvalidArgument('O cacheFactory deve implementar PhpSigep\FactoryInterface.');
            }

            $this->cacheFactory = $cacheFactory;
        }
    }

    /**
     * @return \PhpSigep\FactoryInterface
     */
    public function getCacheFactory()
    {
        $this->setCacheFactory($this->cacheFactory);

        return $this->cacheFactory;
    }

    /**
     * Este não é o melhor lugar para este método, mas dada a simplicidade do projeto ele pode ficar aqui por enquanto.
     * @todo Criar um Service Manager para abstrair a criação dos objetos.
     */
    public function getCacheInstance()
    {
        if (!$this->cacheInstance) {
            $factory             = $this->getCacheFactory();
            $this->cacheInstance = $factory->createService($this);
        }

        return $this->cacheInstance;
    }
}

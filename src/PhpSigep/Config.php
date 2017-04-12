<?php
namespace PhpSigep;

use PhpSigep\Cache\Options;
use PhpSigep\Cache\Storage\Adapter\AdapterOptions;
use PhpSigep\Cache\StorageInterface;
use PhpSigep\Model\AccessData;
use PhpSigep\Model\AccessDataHomologacao;

/**
 * @author: Stavarengo
 * @author: davidalves1
 */
class Config extends DefaultStdClass
{
    /**
     * Indica que estamos no ambiente real (ambiente de producao).
     */
    const ENV_PRODUCTION = 1;
    /**
     * Indica que estamos no ambiente de desenvolvimento.
     */
    const ENV_DEVELOPMENT = 2;

    const WSDL_ATENDE_CLIENTE_PRODUCTION = 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';

    const WSDL_ATENDE_CLIENTE_DEVELOPMENT = 'https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';

    const WSDL_CAL_PRECO_PRAZO = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL';

    const WSDL_RASTREAR_OBJETOS = 'https://webservice.correios.com.br/service/rastro/Rastro.wsdl';

    /**
     * Endereço para o WSDL AtendeCliente.
     * Esse WSDL possui duas versões, uma para o ambiente de produção e outra para o ambiente de desenvolvimento.
     * @var string
     */
    protected $wsdlAtendeCliente = self::WSDL_ATENDE_CLIENTE_DEVELOPMENT;

    /**
     * @var string
     */
    protected $wsdlCalPrecoPrazo = self::WSDL_CAL_PRECO_PRAZO;

    /**
     * @var string
     */
    protected $wsdlRastrearObjetos = self::WSDL_RASTREAR_OBJETOS;

    /**
     * @var int
     */
    protected $env = self::ENV_DEVELOPMENT;

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

    /**
     * Muitos dos métodos do php-sigep recebem como parâmetro uma instância de {@link AccessData}, mas você não precisa
     * passar essa informação todas as vezes que for pedido.
     * O valor setado neste atributo será usado sempre que um método precisar dos dados de acesso mas você não tiver
     * informado um.
     *
     * @var AccessData
     */
    protected $accessData;

    /**
     * @param array $configData
     *      Qualquer atributo desta classe pode ser usado como uma chave deste array.
     *      Ex: array('cacheOptions' => ...)
     */
    public function __construct(array $configData = array())
    {
        $this->setAccessData(new AccessDataHomologacao());

        parent::__construct($configData);
    }

    /**
     * Não defina env como true em ambiente de produção.
     * @return bool
     */
    public function getEnv()
    {
        return (int)$this->env;
    }

    /**
     * @param \PhpSigep\Model\AccessData $accessData
     * @return $this;
     */
    public function setAccessData(\PhpSigep\Model\AccessData $accessData)
    {
        $this->accessData = $accessData;

        return $this;
    }

    /**
     * @return \PhpSigep\Model\AccessData
     */
    public function getAccessData()
    {
        return $this->accessData;
    }

    /**
     * @param int $env
     * @param bool $updateWsdlUrl
     * @return $this
     */
    public function setEnv($env, $updateWsdlUrl = true)
    {
        $this->env = $env;
        if ($updateWsdlUrl) {
            if ($env == self::ENV_DEVELOPMENT) {
                $this->setWsdlAtendeCliente(self::WSDL_ATENDE_CLIENTE_DEVELOPMENT);
            } else {
                $this->setWsdlAtendeCliente(self::WSDL_ATENDE_CLIENTE_PRODUCTION);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getWsdlAtendeCliente()
    {
        return $this->wsdlAtendeCliente;
    }

    /**
     * @param string $wsdlAtendeCliente
     * @return $this
     */
    public function setWsdlAtendeCliente($wsdlAtendeCliente)
    {
        $this->wsdlAtendeCliente = $wsdlAtendeCliente;

        return $this;
    }

    /**
     * @param string $wsdlCalPrecoPrazo
     * @return $this;
     */
    public function setWsdlCalPrecoPrazo($wsdlCalPrecoPrazo)
    {
        $this->wsdlCalPrecoPrazo = $wsdlCalPrecoPrazo;

        return $this;
    }

    /**
     * @return string
     */
    public function getWsdlCalcPrecoPrazo()
    {
        return $this->wsdlCalPrecoPrazo;
    }

    /**
     * @param $wsdlRastrearObjetos
     * @return $this
     */
    public function setWsdlRastrearObjetos($wsdlRastrearObjetos)
    {
        $this->wsdlRastrearObjetos = $wsdlRastrearObjetos;

        return $this;
    }

    /**
     * @return string
     */
    public function getWsdlRastrearObjetos()
    {
        return $this->wsdlRastrearObjetos;
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

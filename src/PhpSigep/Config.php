<?php
namespace PhpSigep;

use PhpSigep\Cache\Options;
use PhpSigep\Cache\Storage\Adapter\AdapterOptions;
use PhpSigep\Cache\StorageInterface;
use PhpSigep\Model\AccessData;
use PhpSigep\Model\Proxy;
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
     * Permite gerenciar o tempo de timeout das conexões (em caso de problemas de timeout verifique seu ambiente, acima de 30 deve funcionar somente em linha de comando)
     */
    const CONNECTION_TIMEOUT = 30;
    
    /**
     * Defina o método para cache no WSDL do PHP (melhor WSDL_CACHE_BOTH)
     * WSDL_CACHE_MEMORY = memória
     * WSDL_CACHE_DISK = disco
     * WSDL_CACHE_BOTH = memória e disco
     * WSDL_CACHE_NONE = nenhum
     */
    const WSDL_CACHE = WSDL_CACHE_BOTH;

    /**
     * Indica que estamos no ambiente de desenvolvimento.
     */
    const ENV_DEVELOPMENT = 2;
    const XML_ENCODE_ISO = "iso-8859-1";
    const XML_ENCODE_UTF = "utf-8";
    const WSDL_ATENDE_CLIENTE_PRODUCTION = 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';
    const WSDL_ATENDE_CLIENTE_DEVELOPMENT = 'https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl';
    const WSDL_CAL_PRECO_PRAZO = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?WSDL';
    const WSDL_RASTREAR_OBJETOS = 'https://webservice.correios.com.br/service/rastro/Rastro.wsdl';
    const WSDL_AGENCIAS_WS = 'https://cws.correios.com.br/cws/agenciaService/agenciaWS';
    const WSDL_REVERSA_PRODUCTION = 'https://cws.correios.com.br/logisticaReversaWS/logisticaReversaService/logisticaReversaWS?wsdl';
    const WSDL_REVERSA_DEVELOPMENT = 'https://apphom.correios.com.br/logisticaReversaWS/logisticaReversaService/logisticaReversaWS?wsdl';

//    const WSDL_REVERSA_PRODUCTION = 'http://webservicescol.correios.com.br/ScolWeb/WebServiceScol?wsdl';
//    const WSDL_REVERSA_DEVELOPMENT = 'http://webservicescolhomologacao.correios.com.br/ScolWeb/WebServiceScol?wsdl';

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
     * @var string
     */
    protected $wsdlAgenciaWS = self::WSDL_AGENCIAS_WS;

    /**
     * @var int
     */
    protected $env = self::ENV_DEVELOPMENT;

    /**
     * @var string
     */
    protected $xml_encode = self::XML_ENCODE_UTF;

    /**
     * @var bool
     */
    protected $simular = false;

    /**
     * @var int
     */
    protected $wsdlCache = self::WSDL_CACHE;

    /**
     * @var int
     */
    protected $connectionTimeout = self::CONNECTION_TIMEOUT;

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
     * Configurações de proxy para o SoapClient.
     *
     * @var proxy
     */
    protected $proxy;

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
        return (int) $this->env;
    }

    /**
     * @return int
     */
    public function getWsdlCache()
    {
        return (int) $this->wsdlCache;
    }

    /**
     * @return int
     */
    public function getConnectionTimeout()
    {
        return (int) $this->connectionTimeout;
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
     * @param \PhpSigep\Model\Proxy $proxy
     * @return $this;
     */
    public function setProxy(\PhpSigep\Model\Proxy $proxy)
    {
        $this->proxy = $proxy;

        return $this;
    }

    /**
     * @return \PhpSigep\Model\Proxy
     */
    public function getProxy()
    {
        return $this->proxy;
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
     * @param int $env
     * @return $this
     */
    public function setXmlEncode($xml_encode)
    {
        if ($xml_encode == self::XML_ENCODE_ISO) {
            $this->xml_encode = self::XML_ENCODE_ISO;
        } else {
            $this->xml_encode = self::XML_ENCODE_UTF;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getXmlEncode()
    {
        return $this->xml_encode;
    }

    /**
     * @return string
     */
    public function getWsdlAtendeCliente()
    {
        return $this->wsdlAtendeCliente;
    }

    public function getWsdlReversa()
    {
        switch ($this->env) {
            case self::ENV_PRODUCTION:
                return self::WSDL_REVERSA_PRODUCTION;
                break;
            case self::ENV_DEVELOPMENT:
            default:
                return self::WSDL_REVERSA_DEVELOPMENT;
                break;
        }
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
     * @param string $wsdlAgenciaWS
     * 
     * @return $this;
     */
    public function setWsdlAgenciaWS($wsdlAgenciaWS)
    {
        $this->wsdlAgenciaWS = $wsdlAgenciaWS;

        return $this;
    }

    /**
     * @return string
     */
    public function getWsdlAgenciaWS()
    {
        return $this->wsdlAgenciaWS;
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
            $factory = $this->getCacheFactory();
            $this->cacheInstance = $factory->createService($this);
        }

        return $this->cacheInstance;
    }
}

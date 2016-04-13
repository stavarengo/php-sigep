<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class ServicoDePostagem extends AbstractModel
{
    const SERVICE_PAC_41068                  = 41068;
    const SERVICE_PAC_41106                  = 41106;
    const SERVICE_PAC_GRANDES_FORMATOS       = 41300;
    const SERVICE_E_SEDEX_STANDARD           = 81019;
    const SERVICE_SEDEX_40096                = 40096;
    const SERVICE_SEDEX_40436                = 40436;
    const SERVICE_SEDEX_40444                = 40444;
    const SERVICE_SEDEX_12                   = 40169;
    const SERVICE_SEDEX_10                   = 40215;
    const SERVICE_SEDEX_10_PACOTE            = 40886;
    const SERVICE_SEDEX_HOJE_40290           = 40290;
    const SERVICE_SEDEX_HOJE_40878           = 40878;
    const SERVICE_SEDEX_A_VISTA              = 40010;
    const SERVICE_SEDEX_VAREJO_A_COBRAR      = 40045;
    const SERVICE_SEDEX_AGRUPADO             = 41009;
    const SERVICE_SEDEX_REVERSO              = 40380;
    const SERVICE_SEDEX_PAGAMENTO_NA_ENTREGA = 40630;
    const SERVICE_CARTA_COMERCIAL_A_FATURAR  = 10065;
    const SERVICE_CARTA_REGISTRADA           = 10014;
    const SERVICE_CARTA_COMERCIAL_REGISTRADA_CTR_EP_MAQ_FRAN = 10707;
//    const SERVICE_CARTA_REGISTRADA           = 10138;

    protected static $services = array(
        self::SERVICE_PAC_41068                  => array('Pac 41068', 109819),
        self::SERVICE_PAC_41106                  => array('Pac 41106', 110353),
        self::SERVICE_PAC_GRANDES_FORMATOS       => array('Pac Grandes Formatos', 120366),
        self::SERVICE_E_SEDEX_STANDARD           => array('E-Sedex Standard', 104672),
        self::SERVICE_SEDEX_40096                => array('Sedex 40096', 104625),
        self::SERVICE_SEDEX_40436                => array('Sedex 40436', 109810),
        self::SERVICE_SEDEX_40444                => array('Sedex 40444', 109811),
        self::SERVICE_SEDEX_12                   => array('Sedex 12', 115218),
        self::SERVICE_SEDEX_10                   => array('Sedex 10', 104707),
        self::SERVICE_SEDEX_10_PACOTE            => array('Sedex 10 Pacote', null),
        self::SERVICE_SEDEX_HOJE_40290           => array('Sedex Hoje 40290', 108934),
        self::SERVICE_SEDEX_HOJE_40878           => array('Sedex Hoje 40878', null),
        self::SERVICE_SEDEX_A_VISTA              => array('Sedex a vista', 104295),
        self::SERVICE_SEDEX_VAREJO_A_COBRAR      => array('Sedex Varejo a Cobrar', null),
        self::SERVICE_SEDEX_AGRUPADO             => array('Sedex Agrupado', 119461),
        self::SERVICE_SEDEX_REVERSO              => array('Sedex Reverso', 109806),
        self::SERVICE_SEDEX_PAGAMENTO_NA_ENTREGA => array('Sedex Pagamento na Entrega', 114976),
        self::SERVICE_CARTA_COMERCIAL_A_FATURAR  => array('Carta Comercial a Faturar', 109480),
        self::SERVICE_CARTA_REGISTRADA           => array('Carta Registrada', 116985),
        self::SERVICE_CARTA_COMERCIAL_REGISTRADA_CTR_EP_MAQ_FRAN           => array('Carta Comercial Registrada CTR EP MÁQ FRAN', 120072),
    );

    /**
     * @var int
     */
    protected $codigo;
    /**
     * @var int
     */
    protected $idServico;
    /**
     * @var string
     */
    protected $nome;

    /**
     * @param int $serviceCode
     *        One of the constants {@link ServicoDePostagem}::SERVICE_*
     *
     * @throws Exception
     */
    public function __construct($serviceCode)
    {
        if (!isset(self::$services[$serviceCode])) {
            throw new Exception('There is no service with the code "' . $serviceCode . '".');
        }

        $service = self::$services[$serviceCode];
        parent::__construct(
            array(
                'codigo'    => $serviceCode,
                'nome'      => $service[0],
                'idServico' => $service[1],
            )
        );
    }

    /**
     * @return ServicoDePostagem[]
     */
    public static function getAll()
    {
        $r = array();
        foreach (self::$services as $serviceCode => $serviceDetails) {
            $r[] = new self($serviceCode);
        }
        
        return $r;
    }

    /**
     * @param int $serviceCode
     *        One of the constants {@link ServicoDePostagem}::SERVICE_*
     *
     * @return bool
     */
    public function is($serviceCode)
    {
        return $this->getCodigo() == $serviceCode;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return int
     */
    public function getIdServico()
    {
        return $this->idServico;
    }

    /**
     * @param int $idServico
     */
    public function setIdServico($idServico)
    {
        $this->idServico = $idServico;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }


}

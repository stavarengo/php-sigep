<?php
namespace Sigep\Model;

/**
 * @author: Stavarengo
 */
class ServicoDePostagem extends AbstractModel
{

	const SERVICE_E_SEDEX           = 81019;
	const SERVICE_PAC               = 41068;
	const SERVICE_SEDEX_40436       = 40436;
	const SERVICE_SEDEX_40096       = 40096;
	const SERVICE_SEDEX_40444       = 40444;
	const SERVICE_SEDEX_10_ENVELOPE = 40215;
	const SERVICE_CARTA             = 10065;
	const SERVICE_SEDEX_10_PACOTE   = 40886;
	const SERVICE_SEDEX_HOJE        = 40878;
	const SERVICE_SEDEX_AGRUPADO    = 41009;
	const SERVICE_CARTA_REGISTRADA  = 10138;
	private static $services = array(
		self::SERVICE_E_SEDEX           => array('E-sedex', 104672),
		self::SERVICE_PAC               => array('Pac', 109819),
		self::SERVICE_SEDEX_40436       => array('Sedex', 109810),
		self::SERVICE_SEDEX_40096       => array('Sedex', 104625),
		self::SERVICE_SEDEX_40444       => array('Sedex', 109811),
		self::SERVICE_SEDEX_10_ENVELOPE => array('Sedex 10 Envelope', 104707),
		self::SERVICE_CARTA             => array('Carta', 109480),
		self::SERVICE_SEDEX_10_PACOTE   => array('Sedex 10 Pacote', null),
		self::SERVICE_SEDEX_HOJE        => array('Sedex Hoje', null),
		self::SERVICE_SEDEX_AGRUPADO    => array('Sedex Agrupado', 119461),
		self::SERVICE_CARTA_REGISTRADA  => array('Carta Registrada', 116985),
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
		$serviceCode = (int)$serviceCode;
		if (!isset(self::$services[$serviceCode])) {
			throw new Exception('There is no service with the code "' . $serviceCode . '".');
		}

		$service = self::$services[$serviceCode];
		parent::__construct(array(
			'codigo'    => $serviceCode,
			'nome'      => $service[0],
			'idServico' => $service[1],
		));
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
	public function getCodigo()
	{
		return $this->codigo;
	}

	/**
	 * @param int $idServico
	 */
	public function setIdServico($idServico)
	{
		$this->idServico = $idServico;
	}

	/**
	 * @return int
	 */
	public function getIdServico()
	{
		return $this->idServico;
	}

	/**
	 * @param string $nome
	 */
	public function setNome($nome)
	{
		$this->nome = $nome;
	}

	/**
	 * @return string
	 */
	public function getNome()
	{
		return $this->nome;
	}


}
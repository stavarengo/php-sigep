<?php
namespace Sigep\Model;

/**
 * @author: Stavarengo
 */
class Diretoria extends AbstractModel
{

	const DIRETORIA_AC_ADMINISTRACAO_CENTRAL = 1;
	const DIRETORIA_DR_ACRE                  = 3;
	const DIRETORIA_DR_ALAGOAS               = 4;
	const DIRETORIA_DR_AMAZONAS              = 6;
	const DIRETORIA_DR_AMAPA                 = 5;
	const DIRETORIA_DR_BAHIA                 = 8;
	const DIRETORIA_DR_BRASILIA              = 10;
	const DIRETORIA_DR_CEARA                 = 12;
	const DIRETORIA_DR_ESPIRITO_SANTO        = 14;
	const DIRETORIA_DR_GOIAS                 = 16;
	const DIRETORIA_DR_MARANHAO              = 18;
	const DIRETORIA_DR_MINAS_GERAIS          = 20;
	const DIRETORIA_DR_MATO_GROSSO_DO_SUL    = 22;
	const DIRETORIA_DR_MATO_GROSSO           = 24;
	const DIRETORIA_DR_PARA                  = 28;
	const DIRETORIA_DR_PARAIBA               = 30;
	const DIRETORIA_DR_PERNAMBUCO            = 32;
	const DIRETORIA_DR_PIAUI                 = 34;
	const DIRETORIA_DR_PARANA                = 36;
	const DIRETORIA_DR_RIO_DE_JANEIRO        = 50;
	const DIRETORIA_DR_RIO_GRANDE_DO_NORTE   = 60;
	const DIRETORIA_DR_RONDONIA              = 26;
	const DIRETORIA_DR_RORAIMA               = 65;
	const DIRETORIA_DR_RIO_GRANDE_DO_SUL     = 64;
	const DIRETORIA_DR_SANTA_CATARINA        = 68;
	const DIRETORIA_DR_SERGIPE               = 70;
	const DIRETORIA_DR_SAO_PAULO_INTERIOR    = 74;
	const DIRETORIA_DR_SAO_PAULO             = 72;
	const DIRETORIA_DR_TOCANTINS             = 75;
	private static $diretorias = array(
		self::DIRETORIA_AC_ADMINISTRACAO_CENTRAL => 'AC Administraçao Central',
		self::DIRETORIA_DR_ACRE                  => 'DR - Acre',
		self::DIRETORIA_DR_ALAGOAS               => 'DR - Alagoas',
		self::DIRETORIA_DR_AMAZONAS              => 'DR - Amazonas',
		self::DIRETORIA_DR_AMAPA                 => 'DR - Amapá',
		self::DIRETORIA_DR_BAHIA                 => 'DR - Bahia',
		self::DIRETORIA_DR_BRASILIA              => 'DR - Brasília',
		self::DIRETORIA_DR_CEARA                 => 'DR - Ceará',
		self::DIRETORIA_DR_ESPIRITO_SANTO        => 'DR - Espirito Santo',
		self::DIRETORIA_DR_GOIAS                 => 'DR - Goiás',
		self::DIRETORIA_DR_MARANHAO              => 'DR - Maranhão',
		self::DIRETORIA_DR_MINAS_GERAIS          => 'DR - Minas Gerais',
		self::DIRETORIA_DR_MATO_GROSSO_DO_SUL    => 'DR - Mato Grosso do Sul',
		self::DIRETORIA_DR_MATO_GROSSO           => 'DR - Mato Grosso',
		self::DIRETORIA_DR_PARA                  => 'DR - Pará',
		self::DIRETORIA_DR_PARAIBA               => 'DR - Paraíba',
		self::DIRETORIA_DR_PERNAMBUCO            => 'DR - Pernambuco',
		self::DIRETORIA_DR_PIAUI                 => 'DR - Piauí',
		self::DIRETORIA_DR_PARANA                => 'DR - Paraná',
		self::DIRETORIA_DR_RIO_DE_JANEIRO        => 'DR - Rio de Janeiro',
		self::DIRETORIA_DR_RIO_GRANDE_DO_NORTE   => 'DR - Rio Grande do Norte',
		self::DIRETORIA_DR_RONDONIA              => 'DR - Rondonia',
		self::DIRETORIA_DR_RORAIMA               => 'DR - Roraima',
		self::DIRETORIA_DR_RIO_GRANDE_DO_SUL     => 'DR - Rio Grande do Sul',
		self::DIRETORIA_DR_SANTA_CATARINA        => 'DR - Santa Catarina',
		self::DIRETORIA_DR_SERGIPE               => 'DR - Sergipe',
		self::DIRETORIA_DR_SAO_PAULO_INTERIOR    => 'DR - São Paulo Interior',
		self::DIRETORIA_DR_SAO_PAULO             => 'DR - São Paulo',
		self::DIRETORIA_DR_TOCANTINS             => 'DR - Tocantins',
	);
	/**
	 * @var int
	 */
	protected $numero;
	/**
	 * @var string
	 */
	protected $nome;

	/**
	 * @param int $numeroDiretoria
	 *        Uma das constantes {@link Diretoria}::DIRETORIA_*
	 *
	 * @throws Exception
	 */
	public function __construct($numeroDiretoria)
	{
		$numeroDiretoria = (int)$numeroDiretoria;
		if (!isset(self::$diretorias[$numeroDiretoria])) {
			throw new Exception('Não existe uma diretoria de número "' . $numeroDiretoria . '".');
		}

		parent::__construct(array(
			'numero' => $numeroDiretoria,
			'nome'   => self::$diretorias[$numeroDiretoria],
		));
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

	/**
	 * @return int
	 */
	public function getNumero()
	{
		return $this->numero;
	}

	/**
	 * @param int $numero
	 */
	public function setNumero($numero)
	{
		$this->numero = $numero;
	}

}
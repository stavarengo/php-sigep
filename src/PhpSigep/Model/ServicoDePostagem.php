<?php

namespace PhpSigep\Model;

/**
 * @author : Stavarengo
 * @modify Jonathan Célio da Silva <jonathan.clio@hotmail.com>
 */
class ServicoDePostagem extends AbstractModel
{
    const SERVICE_PAC_41068 = '41068';
    const SERVICE_PAC_04510 = '04510';
    const SERVICE_PAC_CONTRATO_10065 = '10065';
    const SERVICE_PAC_GRANDES_FORMATOS = '41300';
    const SERVICE_PAC_CONTRATO_GRANDES_FORMATOS = '04693';
    const SERVICE_PAC_CONTRATO_UO = '04812';

    const SERVICE_SEDEX_41556 = '41556';
    const SERVICE_SEDEX_12 = '40169';
    const SERVICE_SEDEX_10 = '40215';
    const SERVICE_SEDEX_10_PACOTE = '40886';
    const SERVICE_SEDEX_HOJE_40290 = '40290';
    const SERVICE_SEDEX_HOJE_40878 = '40878';
    const SERVICE_SEDEX_A_VISTA = '04014';
    const SERVICE_SEDEX_VAREJO_A_COBRAR = '40045';
    const SERVICE_SEDEX_AGRUPADO = '41009';
    const SERVICE_SEDEX_REVERSO = '40380';
    const SERVICE_SEDEX_PAGAMENTO_NA_ENTREGA = '04189';
    const SERVICE_SEDEX_CONTRATO_UO = '04316';
    const SERVICE_PAC_PAGAMENTO_NA_ENTREGA = '04685';
    const SERVICE_CARTA_COMERCIAL_A_FATURAR = '10065';
    const SERVICE_CARTA_REGISTRADA = '10014';
    const SERVICE_SEDEX_CONTRATO_AGENCIA = '04162';
    const SERVICE_PAC_CONTRATO_AGENCIA = '04669';
    const SERVICE_SEDEX_REVERSO_CONTRATO_AGENCIA = '04170';
    const SERVICE_PAC_REVERSO_CONTRATO_AGENCIA = '04677';
    const SERVICE_CARTA_COM_A_FATURAR_SELO_E_SE = '12556';
    const SERVICE_CARTA_COMERCIAL_REGISTRADA_CTR_EP_MAQ_FRAN = '10707';

    // CODIGOS REFERENTES A LIMINAR ABCOMM
    const SERVICE_SEDEX_CONTRATO_GRANDES_FORMATOS_LM = '04146';
    const SERVICE_SEDEX_CONTRATO_AGENCIA_LM = '04154';
    const SERVICE_SEDEX_REVERSO_LM = '04243';
    const SERVICE_SEDEX_CONTRATO_UO_LM = '04278';

    const SERVICE_PAC_CONTRATO_GRANDES_FORMATOS_LM = '04883';
    const SERVICE_PAC_CONTRATO_AGENCIA_LM = '04367';
    const SERVICE_PAC_REVERSO_LM = '04375';
    const SERVICE_PAC_CONTRATO_UO_LM = '04332';

    const SERVICE_SEDEX_CONTRATO_AGENCIA_PAGAMENTO_NA_ENTREGA_LM = '04151';
    const SERVICE_PAC_CONTRATO_AGENCIA_PAGAMENTO_NA_ENTREGA_LM = '04308';

    const SERVICE_SEDEX_CONTRATO_AGENCIA_TA = '04553';
    const SERVICE_PAC_CONTRATO_AGENCIA_TA = '04596';

//    const SERVICE_CARTA_REGISTRADA           = '10138';

    protected static $services
        = array(
            self::SERVICE_PAC_41068                                  => array('Pac 41068', 109819),
            self::SERVICE_PAC_04510                                  => array('Pac 04510', 124887),
            self::SERVICE_PAC_CONTRATO_10065                         => array('Pac 10065', 109480),
            self::SERVICE_PAC_GRANDES_FORMATOS                       => array('Pac Grandes Formatos', 120366),
            self::SERVICE_PAC_CONTRATO_GRANDES_FORMATOS              => array('Pac Contrato Grandes Formatos', 125248),
            self::SERVICE_PAC_CONTRATO_UO                            => array('Pac Contrato - UO', 124899),
            self::SERVICE_SEDEX_41556                                => array('Sedex 41556', 121877),
            self::SERVICE_SEDEX_12                                   => array('Sedex 12', 115218),
            self::SERVICE_SEDEX_10                                   => array('Sedex 10', 104707),
            self::SERVICE_SEDEX_10_PACOTE                            => array('Sedex 10 Pacote', null),
            self::SERVICE_SEDEX_HOJE_40290                           => array('Sedex Hoje 40290', 108934),
            self::SERVICE_SEDEX_HOJE_40878                           => array('Sedex Hoje 40878', null),
            self::SERVICE_SEDEX_A_VISTA                              => array('Sedex a vista', 104295),
            self::SERVICE_SEDEX_VAREJO_A_COBRAR                      => array('Sedex Varejo a Cobrar', null),
            self::SERVICE_SEDEX_AGRUPADO                             => array('Sedex Agrupado', 119461),
            self::SERVICE_SEDEX_REVERSO                              => array('Sedex Reverso', 109806),
            self::SERVICE_SEDEX_PAGAMENTO_NA_ENTREGA                 => array('Sedex Pagamento na Entrega', 114976),
            self::SERVICE_SEDEX_CONTRATO_UO                          => array('Sedex Contrato - UO', 124900),
            self::SERVICE_PAC_PAGAMENTO_NA_ENTREGA                   => array('PAC Pagamento na Entrega', 114976),
            self::SERVICE_CARTA_COMERCIAL_A_FATURAR                  => array('Carta Comercial a Faturar', 109480),
            self::SERVICE_CARTA_REGISTRADA                           => array('Carta Registrada', 116985),
            self::SERVICE_CARTA_COM_A_FATURAR_SELO_E_SE              => array(
                'Carta Comerical Registrada a Faturar',
                160104
            ),
            self::SERVICE_CARTA_COMERCIAL_REGISTRADA_CTR_EP_MAQ_FRAN => array(
                'Carta Comercial Registrada CTR EP MÁQ FRAN',
                120072
            ),
            self::SERVICE_SEDEX_CONTRATO_AGENCIA                     => array('SEDEX Contrato Agência', 124849),
            self::SERVICE_PAC_CONTRATO_AGENCIA                       => array('PAC Contrato Agência', 124884),
            self::SERVICE_SEDEX_REVERSO_CONTRATO_AGENCIA             => array('SEDEX Reverso Contrato Agência', 124849),
            self::SERVICE_PAC_REVERSO_CONTRATO_AGENCIA               => array('PAC Reverso Contrato Agência', 124884),
            self::SERVICE_SEDEX_CONTRATO_GRANDES_FORMATOS_LM         => array(
                'SEDEX Contrato Grandes Formatos (Liminar ABCOMM)',
                null
            ),
            self::SERVICE_SEDEX_CONTRATO_AGENCIA_LM                  => array(
                'SEDEX Contrato Agência (Liminar ABCOMM)',
                160126
            ),
            self::SERVICE_SEDEX_REVERSO_LM                           => array('SEDEX Reverso (Liminar ABCOMM)', null),
            self::SERVICE_SEDEX_CONTRATO_UO_LM                       => array(
                'SEDEX Contrato UO (Liminar ABCOMM)',
                null
            ),

            self::SERVICE_PAC_CONTRATO_GRANDES_FORMATOS_LM => array(
                'PAC Contrato Grandes Formatos (Liminar ABCOMM)',
                null
            ),
            self::SERVICE_PAC_CONTRATO_AGENCIA_LM          => array('PAC Contrato Agência (Liminar ABCOMM)', 160123),
            self::SERVICE_PAC_REVERSO_LM                   => array('PAC Reverso (Liminar ABCOMM)', null),
            self::SERVICE_PAC_CONTRATO_UO_LM               => array('PAC Contrato UO (Liminar ABCOMM)', null),

            self::SERVICE_SEDEX_CONTRATO_AGENCIA_PAGAMENTO_NA_ENTREGA_LM => array(
                'SEDEX Contrato Agencia Pagamento na Entrega (Liminar ABCOMM)',
                null
            ),
            self::SERVICE_PAC_CONTRATO_AGENCIA_PAGAMENTO_NA_ENTREGA_LM   => array(
                'PAC Contrato Agencia Pagamento na Entrega (Liminar ABCOMM)',
                null
            ),

            self::SERVICE_SEDEX_CONTRATO_AGENCIA_TA => array('SEDEX Contrato Agencia TA', 161274),
            self::SERVICE_PAC_CONTRATO_AGENCIA_TA   => array('PAC Contrato Agencia TA', 161277),
        );

    /**
     * @var string
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
        $normalizedServiceCode = sprintf("%'05s", $serviceCode);

        if (!isset(self::$services[$normalizedServiceCode])) {
            throw new Exception('There is no service with the code "' . $serviceCode . '".');
        }

        $service = self::$services[$normalizedServiceCode];
        parent::__construct(
            array(
                'codigo'    => $normalizedServiceCode,
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
     * @return string
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

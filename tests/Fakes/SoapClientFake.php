<?php

namespace PhpSigep\Test\Fakes;

use PhpSigep\Config;

class SoapClientFake extends \SoapClient
{
    /**
     * @var array
     */
    private $soapArgs = [];

    private $retornos = [];

    public function __construct()
    {
        parent::__construct(Config::WSDL_ATENDE_CLIENTE_DEVELOPMENT);
    }

    public function solicitaEtiquetas(array $soapArgs): \stdClass
    {
        $this->soapArgs = $soapArgs;
        $qtdEtiquetas = $soapArgs['qtdEtiquetas'];

        $codigos[] = 'SI0000000001BR';
        $codigos[] = 'SI' . sprintf('%010d', $qtdEtiquetas) . 'BR';

        return (object)['return' => implode(',', $codigos)];
    }

    public function fechaPlpVariosServicos(array $soapArgs): \stdClass
    {
        $this->soapArgs = $soapArgs;
        return $this->retornos['fechaPlpVariosServicos'] ?? (object)['return' => '20563504'];
    }

    public function setRetornoFechaPlpVariosServicos(\stdClass $retorno)
    {
        $this->retornos['fechaPlpVariosServicos'] = $retorno;
    }

    public function getSoapArgs(): array
    {
        return $this->soapArgs;
    }

    public function buscaCliente(array $soapArgs): \stdClass
    {
        return (object)[
            'return' =>
                (object)[
                    'cnpj' => '34028316000103      ',
                    'contratos' =>
                        (object)[
                            'cartoesPostagem' =>
                                (object)[
                                    'codigoAdministrativo' => '17000190  ',
                                    'dataAtualizacao' => '2022-03-04T16:19:40-03:00',
                                    'dataVigenciaFim' => '2040-12-31T00:00:00-03:00',
                                    'dataVigenciaInicio' => '2017-04-26T00:00:00-03:00',
                                    'datajAtualizacao' => 122063,
                                    'datajVigenciaFim' => 140366,
                                    'datajVigenciaInicio' => 117116,
                                    'horajAtualizacao' => 161940,
                                    'numero' => '0067599079',
                                    'servicos' =>
                                        [
                                            0 =>
                                                (object)[
                                                    'codigo' => '40215                    ',
                                                    'dataAtualizacao' => '2022-03-09T14:58:56-03:00',
                                                    'datajAtualizacao' => 122068,
                                                    'descricao' => 'SEDEX 10 A FATURAR            ',
                                                    'horajAtualizacao' => 145856,
                                                    'id' => 104707,
                                                    'servicoSigep' =>
                                                        (object)[
                                                            'categoriaServico' => 'SEDEX',
                                                            'dataAtualizacao' => '2019-06-03T00:00:00-03:00',
                                                            'descricao' => 'Sedex 10',
                                                            'id' => 9193,
                                                        ],
                                                    'exigeDimensoes' => false,
                                                    'exigeValorCobrar' => false,
                                                    'imitm' => 104707,
                                                    'pagamentoEntrega' => 'N',
                                                    'remessaAgrupada' => 'N',
                                                    'restricao' => 'S',
                                                    'servico' => 104707,
                                                    'ssiCoCodigoPostal' => '244',
                                                ],
                                            'servicosAdicionais' =>
                                                (object)[
                                                ],
                                            'tipo1Codigo' => 'CNV',
                                            'tipo2Codigo' => 'A',
                                            'vigencia' =>
                                                (object)[
                                                    'dataFinal' => '2040-12-31T00:00:00-03:00',
                                                    'dataInicial' => '2001-06-11T00:00:00-03:00',
                                                    'datajFim' => 140366,
                                                    'datajIni' => 101162,
                                                    'id' => 104707,
                                                ],
                                        ],
                                    1 =>
                                        (object)[
                                            'codigo' => '40290                    ',
                                            'dataAtualizacao' => '2022-03-09T15:01:43-03:00',
                                            'datajAtualizacao' => 122068,
                                            'descricao' => 'SEDEX HOJE A FATURAR          ',
                                            'horajAtualizacao' => 150143,
                                            'id' => 108934,
                                            'servicoSigep' =>
                                                (object)[
                                                    'categoriaServico' => 'SEDEX',
                                                    'chancela' =>
                                                        (object)[
                                                            'dataAtualizacao' => '2019-06-03T00:00:00-03:00',
                                                            'descricao' => 'Sedex Hoje',
                                                            'id' => 9196,
                                                        ],
                                                    'descricao' => 'SEDEX HOJE                    ',
                                                    'exigeDimensoes' => false,
                                                    'exigeValorCobrar' => false,
                                                    'imitm' => 108934,
                                                    'pagamentoEntrega' => 'N',
                                                    'remessaAgrupada' => 'N',
                                                    'restricao' => 'S',
                                                    'servico' => 108934,
                                                    'ssiCoCodigoPostal' => '244',
                                                ],
                                            'servicosAdicionais' =>
                                                (object)[
                                                ],
                                            'tipo1Codigo' => 'CNV',
                                            'tipo2Codigo' => 'A',
                                            'vigencia' =>
                                                (object)[
                                                    'dataFinal' => '2040-12-31T00:00:00-03:00',
                                                    'dataInicial' => '2004-11-04T00:00:00-02:00',
                                                    'datajFim' => 140366,
                                                    'datajIni' => 104309,
                                                    'id' => 108934,
                                                ],
                                        ],
                                ],
                        ],
                ],
        ];
    }
}
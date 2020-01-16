<?php
namespace PhpSigep\Services\SoapClient;


use PhpSigep\Model\Etiqueta;
use PhpSigep\Model\ServicoAdicional;
use string;

class Simulador implements SoapClientInterface
{

    /**
     * @param \PhpSigep\Model\VerificaDisponibilidadeServico $params
     *
     * @return bool
     */
    public function verificaDisponibilidadeServico(\PhpSigep\Model\VerificaDisponibilidadeServico $params)
    {
        return true;
    }

    /**
     * @param \PhpSigep\Model\SolicitaEtiquetas $params
     *
     * @throws \SoapFault
     * @throws \Exception
     * @return Etiqueta[]
     */
    public function solicitaEtiquetas(\PhpSigep\Model\SolicitaEtiquetas $params)
    {
        $qtdEtiquetas = $params->getQtdEtiquetas();
        $etiquetas    = array();
        for ($i = 0; $i < $qtdEtiquetas; $i++) {
            $etiqueta = new \PhpSigep\Model\Etiqueta();
            $etiqueta->setEtiquetaSemDv('SI' . $i . mt_rand(10000000, 99999999) . 'BR');

            $etiquetas[] = $etiqueta;
        }

        return $etiquetas;
    }

    /**
     * @param \PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params
     *
     * @throws \SoapFault
     * @throws \Exception
     * @return Etiqueta[]
     */
    public function geraDigitoVerificadorEtiquetas(\PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params)
    {
        $etiquetas = $params->getEtiquetas();
        foreach ($etiquetas as $etiqueta) {
            $etiqueta->setDv($etiqueta->getDv());
        }

        return $etiquetas;
    }

    public function fechaPlpVariosServicos(\PhpSigep\Model\PreListaDePostagem $params, \XMLWriter $xmlDaPreLista)
    {
        return time();
    }

    /**
     * @param \PhpSigep\Model\CalcPrecoPrazo $params
     * @return \PhpSigep\Model\CalcPrecoPrazoRespostaIterator
     */
    public function calcPrecoPrazo(\PhpSigep\Model\CalcPrecoPrazo $params)
    {
        $retorno  = array();
        $servicos = $params->getServicosPostagem();
        foreach ($servicos as $servico) {
            $valorFrete            = mt_rand(30, 1000) / 10; // Valores entre 3 e 100
            $valorMaoPropria       = 0;
            $valorAvisoRecebimento = 0;
            $valorValorDeclarado   = 0;

            $servicoAdicionais = $params->getServicosAdicionais();
            foreach ($servicoAdicionais as $servicoAdicional) {
                if ($servicoAdicional->is(ServicoAdicional::SERVICE_AVISO_DE_RECEBIMENTO)) {
                    $valorAvisoRecebimento = mt_rand(1, 50) / 10; // Valores entre 0.1 e 5
                } else {
                    if ($servicoAdicional->is(ServicoAdicional::SERVICE_MAO_PROPRIA)) {
                        $valorMaoPropria = mt_rand(1, 50) / 10; // Valores entre 0.1 e 5
                    } else {
                        if ($servicoAdicional->is(ServicoAdicional::SERVICE_VALOR_DECLARADO_SEDEX) || $servicoAdicional->is(ServicoAdicional::SERVICE_VALOR_DECLARADO_PAC)) {
                            $valorValorDeclarado = $servicoAdicional->getValorDeclarado(
                                ) * 1 / 100; //1% do valor declarado
                        }
                    }
                }
            }

            $valorMaoPropria       = round($valorMaoPropria, 2);
            $valorAvisoRecebimento = round($valorAvisoRecebimento, 2);
            $valorValorDeclarado   = round($valorValorDeclarado, 2);
            $valorFrete            = round($valorFrete, 2);
            $total                 = $valorFrete + $valorValorDeclarado + $valorMaoPropria + $valorAvisoRecebimento;

            $item      = new \PhpSigep\Model\CalcPrecoPrazoResposta(array(
                                                                        'servico'               => $servico,
                                                                        'valor'                 => $total,
                                                                        'prazoEntrega'          => mt_rand(1, 9),
                                                                        'valorMaoPropria'       => $valorMaoPropria,
                                                                        'valorAvisoRecebimento' => $valorAvisoRecebimento,
                                                                        'valorValorDeclarado'   => $valorValorDeclarado,
                                                                        'entregaDomiciliar'     => true,
                                                                        'entregaSabado'         => true,
                                                                    ));
            $retorno[] = $item;
        }

        return new \PhpSigep\Model\CalcPrecoPrazoRespostaIterator($retorno);
    }

    /**
     * @todo tratar o retorno
     *
     * @param \PhpSigep\Model\AccessData $params
     * @return mixed
     */
    public function buscaCliente(\PhpSigep\Model\AccessData $params)
    {
        return new \stdClass();
    }


} 
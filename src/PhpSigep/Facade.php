<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */
 
namespace PhpSigep;

use PhpSigep\Model\Etiqueta;
use PhpSigep\Services\SoapClient\SoapClientInterface;
use string;

class Facade implements SoapClientInterface
{

    /**
     * @param \PhpSigep\Model\VerificaDisponibilidadeServico $params
     *
     * @return bool
     */
    public function verificaDisponibilidadeServico(\PhpSigep\Model\VerificaDisponibilidadeServico $params)
    {
        // TODO: Implement verificaDisponibilidadeServico() method.
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
        $service = new Services\SolicitaEtiquetas();
        return $service->execute($params);
    }

    /**
     * @param \PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params
     *
     * @throws \SoapFault
     * @throws \Exception
     * @return string[]
     */
    public function geraDigitoVerificadorEtiquetas(\PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params)
    {
        // TODO: Implement geraDigitoVerificadorEtiquetas() method.
    }

    public function fechaPlpVariosServicos(\PhpSigep\Model\PreListaDePostagem $params, \XMLWriter $xmlDaPreLista)
    {
        // TODO: Implement fechaPlpVariosServicos() method.
    }

    /**
     * @param \PhpSigep\Model\CalcPrecoPrazo $params
     * @return \PhpSigep\Model\CalcPrecoPrazoRespostaIterator
     */
    public function calcPrecoPrazo(\PhpSigep\Model\CalcPrecoPrazo $params)
    {
        // TODO: Implement calcPrecoPrazo() method.
    }

    /**
     * @todo documentar o retorno
     *
     * @param \PhpSigep\Model\AccessData $params
     * @return mixed
     */
    public function buscaCliente(\PhpSigep\Model\AccessData $params)
    {
        // TODO: Implement buscaCliente() method.
    }
} 
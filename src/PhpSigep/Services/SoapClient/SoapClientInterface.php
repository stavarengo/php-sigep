<?php
namespace PhpSigep\Services\SoapClient;

use PhpSigep\Model\Etiqueta;

/**
 * @author: Stavarengo
 */
interface SoapClientInterface
{

    /**
     * @param \PhpSigep\Model\VerificaDisponibilidadeServico $params
     *
     * @return bool
     */
    public function verificaDisponibilidadeServico(\PhpSigep\Model\VerificaDisponibilidadeServico $params);

    /**
     * @param \PhpSigep\Model\SolicitaEtiquetas $params
     *
     * @throws \SoapFault
     * @throws \Exception
     * @return Etiqueta[]
     */
    public function solicitaEtiquetas(\PhpSigep\Model\SolicitaEtiquetas $params);

    /**
     * @param \PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params
     *
     * @throws \SoapFault
     * @throws \Exception
     * @return string[]
     */
    public function geraDigitoVerificadorEtiquetas(\PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params);

    public function fechaPlpVariosServicos(\PhpSigep\Model\PreListaDePostagem $params, \XMLWriter $xmlDaPreLista);

    public function calcPrecoPrazo(\PhpSigep\Model\CalcPrecoPrazo $params);
}

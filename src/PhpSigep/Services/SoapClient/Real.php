<?php
namespace PhpSigep\Services\SoapClient;

use PhpSigep\Model\BuscaClienteResult;
use PhpSigep\Model\Etiqueta;
use PhpSigep\Services\Real as ServiceImplementation;
use PhpSigep\Services\Result;
use PhpSigep\Services\ServiceInterface;

/**
 * @author: Stavarengo
 */
class Real implements ServiceInterface
{
    private static $calcPrecosPrazosServiceUnavailable = false;

    /**
     * @param \PhpSigep\Model\VerificaDisponibilidadeServico $params
     *
     * @return bool
     */
    public function verificaDisponibilidadeServico(\PhpSigep\Model\VerificaDisponibilidadeServico $params)
    {
        $service = new ServiceImplementation\VerificaDisponibilidadeServico();
        return $service->execute($params);
    }

    /**
     * @param $cep
     *
     * @return Result<\PhpSigep\Model\ConsultaCepResposta>
     */
    public function consultaCep($cep)
    {
        $service = new ServiceImplementation\ConsultaCep();
        return $service->execute($cep);
    }

    /**
     * @param \PhpSigep\Model\SolicitaEtiquetas $params
     *
     * @return Etiqueta[]
     */
    public function solicitaEtiquetas(\PhpSigep\Model\SolicitaEtiquetas $params)
    {
        $service = new ServiceImplementation\SolicitaEtiquetas();
        return $service->execute($params);
    }

    /**
     * Pede para o WebService do Correios calcular o dígito verificador de uma etiqueta.
     * 
     * Se preferir você pode usar o método {@linnk \PhpSigep\Model\Etiqueta::getDv() } para calcular o dígito 
     * verificador, visto que esse método é mais rádido pois faz o cálculo local sem precisar se comunicar com o
     * WebService.
     * 
     * @param \PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params
     *
     * @throws \SoapFault
     * @throws \Exception
     * @return Result
     */
    public function geraDigitoVerificadorEtiquetas(\PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params)
    {
        $service = new ServiceImplementation\GeraDigitoVerificadorEtiquetas();
        return $service->execute($params);
    }

    public function fechaPlpVariosServicos(\PhpSigep\Model\PreListaDePostagem $params)
    {
        $service = new ServiceImplementation\FecharPreListaDePostagem();
        return $service->execute($params);
    }

    /**
     * @param \PhpSigep\Model\CalcPrecoPrazo $params
     * @return \PhpSigep\Model\CalcPrecoPrazoRespostaIterator
     * @throws Exception
     * @throws \Exception
     */
    public function calcPrecoPrazo(\PhpSigep\Model\CalcPrecoPrazo $params)
    {
        $service = new ServiceImplementation\CalcPrecoPrazo();
        return $service->execute($params);
    }

    /**
     * @todo tratar o retorno
     *
     * @param \PhpSigep\Model\AccessData $params
     * @return Result
     */
    public function buscaCliente(\PhpSigep\Model\AccessData $params)
    {
        $service = new ServiceImplementation\BuscaCliente();
        return $service->execute($params);
    }
}

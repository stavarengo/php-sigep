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
     * @return Result<\PhpSigep\Model\VerificaDisponibilidadeServicoResposta>
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
     * @return Result<\PhpSigep\Model\Etiqueta[]>
     */
    public function solicitaEtiquetas(\PhpSigep\Model\SolicitaEtiquetas $params)
    {
        $service = new ServiceImplementation\SolicitaEtiquetas();
        return $service->execute($params);
    }

    /**
     * @param integer $idPlpMaster número da PLP
     *
     * @return Result<\PhpSigep\Model\solicitaXmlPlp[]>
     */
    public function solicitaXmlPlp($idPlpMaster)
    {
        $service = new ServiceImplementation\SolicitaXmlPlp();

        return $service->execute($idPlpMaster);
    }

    /**
     * Pede para o WebService do Correios calcular o dígito verificador de uma etiqueta.
     *
     * Se preferir você pode usar o método {@linnk \PhpSigep\Model\Etiqueta::getDv() } para calcular o dígito
     * verificador, visto que esse método é mais rápido pois faz o cálculo local sem precisar se comunicar com o
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

    /**
     * @param \PhpSigep\Model\PreListaDePostagem $params
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\FechaPlpVariosServicosRetorno>
     */
    public function fechaPlpVariosServicos(\PhpSigep\Model\PreListaDePostagem $params)
    {
        $service = new ServiceImplementation\FecharPreListaDePostagem();
        return $service->execute($params);
    }

    /**
     * @param \PhpSigep\Model\CalcPrecoPrazo $params
     * @return Result<\PhpSigep\Model\CalcPrecoPrazoResposta[]>
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

    /**
     *
     * @param \PhpSigep\Model\RastrearObjeto $params
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\RastrearObjetoResultado[]>
     */
    public function rastrearObjeto(\PhpSigep\Model\RastrearObjeto $params)
    {
        $service = new ServiceImplementation\RastrearObjeto();
        return $service->execute($params);
    }

    /**
     * @param $numeroCartaoPostagem
     * @param $login
     * @param $senha
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\verificarStatusCartaoPostagemResposta[]>
     */
    public function verificarStatusCartaoPostagem($numeroCartaoPostagem, $usuario, $senha)
    {
        $service = new ServiceImplementation\VerificarStatusCartaoPostagem();
        return $service->execute($numeroCartaoPostagem, $usuario, $senha);
    }

    /**
     * Pede para o WebService do Correios suspender a entrega de uma encomenda ao destinatário
     * @param $numeroEtiqueta
     * @param $idPlp
     * @param $usuario
     * @param $senha
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\BloquearObjetoResposta[]>
     */

    public function bloquearObjeto($numeroEtiqueta, $idPlp, $usuario, $senha)
    {
        $service = new ServiceImplementation\BloquearObjeto();
        return $service->execute($numeroEtiqueta, $idPlp, $usuario, $senha);
    }

}

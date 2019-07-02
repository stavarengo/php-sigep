<?php
namespace PhpSigep\Services;

/**
 * @author: Stavarengo
 */
interface ServiceInterface
{

    /**
     * @param \PhpSigep\Model\VerificaDisponibilidadeServico $params
     *
     * @return Result<\PhpSigep\Model\VerificaDisponibilidadeServicoResposta>
     */
    public function verificaDisponibilidadeServico(\PhpSigep\Model\VerificaDisponibilidadeServico $params);

    /**
     * @param $cep
     *
     * @return Result<\PhpSigep\Model\ConsultaCepResposta>
     */
    public function consultaCep($cep);

    /**
     * @param \PhpSigep\Model\SolicitaEtiquetas $params
     *
     * @return \PhpSigep\Model\Etiqueta[]
     */
    public function solicitaEtiquetas(\PhpSigep\Model\SolicitaEtiquetas $params);

    /**
     * Pede para o WebService do Correios calcular o dígito verificador de uma etiqueta.
     *
     * Se preferir você pode usar o método {@linnk \PhpSigep\Model\Etiqueta::getDv() } para calcular o dígito
     * verificador, visto que esse método é mais rádido pois faz o cálculo local sem precisar se comunicar com o
     * WebService.
     *
     * @param \PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params
     *
     * @return string[]
     */
    public function geraDigitoVerificadorEtiquetas(\PhpSigep\Model\GeraDigitoVerificadorEtiquetas $params);

    /**
     * @param \PhpSigep\Model\PreListaDePostagem $params
     * @param \XMLWriter $xmlDaPreLista
     * @return mixed
     */
    public function fechaPlpVariosServicos(\PhpSigep\Model\PreListaDePostagem $params);

    /**
     * @param \PhpSigep\Model\CalcPrecoPrazo $params
     * @return \PhpSigep\Model\CalcPrecoPrazoRespostaIterator
     */
    public function calcPrecoPrazo(\PhpSigep\Model\CalcPrecoPrazo $params);

    /**
     * @todo documentar o retorno
     *
     * @param \PhpSigep\Model\AccessData $params
     * @return mixed
     */
    public function buscaCliente(\PhpSigep\Model\AccessData $params);

    /**
     *
     * @param \PhpSigep\Model\RastrearObjeto $params
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\RastrearObjetoResultado[]>
     */
    public function rastrearObjeto(\PhpSigep\Model\RastrearObjeto $params);

    /**
     * @param $numeroCartaoPostagem
     * @param $login
     * @param $senha
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\verificarStatusCartaoPostagemResposta[]>
     */
    public function verificarStatusCartaoPostagem($numeroCartaoPostagem, $usuario, $senha);


    /**
     * @param $numeroEtiqueta
     * @param $idPlp
     * @param $usuario
     * @param $senha
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\BloquearObjetoResposta[]>
     */
    public function bloquearObjeto($numeroEtiqueta, $idPlp, $usuario, $senha);
    

    /**
     * @param $numeroEtiqueta
     * @param $idPlp
     * @param $usuario
     * @param $senha
     * @return \PhpSigep\Services\Result<\PhpSigep\Model\CancelarObjetoResposta[]>
     */
    public function cancelarObjeto($numeroEtiqueta, $idPlp, $usuario, $senha);    

}

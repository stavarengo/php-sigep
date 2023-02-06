<?php

namespace PhpSigep\Model;

use PhpSigep\InvalidArgument;

/**
 * @author: Rodrigo Job (rodrigo at econector.com.br)
 */
/*
 * baseado no documento:
 * LOGÍSTICA REVERSA
 * Manual de Implementação de Web Service de Logística Reversa
 * Revisão: Maio/2016

 * Método: solicitarPostagemReversa()
 */
class LogisticaReversaPedido extends AbstractModel {

    /**
     * Opcional.
     * Quando não informado será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     * @var AccessData
     */
    protected $accessData;

    /**
     * @var string
     */
    protected $msg_erro;

    /**
     * @var string
     */
    protected $cod_erro;

    /**
     * codAdministrativo:
     * Código Administrativo do cliente.
     * Numérico (8)
     * Sim
     * @var string
     */
    protected $codAdministrativo;

    /**
     * codigo_servico:
     * Código do serviço que será utilizado. O código será fornecido pela ECT.
     * Numérico(5)
     * Sim
     * @var string
     */
    protected $codigo_servico;
    /**
     * Qtd de Dias:
     * Qtd de dias de vencimento da autorização
     * Numérico(5)
     * Sim
     * @var string
     */

    protected $qtdeDias;

    /**
     * cartao:
     * Número do cartão de postagem do cliente que será usado para a cobrança das taxas do serviço realizado.
     * Numérico(10)
     * Sim
     * @var string
     */
    protected $cartao;

    /**
     * destinatario:
     * Sub-tags que armazenam dados do destinatário.
     * Sub-Tags
     * Sim
     * @var array
     */
    protected $destinatario;

    /**
     * coleta_solicitadas:
     * Descrição / Observações
     * Tipo Dados
     * Obrigatório
     * @var array
     */
    protected $coletas_solicitadas;

    /**
     * @return \PhpSigep\Model\AccessData
     */
    public function getAccessData() {
        return $this->accessData;
    }

    /**
     * @param \PhpSigep\Model\AccessData $accessData
     *      Opcional.
     *      Quando null será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     */
    public function setAccessData(AccessData $accessData) {
        $this->accessData = $accessData;
    }

    /**
     * @param string msg_erro
     */
    public function setMsgErro($msgErro) {
        $this->msg_erro = $msgErro;
    }

    /**
     * @return string
     */
    public function getErrorMsg() {
        return $this->msg_erro;
    }

    /**
     * @param string cod_erro
     */
    public function setErrorCode($codErro) {
        $this->cod_erro = $codErro;
    }

    /**
     * @return string
     */
    public function getCodErro() {
        return $this->cod_erro;
    }

    /**
     * @param string
     */
    public function setCodAdministrativo($codAdministrativo) {
        $this->codAdministrativo = $codAdministrativo;
    }

    /**
     * @param string
     */
    public function setCodigoServico($codigoServico) {
        $this->codigo_servico = $codigoServico;
    }

    /**
     * @param string
     */
    public function setCartao($cartao) {
        $this->cartao = $cartao;
    }


    /**
     * @param \PhpSigep\Model\LogisticaReversaDestinatario[] $destinatario
     * @throws \PhpSigep\InvalidArgument
     */
    public function setDestinatario($destinatario)
    {

        $this->destinatario = $destinatario;

    }

    /**
     * @param \PhpSigep\Model\LogisticaReversaColeta[] $coleta
     * @throws \PhpSigep\InvalidArgument
     */
    public function setColetasSolicitadas($coleta)
    {

        $this->coletas_solicitadas = $coleta;

    }


    /**
     *  @param string qtdeDias
     */
    public function setQtddeDias($qtdeDias)
    {
        $this->qtdeDias= $qtdeDias;
    }

    /**
     * @return string
     */
    public function getQtdeDias()
    {
        return $this->qtdeDias;
    }

    /**
     * @param \PhpSigep\Model\LogisticaReversaPedido[] $pedido
     * @throws \PhpSigep\InvalidArgument
     */
    public function getDados()
    {

        return array(
            "codAdministrativo" => $this->codAdministrativo,
            "cartao" => $this->cartao,
            "codigo_servico" => $this->codigo_servico,
            "destinatario" => $this->destinatario,
            "coletas_solicitadas" => $this->coletas_solicitadas,
	);
    }

}

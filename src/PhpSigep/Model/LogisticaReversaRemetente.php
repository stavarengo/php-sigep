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
 *
 * remetente
 * Sub-tags que armazenam dados do remetente da coleta ou autorização de postagem. 
 * - 
 * Sim
 * 
 */
class LogisticaReversaRemetente extends AbstractModel {

    /**
     * @var string
     */
    protected $msg_erro;

    /**
     * @var string
     */
    protected $cod_erro;

    /**
     * nome:
     * Nome do remetente
     * Caractere(60)
     * Sim
     * @var string
     */
    protected $nome;

    /**
     * logradouro
     * Logradouro do remetente
     * Caractere(72)
     * Sim
     * @var string
     */
    protected $logradouro;

    /**
     * numero:
     * Número do endereço do remetente. Caso não possua, preencher com “S/N” 
     * Caractere(8)
     * Sim
     * @var string
     */
    protected $numero;

    /**
     * complemento:
     * Complemento do endereço
     * Caractere(30)
     * Não
     * @var string
     */
    protected $complemento;

    /**
     * bairro:
     * Bairro do remetente
     * Caractere(80)
     * Não 
     * @var string
     */
    protected $bairro;

    /**
     * referencia:
     * Uma referência do endereço do remetente
     * Caractere(60)
     * Não 	
     * @var string
     */
    protected $referencia;

    /**
     * cidade:
     * Cidade do remetente Caractere(40)
     * Sim
     * @var string
     */
    protected $cidade;

    /**
     * uf:
     * UF do remetente
     * Caractere(2) 
     * Sim
     * @var string
     */
    protected $UF;

    /**
     * cep:
     * Cep do remetente sempre com 8 posições. Exemplo: 01200999
     * Caractere(8)
     * Sim
     * @var string
     */
    protected $CEP;

    /**ddd:
     * Código do DDD do remetente 
     * Caractere(3) 
     * Sim 
     * @var string
     */
    protected $DDD;

    /**
     * telefone:
     * Número do telefone do remetente. Este campo é obrigatório. É importante para que a ECT entre em contato com o remetente em casos de insucesso na coleta.
     * Caractere(18)
     * Sim
     * @var string
     */
    protected $telefone;

    /**
     * email
     * E-mail do remetente. Caso seja preenchido o remetente receberá um e-mail informando sobre a coleta ou a autorização de postagem. 
     * Caractere(72)
     * Sim 
     * @var string
     */
    protected $email;

    /**
     * celular:
     * Número do telefone celular do rementente 
     * Caractere(9) 
     * Não
     * @var string
     */
    protected $celular;

    /**
     * ddd_celular:
     * Código do DDD do celular do remetente 
     * Caractere(3) ATENÇÂO: até a Revisão: Maio/2016, o sistema só aceita 2 caracteres
     * Não
     * @var string
     */
    protected $dddCelular;

    /**
     * sms:
     * Caso deseja receber SMS dos status do objeto (S = Sim, N = Não) 
     * Caractere(1) 
     * Não
     * @var string
     */
    protected $sms;

    /**
     * identificacao
     * Número do CNPJ ou CPF do remetente. 
     * Caractere(14) 
     * Não
     * @var string
     */
    protected $identificacao;

    /**
     * @var array
     */
    protected $dados;

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
    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * @param string
     */
    public function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
    }

    /**
     * @param string
     */
    public function setNumero($numero) {
        $this->numero = $numero;
    }

    /**
     * @param string
     */
    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    /**
     * @param string
     */
    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    /**
     * @param string
     */
    public function setReferencia($referencia) {
        $this->referencia = $referencia;
    }

    /**
     * @param string
     */
    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    /**
     * @param string
     */
    public function setUF($UF) {
        $this->UF = $UF;
    }

    /**
     * @param string
     */
    public function setCEP($CEP) {
        $this->CEP = $CEP;
    }

    /**
     * @param string
     */
    public function setDDD($DDD) {
        $this->DDD = $DDD;
    }

    /**
     * @param string
     */
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    /**
     * @param string
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @param string
     */
    public function setCelular($celular) {
        $this->celular = $celular;
    }

    /**
     * @param string
     */
    public function setDDDCelular($ddd) {
        $this->dddCelular = $ddd;
    }

    /**
     * @param string
     */
    public function setSMS($sms) {
        $this->sms = $sms;
    }

    /**
     * @param string
     */
    public function setIdentificacao($identificacao) {
        $this->identificacao = $identificacao;
    }

    public function getDados() {

        $this->dados = array(
            "nome" => $this->nome,
            "logradouro" => $this->logradouro,
            "numero" => $this->numero,
            "complemento" => $this->complemento,
            "bairro" => $this->bairro,
            "referencia" => $this->referencia,
            "cidade" => $this->cidade,
            "uf" => $this->UF,
            "identificacao" => $this->identificacao,
            "email" => $this->email,
            "cep" => $this->CEP,
            "ddd" => $this->DDD,
            "telefone" => $this->telefone,
            "ddd_celular" => $this->dddCelular,
            "celular" => $this->celular,
            "sms" => $this->sms,
        );
        return $this->dados;
    }

}

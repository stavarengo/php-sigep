<?php

namespace PhpSigep\Model;

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

class LogisticaReversaColeta extends AbstractModel {

    /**
     * @var string
     */
    protected $msg_erro;

    /**
     * @var string
     */
    protected $cod_erro;

    /**
     * coleta_solicitadas:
     *  Descrição / Observações 
     * Tipo Dados 
     * Obrigatório
     * @var array
     */
    protected $coleta;

    /**
     * tipo: 
     * Indica se a solicitação é de coleta domiciliária ou uma autorização de postagem. CA = Coleta domiciliar. Caso não exista coleta domiciliar na localidade o sistema transforma automaticamente o pedido em uma autorização de postagem. C = Coleta domiciliária. Caso não exista a coleta no local indicado, o sistema não processa a solicitação. A = Autorização de Postagem Caso nenhum valor seja passado nessa tag, o sistema entende que é uma solicitação de autorização de postagem. 
     * Caractere(2) 
     * Sim
     *  
     * @var string
     */
    protected $tipo;

    /**
     * numero:
     * Número da Autorização de Postagem. Usado quando o cliente já possui uma faixa numérica desse tipo de solicitação (Range). 
     * Numérico (9) 
     * Não
     * @var string
     */
    protected $numero;

    /**
     * id_cliente:
     * Campo chave que identifica cada solicitação do cliente. Poderá ser informado por exemplo o número da NF, OS, etc, desde que não se repita em mais de uma solicitação. 
     * Caractere (30) 
     * Não
     * @var string
     */
    protected $idCliente;

    /**
     * ag:
     * Coleta domiciliar: Data para agendamento da coleta. Se informado o pedido fica retido no sistema e a primeira tentativa de coleta é feita apenas na data informada. O sistema aceita apenas datas com mais de cinco dias corridos a partir da data de processamento do pedido. Autorização de Postagem: Indica a quantidade de dias de validade da autorização. A validade deve ser de no mínimo 5 e no máximo 90 dias. Se não for informada, a validade da autorização será de 10 (dez) dias corridos a partir da data do processamento do pedido. Data DD/MM/YYYY 
     * Numérico(2) Entre 5 e 60 dias. 
     * Não
     * @var string
     */
    protected $ag;

    /**
     * cartao:
     * Número do cartão de postagem para ser usado no faturamento dos serviço utilizados. 
     * Numérico(10) 
     * Não
     * @var string
     */
    protected $cartao;

    /**
     * valor_declarado:
     * Valor declarado dos objetos da coleta. Exemplo: 1020.70. 
     * Numérico(9.2) 
     * Não
     * @var Float
     */
    protected $valorDeclarado;

    /**
     * servico_adicional:
     * Códigos de serviços adicionais separados por vírgula. 
     * Caractere(20) 
     * Não
     * @var string
     */
    protected $servicoAdicional;

    /**	
    * descricao:
    *  Descrição / instruções para coleta. 
    * Caractere(255) 
    * Não
    * @var string
    */
    protected $descricao;

    /**
     * ar:
     * Indica se é para solicitar Aviso de Recebimento para as encomendas cadastradas. Usado apenas para pedidos de Autorização de Postagem. 
     * Booleano Informar 1 ou 0 
     * Não
     * @var string
     */
    protected $AR;

    /**
     * cklist:
     * Indica que serão impressas vias de checklist. Apenas clientes previamente habilitados podem utilizar essa opção. Código fornecido pela ECT. 
     * Valor fixo 
     * Não   
     * @var string
     */
    protected $ckList;

    /**
     * documento:
     * Obrigatório informar se o tipo do check list for documento. Lista de códigos. Será fornecido pela ECT 
     * Não
     * @var string
     */
    protected $documento;

    /**
     * remetente:
     * Sub-tags que armazenam dados do remetente da coleta ou autorização de postagem. 
     * - 
     * Sim
     * @var array
     */
    protected $Remetente;

    /**
     * obj_col:
     * Contém os blocos de tags que cadastram os objetos que serão coletados (coleta domiciliar) ou postados (autorização de postagem). Caso esta tag não seja encontrada o sistema assume que existe apenas um objeto a ser coletado ou postado. 
     * - 
     * Sim 
     * @var array
     */
    protected $objCol;

    /**
     * produto:
     * Contém os blocos de tags que são utilizadas para solicitação de produtos junto com a coleta. O contrato do cliente deve ser habilitado previamente para usar essa tag. 
     * - 
     * Não
     * @var array
     */
    protected $produto;

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
    public function setTipo($tipo) {
        $this->tipo = $tipo;
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
    public function setIdCliente($idCliente) {
        $this->idCliente = $idCliente;
    }

    /**
     * @param string
     */
    public function setAg($ag) {
        $this->ag = $ag;
    }

    /**
     * @param string
     */
    public function setCartao($cartao) {
        $this->cartao = $cartao;
    }

    /**
     * @param string
     */
    public function setValorDeclarado($valorDeclarado) {
        $this->valorDeclarado = number_format((float)$valorDeclarado, 2);
    }

    /**
     * @param string
     */
    public function setServicoAdicional($servicoAdicional) {
        $this->servicoAdicional = $servicoAdicional;
    }

    /**
     * @param string
     */
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    /**
     * @param string
     */
    public function setAR($AR) {
        $this->AR = $AR;
    }

    /**
     * @param string
     */
    public function setCklist($cklist) {
        $this->cklist = $cklist;
    }

    /**
     * @param string
     */
    public function setDocumento($documento) {
        $this->documento = $documento;
    }

    /**
     * @param \PhpSigep\Model\LogisticaReversaRemetente $remetente
     *      
     */
    public function setRemetente(LogisticaReversaRemetente $remetente) {
        $this->remetente = $remetente;
    }

    /**
     * @param \PhpSigep\Model\LogisticaReversaObjeto[] $objeto
     * @throws \PhpSigep\InvalidArgument
     */
    public function setObjCol(LogisticaReversaObjeto $objeto)
    {
        
        $this->objCol = $objeto;
		
    }

    /**
     * @param \PhpSigep\Model\LogisticaReversaProduto $produto
     *      
     */
    public function setProduto(LogisticaReversaProduto $produto) {
        $this->produto = $produto;
    }

    /**
     * @param $coleta
     *      
     */
    
    public function getDados() {

        $this->coleta = array(
            "tipo"      => $this->tipo,
            "numero"    => $this->numero,
            "id_cliente" => $this->idCliente,
            "ag"        => $this->ag,
            "cartao"    => $this->cartao,
            "valor_declarado"   => $this->valorDeclarado,
            "servico_adicional" => $this->servicoAdicional,
            "descricao" => $this->descricao,
            "ar"        => $this->AR,
            "cklist"    => $this->ckList,
            "documento" => $this->documento,
            "remetente" => $this->remetente->getDados(),
            "obj_col"    => $this->objCol->getDados(),
            "produto"   => $this->produto->getDados()
        );
        return $this->coleta;
    }

}

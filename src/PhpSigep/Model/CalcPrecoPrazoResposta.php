<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class CalcPrecoPrazoResposta extends AbstractModel
{

    /**
     * Código do serviço de entrega.
     * @var ServicoDePostagem
     */
    protected $servico;

    /**
     * Preço total da encomenda, em Reais, incluindo os preços dos serviços opcionais.
     * @var float
     */
    protected $valor;

    /**
     * Prazo estimado em dias para entrega do produto.
     * Se o valor retornado for 0 (zero), indica que o web service do Correios não retornou o prazo para esta entrega.
     * @var int
     */
    protected $prazoEntrega;

    /**
     * Preço do serviço adicional Mão Própria.
     * @var float
     */
    protected $valorMaoPropria;

    /**
     * Preço do serviço adicional Aviso de Recebimento.
     * @var float
     */
    protected $valorAvisoRecebimento;

    /**
     * Preço do serviço adicional Valor Declarado.
     * @var float
     */
    protected $valorValorDeclarado;

    /**
     * Informa se a localidade informada possui entrega domiciliária.
     * Se o prazo não for retornado corretamente, o retorno deste parâmetro será vazio.
     * @var  bool
     */
    protected $entregaDomiciliar;

    /**
     * Informa se a localidade informada possui entrega domiciliária aos sábados.
     * Se o prazo não for retornado corretamente, o retorno deste parâmetro será vazio.
     * @var bool
     */
    protected $entregaSabado;

    /**
     * Código do erro retornado pelo web service do Correios.
     * @var int
     */
    protected $erroCodigo;

    /**
     * Retorna a descrição do erro gerado.
     * @var string
     */
    protected $erroMsg;

    /**
     * @param boolean $entregaDomiciliar
     */
    public function setEntregaDomiciliar($entregaDomiciliar)
    {
        $this->entregaDomiciliar = (bool)$entregaDomiciliar;
    }

    /**
     * @return boolean
     */
    public function isEntregaDomiciliar()
    {
        return $this->entregaDomiciliar;
    }

    /**
     * @param boolean $entregaSabado
     */
    public function setEntregaSabado($entregaSabado)
    {
        $this->entregaSabado = (bool)$entregaSabado;
    }

    /**
     * @return boolean
     */
    public function isEntregaSabado()
    {
        return $this->entregaSabado;
    }

    /**
     * @param int $erroCodigo
     */
    public function setErroCodigo($erroCodigo)
    {
        $this->erroCodigo = (int)$erroCodigo;
    }

    /**
     * @return int
     */
    public function getErroCodigo()
    {
        return $this->erroCodigo;
    }

    /**
     * @param string $erroMsg
     */
    public function setErroMsg($erroMsg)
    {
        $this->erroMsg = $erroMsg;
    }

    /**
     * @return string
     */
    public function getErroMsg()
    {
        return $this->erroMsg;
    }

    /**
     * @param int $prazoEntrega
     */
    public function setPrazoEntrega($prazoEntrega)
    {
        $this->prazoEntrega = (int)$prazoEntrega;
    }

    /**
     * @return int
     */
    public function getPrazoEntrega()
    {
        return $this->prazoEntrega;
    }

    /**
     * @param \PhpSigep\Model\ServicoDePostagem $servico
     */
    public function setServico(\PhpSigep\Model\ServicoDePostagem $servico)
    {
        $this->servico = $servico;
    }

    /**
     * @return \PhpSigep\Model\ServicoDePostagem
     */
    public function getServico()
    {
        return $this->servico;
    }

    /**
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = (float)$valor;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valorAvisoRecebimento
     */
    public function setValorAvisoRecebimento($valorAvisoRecebimento)
    {
        $this->valorAvisoRecebimento = (float)$valorAvisoRecebimento;
    }

    /**
     * @return float
     */
    public function getValorAvisoRecebimento()
    {
        return $this->valorAvisoRecebimento;
    }

    /**
     * @param float $valorMaoPropria
     */
    public function setValorMaoPropria($valorMaoPropria)
    {
        $this->valorMaoPropria = (float)$valorMaoPropria;
    }

    /**
     * @return float
     */
    public function getValorMaoPropria()
    {
        return $this->valorMaoPropria;
    }

    /**
     * @param float $valorValorDeclarado
     */
    public function setValorValorDeclarado($valorValorDeclarado)
    {
        $this->valorValorDeclarado = (float)$valorValorDeclarado;
    }

    /**
     * @return float
     */
    public function getValorValorDeclarado()
    {
        return $this->valorValorDeclarado;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return ($this->getErroCodigo() || $this->getErroMsg());
    }
}

<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class DestinoNacional extends AbstractModel implements Destino
{

    /**
     * Bairro do destinatario.
     * Max length: 30
     * @var string
     */
    protected $bairro;
    /**
     * CEP do destinatario.
     * Max length: 8
     * @var string
     */
    protected $cep;
    /**
     * Cidade do destinatario.
     * Max length: 30
     * @var string
     */
    protected $cidade;
    /**
     * Unidade de federação.
     * Max length: 2
     * @var string
     */
    protected $uf;
//	/**
//	 * Código do usuário postal 
//	 * Não obrigatório
//	 * Max length: 20
//	 * @var string
//	 */
//	protected $codigoUsuarioPostal;
//	/**
//	 * Centro de custo do cliente 
//	 * Não obrigatório
//	 * Max length: 20
//	 * @var string
//	 */
//	protected $centroCustoCliente;
    /**
     * Número do pedido
     * Opcional.
     * @var int
     */
    protected $numeroPedido;
    /**
     * Número da nota fiscal
     * Opcional.
     * @var int
     */
    protected $numeroNotaFiscal;
    /**
     * Série da nota fiscal
     * Não obrigatório
     * Max length: 20
     * @var string
     */
    protected $serieNotaFiscal;
    /**
     * Valor da nota fiscal Numérico(9,2) Não obrigatório
     * @var float
     */
    protected $valorNotaFiscal;
    /**
     * Natureza da nota fiscal
     * Não obrigatório
     * Max length: 20
     * @var string
     */
    protected $naturezaNotaFiscal;
    /**
     * Descrição do objeto
     * Não obrigatório
     * Max length: 20
     * @var string
     */
    protected $descricaoObjeto;
    /**
     * Valor a cobrar do destinatário
     * No manual está escrito que é obrigatório para o serviço 40126, porem no mesmo manual não existe nada relacionado
     * ao servico 40126.
     * @var float
     */
    protected $valorACobrar;

    /**
     * Agencia destinatária.
     * Max length: 30
     * @var string
     */
    protected $agencia;

    /**
     * @return string
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param string $bairro
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    /**
     * @return string
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param string $cep
     */
    public function setCep($cep)
    {
        $this->cep = $cep;
    }

    /**
     * @return string
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @param string $cidade
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * @return string
     */
    public function getDescricaoObjeto()
    {
        return $this->descricaoObjeto;
    }

    /**
     * @param string $descricaoObjeto
     */
    public function setDescricaoObjeto($descricaoObjeto)
    {
        $this->descricaoObjeto = $descricaoObjeto;
    }

    /**
     * @return int
     */
    public function getNumeroPedido()
    {
        return $this->numeroPedido;
    }

    /**
     * @param int $numeroPedido
     */
    public function setNumeroPedido($numeroPedido)
    {
        $this->numeroPedido = $numeroPedido;
    }

    /**
     * @return int
     */
    public function getNumeroNotaFiscal()
    {
        return $this->numeroNotaFiscal;
    }

    /**
     * @param int $numeroNotaFiscal
     */
    public function setNumeroNotaFiscal($numeroNotaFiscal)
    {
        $this->numeroNotaFiscal = $numeroNotaFiscal;
    }

    /**
     * @return string
     */
    public function getSerieNotaFiscal()
    {
        return $this->serieNotaFiscal;
    }

    /**
     * @param string $serieNotaFiscal
     */
    public function setSerieNotaFiscal($serieNotaFiscal)
    {
        $this->serieNotaFiscal = $serieNotaFiscal;
    }

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param string $uf
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
    }

    /**
     * @return float
     */
    public function getValorNotaFiscal()
    {
        return $this->valorNotaFiscal;
    }

    /**
     * @param float $valorNotaFiscal
     */
    public function setValorNotaFiscal($valorNotaFiscal)
    {
        $this->valorNotaFiscal = $valorNotaFiscal;
    }

    /**
     * @param string $naturezaNotaFiscal
     */
    public function setNaturezaNotaFiscal($naturezaNotaFiscal)
    {
        $this->naturezaNotaFiscal = $naturezaNotaFiscal;
    }

    /**
     * @return string
     */
    public function getNaturezaNotaFiscal()
    {
        return $this->naturezaNotaFiscal;
    }

    /**
     * @param float $valorACobrar
     */
    public function setValorACobrar($valorACobrar)
    {
        $this->valorACobrar = $valorACobrar;
    }

    /**
     * @return float
     */
    public function getValorACobrar()
    {
        return $this->valorACobrar;
    }

    /**
     * @param string $agencia
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
    }

    /**
     * @return string
     */
    public function getAgencia()
    {
        return $this->agencia;
    }
}
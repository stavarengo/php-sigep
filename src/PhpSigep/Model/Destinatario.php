<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class Destinatario extends AbstractModel
{

    /**
     * Nome do destinatário.
     * Obrigatório.
     * Max length: 50
     * Tag: nome_destinatario
     * @var string
     */
    protected $nome;

    /**
     * Telefone do Destinatário.
     * Não Obrigatório.
     * Max length: 12
     * Tag: telefone_destinatario
     * @var string|null
     */
    protected $telefone;

    /**
     * Celular do Destinatário.
     * Não Obrigatório.
     * Max length: 12
     * Tag: celular_destinatario
     * @var string|null
     */
    protected $celular;

    /**
     * Email do Destinatário.
     * Não obrigatório
     * Max length: 50
     * Tag: email_destinatario
     * @var string|null
     */
    protected $email;

    /**
     * Logradouro do destinatário.
     * Obrigatório.
     * Max length: 50
     * Tag: logradouro_destinatario
     * @var string
     */
    protected $logradouro;

    /**
     * Complemento do endereço.
     * Não obrigatório.
     * Max length: 30
     * Tag: complemento_destinatario
     * @var string|null
     */
    protected $complemento;

    /**
     * Número da casa, prédio, etc. Parte do endereço.
     * Obrigatório.
     * Max length: 6
     * Tag: numero_end_destinatario
     * @var string
     */
    protected $numero;

    /**
     * Indica se o destinatário é clique e retire
     * Não Obrigatório.
     * Tag: is_clique_retire
     * @var boolean
     */
    protected $isCliqueRetire = false;

    /**
     * 
     * Obrigatório.
     * Max length: 50
     * Tag: bairro
     * @var string
     */
    protected $bairro;

    /**
     * 
     * Não Obrigatório.
     * Max length: 50
     * Tag: referencia
     * @var string|null
     */
    protected $referencia;

    /**
     * 
     * Obrigatório.
     * Max length: 12
     * Tag: cidade
     * @var string
     */
    protected $cidade;

    /**
     * 
     * Obrigatório.
     * Max length: 2
     * Tag: uf
     * @var string
     */
    protected $uf;

    /**
     * 
     * Obrigatório.
     * Max length: 8
     * Tag: cep
     * @var string
     */
    protected $cep;

    /**
     * 
     * Não Obrigatório.
     * Max length: 3
     * Tag: ddd
     * @var int|null
     */
    protected $ddd;

    /**
     * Identificacao do destinatario (CPF/CNPJ).
     * Max length: 14
     * @var string|null
     */
    protected $identificacao;

    /**
     * @return string|null
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * @param string $celular
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;
    }

    /**
     * @return string|null
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param string $complemento
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * @param string $logradouro
     */
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @param string $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * @return string|null
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param string $telefone
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    /**
     * @return boolean
     */
    public function getIsCliqueRetire()
    {
        return $this->isCliqueRetire;
    }

    /**
     * @param boolean $isCliqueRetire
     */
    public function setIsCliqueRetire($isCliqueRetire = false)
    {
        $this->isCliqueRetire = $isCliqueRetire;
    }

    /**
     * @return string
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @return string|null
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * @return string
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @return string
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @return int|null
     */
    public function getDdd()
    {
        return $this->ddd;
    }

    /**
     * @param string $bairro
     * @return $this
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
        return $this;
    }

    /**
     * @param string $referencia
     * @return $this
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
        return $this;
    }

    /**
     * @param string $cidade
     * @return $this
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
        return $this;
    }

    /**
     * @param string $uf
     * @return $this
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
        return $this;
    }

    /**
     * @param string|int $cep
     * @return $this
     */
    public function setCep($cep)
    {
        $this->cep = (string)$cep;
        return $this;
    }

    /**
     * @param int|numeric-string $ddd
     * @return $this
     */
    public function setDdd($ddd)
    {
        $this->ddd = $ddd;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdentificacao()
    {
        return $this->identificacao;
    }

    /**
     * @param int|string $identificacao
     */
    public function setIdentificacao($identificacao)
    {
        $this->identificacao = $identificacao;
        return $this;
    }
}

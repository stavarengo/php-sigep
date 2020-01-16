<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class Remetente extends AbstractModel
{

    /**
     * Número do contrato do cliente.
     * Max length: 20
     * @var string
     */
    protected $numeroContrato;

    /**
     * Diretoria Regional do contrato do cliente.
     * @var Diretoria
     */
    protected $diretoria;

    /**
     * Código administrativo do cliente
     * Max length: 9
     * @var string
     */
    protected $codigoAdministrativo;

    /**
     * Nome do remetente
     * Max length: 50
     * @var string
     */
    protected $nome;

    /**
     * Logradouro do remetente.
     * Max length: 40
     * @var string
     */
    protected $logradouro;

    /**
     * Número da casa, prédio, etc. Parte do endereço do remetente.
     * Max length: 6
     * @var string
     */
    protected $numero;

    /**
     * Complemento do endereço.
     * Max length: 20
     * @var string
     */
    protected $complemento;

    /**
     * Bairro do endereço.
     * Max length: 20
     * @var string
     */
    protected $bairro;

    /**
     * CEP do remetente.
     * Max length: 8
     * @var string
     */
    protected $cep;

    /**
     * Cidade do remetente.
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

    /**
     * Telefone do remetente.
     * Max length: 12
     * @var string
     */
    protected $telefone;

    /**
     * Fax do remetente.
     * Max length: 12
     * @var string
     */
    protected $fax;

    /**
     * Email do remetente.
     * Max length: 50
     * @var string
     */
    protected $email;

    /**
     * Identificacao do remetente (CPF/CNPJ).
     * Max length: 14
     * @var string
     */
    protected $identificacao;

    /**
     * 
     * Max length: 1
     * @var string
     */
    protected $sms;
    protected $referencia;

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
    public function getCodigoAdministrativo()
    {
        return $this->codigoAdministrativo;
    }

    /**
     * @param string $codigoAdministrativo
     */
    public function setCodigoAdministrativo($codigoAdministrativo)
    {
        $this->codigoAdministrativo = $codigoAdministrativo;
    }

    /**
     * @return string
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
     * @return \PhpSigep\Model\Diretoria
     */
    public function getDiretoria()
    {
        return $this->diretoria;
    }

    /**
     * @param \PhpSigep\Model\Diretoria $diretoria
     */
    public function setDiretoria($diretoria)
    {
        $this->diretoria = $diretoria;
    }

    /**
     * @return string
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
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
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
     * @return string
     */
    public function getNumeroContrato()
    {
        return $this->numeroContrato;
    }

    /**
     * @param string $numeroContrato
     */
    public function setNumeroContrato($numeroContrato)
    {
        $this->numeroContrato = $numeroContrato;
    }

    /**
     * @return string
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

    public function getIdentificacao()
    {
        return $this->identificacao;
    }

    public function getSms()
    {
        return $this->sms;
    }

    public function setIdentificacao($identificacao)
    {
        $this->identificacao = $identificacao;
        return $this;
    }

    public function setSms($sms)
    {
        $this->sms = $sms;
        return $this;
    }

    public function getReferencia()
    {
        return $this->referencia;
    }

    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
        return $this;
    }
}

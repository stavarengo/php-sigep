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
     * @var \PhpSigep\Model\Diretoria
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
     * @var string|null
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
     * Código do DDD do remetente
     * Não Obrigatório.
     * Max length: 2
     * Tag: ddd
     * @var int|null
     */
    protected $ddd;

    /**
     * Telefone do remetente.
     * Max length: 12
     * @var string|null
     */
    protected $telefone;

    /**
     * Código do DDD do celular do remetente
     * Não Obrigatório.
     * Max length: 2
     * Tag: ddd_celular
     * @var int|null
     */
    protected $ddd_celular;

    /**
     * Celular do remetente.
     * Max length: 12
     * @var string|null
     */
    protected $celular;

    /**
     * Fax do remetente.
     * Max length: 12
     * @var string|null
     */
    protected $fax;

    /**
     * Email do remetente.
     * Max length: 50
     * @var string|null
     */
    protected $email;

    /**
     * Identificacao do remetente (CPF/CNPJ).
     * Max length: 14
     * @var string|null
     */
    protected $identificacao;

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
     * @return string|null
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
     * @return string|null
     */
    public function getIdentificacao()
    {
        return $this->identificacao;
    }

    /**
     * @param string $identificacao
     * @return $this
     */
    public function setIdentificacao($identificacao)
    {
        $this->identificacao = $identificacao;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDdd()
    {
        return $this->ddd;
    }

    /**
     * @param int|null $ddd
     */
    public function setDdd($ddd)
    {
        $this->ddd = $ddd;
    }

    /**
     * @return int|null
     */
    public function getDddCelular()
    {
        return $this->ddd_celular;
    }

    /**
     * @param int|null $ddd_celular
     */
    public function setDddCelular($ddd_celular)
    {
        $this->ddd_celular = $ddd_celular;
    }
}

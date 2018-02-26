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
     * DDD do telefone do remetente.
     * Max length: 12
     * @var string
     */
    protected $ddd;
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
     * Uma referência do local de entrega do remetente.
     * Max length: 60
     * @var string
     */
    protected $referencia;
    /**
     * DDD do celular do remetente.
     * Não Obrigatório.
     * Max length: 3
     * Tag: ddd_celular
     * @var string
     */
    protected $ddd_celular;
    /**
     * Celular do remetente.
     * Não Obrigatório.
     * Max length: 12
     * Tag: celular_remetente
     * @var string
     */
    protected $celular;
    /**
     * Se o remetente recebe SMS.
     * Não Obrigatório.
     * Max length: 1
     * Tag: sms
     * @var string
     */
    protected $sms;
    /**
     * CPF ou CNPJ do remetente.
     * Não Obrigatório.
     * Max length: 14
     * Tag: identificacao
     * @var string
     */
    protected $identificacao;

    /**
     * Remetente class constructor.
     * @access public
     * @param array $data
     * @return null
     */
    public function __construct($data = array())
    {
        $this->nome = ( isset($data['nome']) ? $data['nome'] : null );
        $this->logradouro = ( isset($data['nome']) ? $data['nome'] : null );
        $this->numero = ( isset($data['numero']) ? $data['numero'] : null );
        $this->complemento = ( isset($data['complemento']) ? $data['complemento'] : null );
        $this->referencia = ( isset($data['referencia']) ? $data['referencia'] : null );
        $this->cidade = ( isset($data['cidade']) ? $data['cidade'] : null );
        $this->uf = ( isset($data['uf']) ? $data['uf'] : null );
        $this->cep = ( isset($data['cep']) ? $data['cep'] : null );
        $this->bairro = ( isset($data['bairro']) ? $data['bairro'] : null );
        $this->ddd = ( isset($data['ddd']) ? $data['ddd'] : null );
        $this->telefone = ( isset($data['telefone']) ? $data['telefone'] : null );
        $this->ddd_celular = ( isset($data['ddd_celular']) ? $data['ddd_celular'] : null );
        $this->celular = ( isset($data['celular']) ? $data['celular'] : null );
        $this->email = ( isset($data['email']) ? $data['email'] : null );
        $this->sms = ( isset($data['sms']) ? $data['sms'] : null );
        $this->identificacao = ( isset($data['identificacao']) ? $data['identificacao'] : null );
        $this->fax = ( isset($data['fax']) ? $data['fax'] : null );
        $this->codigoAdministrativo = ( isset($data['codigoAdministrativo']) ? $data['codigoAdministrativo'] : null );
        $this->diretoria = ( isset($data['diretoria']) ? $data['diretoria'] : null );
        $this->numeroContrato = ( isset($data['numeroContrato']) ? $data['numeroContrato'] : null );
    }

    /**
    * Get object vars of this class.
    * @access public
    * @return array
    */
    public function getObjects()
    {
        return get_object_vars($this);
    }

    /**
    * Get instance.
    * @access public
    * @return AccessData
    */
    public function getInstance()
    {
        return $this;
    }

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

    /**
     * @return string
     */
    public function getDdd()
    {
        return $this->ddd;
    }

    /**
     * @param string $ddd
     */
    public function setDdd($ddd)
    {
        $this->ddd = $ddd;
    }

    /**
     * @return string
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * @param string $referencia
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    }

    /**
     * @return string
     */
    public function getDddCelular()
    {
        return $this->ddd_celular;
    }

    /**
     * @param string $ddd_celular
     */
    public function setDddCelular($ddd_celular)
    {
        $this->ddd_celular = $ddd_celular;
    }

    /**
     * @return string
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
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * @param string $sms
     */
    public function setSms($sms)
    {
        $this->sms = $sms;
    }

    /**
     * @return string
     */
    public function getIdentificacao()
    {
        return $this->identificacao;
    }

    /**
     * @param string $identificacao
     */
    public function setIdentificacao($identificacao)
    {
        $this->identificacao = $identificacao;
    }

}

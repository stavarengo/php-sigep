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
     * Max length: 60
     * Tag: nome_destinatario
     * @var string
     */
    protected $nome;
    /**
     * DDD do telefone do Destinatário.
     * Não Obrigatório.
     * Max length: 3
     * Tag: ddd
     * @var string
     */
    protected $ddd;
    /**
     * Telefone do Destinatário.
     * Não Obrigatório.
     * Max length: 12
     * Tag: telefone_destinatario
     * @var string
     */
    protected $telefone;
    /**
     * DDD do celular do Destinatário.
     * Não Obrigatório.
     * Max length: 3
     * Tag: ddd_celular
     * @var string
     */
    protected $ddd_celular;
    /**
     * Celular do Destinatário.
     * Não Obrigatório.
     * Max length: 12
     * Tag: celular_destinatario
     * @var string
     */
    protected $celular;
    /**
     * Email do Destinatário.
     * Não obrigatório
     * Max length: 50
     * Tag: email_destinatario
     * @var string
     */
    protected $email;
    /**
     * Logradouro do destinatário.
     * Não obrigatório.
     * Max length: 50
     * Tag: logradouro_destinatario
     * @var string
     */
    protected $logradouro;
    /**
     * Complemento do endereço.
     * Obrigatório.
     * Max length: 30
     * Tag: complemento_destinatario
     * @var string
     */
    protected $complemento;
    /**
     * Número da casa, prédio, etc. Parte do endereço.
     * Não Obrigatório.
     * Max length: 6
     * Tag: numero_end_destinatario
     * @var string
     */
    protected $numero;
    /**
     * Código do estado do destinatário.
     * Obrigatório.
     * Max length: 2
     * Tag: uf
     * @var string
     */
    protected $uf;
    /**
     * Nome da cidade do destinatário.
     * Obrigatório.
     * Max length: 36
     * Tag: cidade
     * @var string
     */
    protected $cidade;
    /**
     * Nome da cidade do destinatário.
     * Não Obrigatório.
     * Max length: 60
     * Tag: referencia
     * @var string
     */
    protected $referencia;
    /**
     * Cep do destinatário.
     * Obrigatório.
     * Max length: 8
     * Tag: cep
     * @var string
     */
    protected $cep;
    /**
     * Bairro do destinatário.
     * Obrigatório.
     * Max length: 50
     * Tag: bairro
     * @var string
     */
    protected $bairro;

    /**
     * Destinatario class constructor.
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
    }

    /**
    * Get instance.
    * @access public
    * @return Produto
    */
    public function getInstance()
    {
        return $this;
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


}

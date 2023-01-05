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
 * destinatario: Sub-tags que armazenam dados do destinatário. 
 * Sub-Tags
 * Sim
 * 	
 */
class LogisticaReversaDestinatario extends AbstractModel
{

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
     * Nome do Cliente ou Razão Social
     * Caractere(60)
     * Sim
     * @var string
     */
    protected $nome;

    /**
     * logradouro:
     * Logradouro do cliente
     * Caractere(72)
     * Sim
     * @var string
     */
    protected $logradouro;

    /**
     * numero:
     * Número do endereço do cliente. Caso não possua preencher com “S/N”
     * Caractere(8)
     * Sim 	
     * @var string
     */
    protected $numero;

    /**
     * complemento:
     * Complemento do endereço do cliente 
     * Caractere(30) 
     * Não 
     * @var string
     */
    protected $complemento;

    /**
     * bairro:
     * Bairro 
     * Caractere(50) 
     * Não
     * @var string
     */
    protected $bairro;

    /**
     * referencia:
     * Uma referência do local de entrega 
     * Caractere(60) 
     * Não
     * @var string
     */
    protected $referencia;

    /**
     * cidade:
     * Cidade
     * Caractere(36) 
     * Sim
     * @var string
     */
    protected $cidade;

    /**
     * uf:
     * Sigla do Estado. 
     * Caractere(2) 
     * Sim
     * @var string
     */
    protected $UF;

    /**
     * cep:
     * CEP sempre com oito posições. Ex: 01000999
     * Caractere(8) 
     * Sim
     * @var string
     */
    protected $CEP;

    /**
     * ddd: Código de área do telefone. 
     * Caractere(3) 
     * Não
     * @var string
     */
    protected $DDD;

    /**
     * telefone:
     * Telefone do cliente 
     * Caractere(12) 
     * Não
     * @var string
     */
    protected $telefone;

    /**
     * email:
     * Este campo não é obrigatório, mas é importante para que o cliente receba informações sobre seus pedidos. 
     * Caractere(72) 
     * Não
     * @var string
     */
    protected $email;

    /**
     * dados:
     * @var array
     */
    protected $dados;

    
    /**
     * @param string msg_erro
     */
    public function setMsgErro($msgErro)
    {
        $this->msg_erro =  $msgErro;
    }
    
    /**
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->msg_erro;
    }

    
    /**
     * @param string cod_erro
     */
    public function setErrorCode($codErro)
    {
        $this->cod_erro =  $codErro;
    }
    /**
     * @return string
     */
    public function getCodErro()
    {
        return $this->cod_erro;
    }


   /**
     * @param string
    */
   public function setNome($nome)
   {
           $this->nome = $nome;
   }

   /**
     * @param string
    */
   public function setLogradouro($logradouro)
   {
           $this->logradouro = $logradouro;
   }

   /**
     * @param string
    */
   public function setNumero($numero)
   {
           $this->numero = $numero;
   }

   /**
     * @param string
    */
   public function setComplemento($complemento)
   {
           $this->complemento = $complemento;
   }

   /**
     * @param string
    */
   public function setBairro($bairro)
   {
           $this->bairro = $bairro;
   }

   /**
     * @param string
    */
   public function setReferencia($referencia)
   {
           $this->referencia = $referencia;
   }

   /**
     * @param string
    */
   public function setCidade($cidade)
   {
           $this->cidade = $cidade;
   }

   /**
     * @param string
    */
   public function setUF($UF)
   {
           $this->UF = $UF;
   }

   /**
     * @param string
    */
   public function setCEP($CEP)
   {
           $this->CEP = $CEP;
   }

   /**
     * @param string
    */
   public function setDDD($DDD)
   {
           $this->DDD = $DDD;
   }

   /**
     * @param string
    */
   public function setTelefone($telefone)
   {
           $this->telefone = $telefone;
   }

   /**
     * @param string
    */
   public function setEmail($email)
   {
           $this->email = $email;
   }
   
    public function getDados() {

        $this->dados = array(
            "nome"          => $this->nome,
            "logradouro"    => $this->logradouro,
            "numero"        => $this->numero,
            "complemento"   => $this->complemento,
            "bairro"        => $this->bairro,
            "referencia"    => $this->referencia,
            "cidade"        => $this->cidade,
            "uf"            => $this->UF,
            "email"         => $this->email,
            "cep"           => $this->CEP,
            "ddd"           => $this->DDD,
            "telefone"      => $this->telefone,
        );
        return $this->dados;
    }
}

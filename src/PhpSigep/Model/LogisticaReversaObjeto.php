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
 * obj_col:
 * Contém os blocos de tags que cadastram os objetos que serão coletados (coleta domiciliar) ou postados (autorização de postagem). Caso esta tag não seja encontrada o sistema assume que existe apenas um objeto a ser coletado ou postado. 
 * - 
 * Sim
*/

class LogisticaReversaObjeto extends AbstractModel
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
     * @var array
     */
    protected $dados;
    /**
     * @var array
     */
    protected $objCol;

    /**
     * item:
     * Tag obrigatória. Apenas confirma o cadastro do objeto dentro da solicitação. 
     * Valor fixo “1” 
     * Sim
     * @var string
     */
    protected $item;

    /**
     * id:
     * Campo para preenchimento livre. É um valor para identificação do objeto junto ao cliente. Este valor é enviado no arquivo de retorno gerado após o processamento. Exemplo: Número da nota fiscal.
     * Caractere(30)
     * Não
     * @var string
     */
    protected $id;

    /**	
     * desc:
     * Descrição do objeto que será coletado 
     * Caractere(255)
     * Não 	
     * @var string
     */
    protected $desc;

    /**
     * entrega:
     * Número do objeto para os pedidos de coleta simultânea. O contrato deve aceitar pedidos de coleta simultânea.
     * Caractere(13)
     * Não 	
     * @var string
     */
    protected $entrega;

    /**
     * num:
     * Número do objeto quando existe uma faixa numérica reservada para o cliente. Esta opção ainda não é utilizada. 
     * Caractere(13) 
     * Não
     * @var string
     */
    protected $num;

    
    /**
     * @param string msg_erro
     */
    public function setMsgErro($msgErro)
    {
        $this->msg_erro = $msgErro;
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
     * @param array
    */
   public function setObjeto($objCol)
   {
           $this->objCol = $objCol;
   }

   /**
     * @param string
    */
   public function setItem($Item)
   {
           $this->item = $Item;
   }

   /**
     * @param string
    */
   public function setId($Id)
   {
           $this->id = $Id;
   }

   /**
     * @param string
    */
   public function setDesc($Desc)
   {
           $this->desc = $Desc;
   }

   /**
     * @param string
    */
   public function setEntrega($Entrega)
   {
           $this->entrega = $Entrega;
   }

   /**
     * @param string
    */
   public function setNum($Num)
   {
           $this->num = $Num;
   }

   /**
     * @param string
    */
   public function getDados()
   {

       $this->dados = array (
         "item"     =>  $this->item,
         "id"       =>  $this->id,
         "desc"     =>  $this->desc,
         "entrega"  =>  $this->entrega,
         "num"      =>  $this->num,

       );
       
       return $this->dados;
   }

}
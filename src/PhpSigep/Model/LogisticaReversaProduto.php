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
 * produto:
 * Contém os blocos de tags que são utilizadas para solicitação de produtos junto com a coleta. O contrato do cliente deve ser habilitado previamente para usar essa tag. 
 * - 
 * Não
*/

class LogisticaReversaProduto extends AbstractModel
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
    protected $produto;

    /**
     * codigo
     * Código do produto. Fornecido pela ECT. Ver tabela neste documento. 
     * Numérico
     * -
     * @var string
     */
    protected $codigo;

    /**
     * tipo:
     * Código do tipo de produto. Fornecido pela ECT. Ver tabela neste documento.
     * Numérico
     * -
     *   
     * @var string
     */
    protected $tipo;

    /**
     * qtd:
     * Quantidade de produtos do tipo fornecido. 
     * Numérico
     * -
     * @var string
     */
    protected $qtd;

    
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
     * @param array
    */
  /*
     public function setProduto()
   
   {
           $this->produto = array(
               "codigo" => $this->codigo,
               "tipo" => $this->tipo,
               "qtd" => $this->qtd
           );
   }
*/
   /**
     * @param string
    */
   public function setCodigo($Codigo)
   {
           $this->codigo = $Codigo;
   }

   /**
     * @param string
    */
   public function setTipo($Tipo)
   {
           $this->tipo = $Tipo;
   }

   /**
     * @param string
    */
   public function setQtd($Qtd)
   {
           $this->qtd = $Qtd;
   }

   /**
     * @param array
    */
   public function getDados()
   {
        $this->produto = array( 
            "codigo"    => $this->codigo,
            "tipo"      => $this->tipo,
            "qtd"       => $this->qtd,
        );
        return $this->produto;
   }

}
<?php
namespace PhpSigep\Model;
use PhpSigep\InvalidArgument;

/**
 * @author: Rodrigo Job (rodrigo at econector.com.br)
 */
class LogisticaReversa extends AbstractModel
{

    /**
     * @var string
     */
    protected $codAdministrativo;
    /**
     * @var string
     */
    protected $tipoBusca;
    /**
     * @var string
     */
    protected $tipoSolicitacao;
    /**
     * @var string
     */
    protected $numeroPedido;
    /**
     * @var string
     */
    protected $qtdeDias;
    /**
     * Opcional.
     * Quando não informado será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     * @var AccessData
     */
    protected $accessData;

    /**
     * @var string
     */
    protected $prazo;
    /**
     * @var string
     */
    protected $msg_erro;
    /**
     * @var string
     */
    protected $cod_erro;
    /**
     * @var string
     */
    protected $tipo; 
    /**
     * @var @link \PhpSigep\ObjetoPostal() 
     */
    protected $objetoPostal;    
    
    
    
    /**
     * @return \PhpSigep\Model\AccessData
     */
    public function getAccessData()
    {
        return $this->accessData;
    }

    /**
     * @param \PhpSigep\Model\AccessData $accessData
     *      Opcional.
     *      Quando null será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     */
    public function setAccessData(AccessData $accessData)
    {
        $this->accessData = $accessData;
    }

    /**
     *  @param string $tipoBusca
     */
    public function setTipoBusca($tipoBusca)
    {
        $this->tipoBusca= $tipoBusca;
    }

    /**
     * @return string
     */
    public function getTipoBusca()
    {
        return $this->tipoBusca;
    }

    /**
     *  @param string codAdministrativo
     */
    public function setCodAdministrativo($codAdministrativo)
    {
        $this->codAdministrativo= $codAdministrativo;
    }

    /**
     * @return string
     */
    public function getCodAdministrativo()
    {
        return $this->codAdministrativo;
    }

    /**
     *  @param string qtdeDias
     */
    public function setQtdeDias($qtdeDias)
    {
        $this->qtdeDias= $qtdeDias;
    }

    /**
     * @return string
     */
    public function getQtdeDias()
    {
        return $this->qtdeDias;
    }
    
    /**
     * @param string $tipoSolicitacao
     */
    public function setTipoSolicitacao($tipoSolicitacao)
    {
        $this->tipoSolicitacao = $tipoSolicitacao;
    }

    /**
     * @return string
     */
    public function getTipoSolicitacao()
    {
        return $this->tipoSolicitacao;
    }
    
    /**
     * @param string numeroPedido
     */
    public function setNumeroPedido($numeroPedido)
    {
        $this->numeroPedido = preg_replace('/[^\d]/', '', $numeroPedido);
    }

    /**
     * @return string
     */
    public function getNumeroPedido()
    {
        return $this->numeroPedido;
    }

    /**
     * @return string
     */
    public function getPrazo()
    {
        return $this->prazo;
    }
    
    /**
     * @param string prazo
     */
    public function setPrazo($prazo)
    {
        $this->prazo = preg_replace('/[^\d]/', '', $prazo);
    }

    
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
     * @param string tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo =  $tipo;
    }
    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }
    
    /**
     * @return \PhpSigep\Model\ObjetoPostal
     */
    public function getObjetoPostal()
    {
        return $this->objetoPostal;
    }

    /**
     * @param \PhpSigep\Model\ObjetoPostal $objetoPostal
        */
    public function setObjetoPostal(ObjetoPostal $objetoPostal)
    {
        $this->objetoPostal = $objetoPostal;
    }
    
    /**
     * @return array
        */
    public function getDados()
    {
        return array(
                "accessData" => $this->accessData,
                "codAdministrativo" => $this->codAdministrativo,
                "numero_pedido" => $this->numeroPedido,
                "tipo" => $this->tipo,
            );
    }
    
    
}

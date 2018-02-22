<?php
namespace PhpSigep\Model;

use PhpSigep\Model\Produto;
use PhpSigep\Model\Remetente;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */
 
class ColetasSolicitadas extends AbstractModel
{

    /**
     * @var string
     */
    protected $tipo;
    /**
     * @var string
     */
    protected $numero;
    /**
     * @var string
     */
    protected $id_cliente;
    /**
     * @var string
     */
    protected $ag;
    /**
     * @var string
     */
    protected $valor_declarado;
    /**
     * @var string
     */
    protected $servico_adicional;
    /**
     * @var string
     */
    protected $descricao;
    /**
     * @var string
     */
    protected $ar;
    /**
     * @var string
     */
    protected $cklist;
    /**
     * @var string
     */
    protected $documento;
    /**
     * @var object
     */
    protected $remetente;
    /**
     * @var object
     */
    protected $produto;

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
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdCliente()
    {
        return $this->id_cliente;
    }

    /**
     * @param string $id_cliente
     */
    public function setIdCliente($id_cliente)
    {
        $this->id_cliente = $id_cliente;
        return $this;
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
        return $this;
    }

    /**
     * @return string
     */
    public function getAg()
    {
        return $this->ag;
    }

    /**
     * @param string $ag
     */
    public function setAg($ag)
    {
        $this->ag = $ag;
        return $this;
    }

    /**
     * @return string
     */
    public function getValorDeclarado()
    {
        return $this->valor_declarado;
    }

    /**
     * @param string $valor_declarado
     */
    public function setValorDeclarado($valor_declarado)
    {
        $this->valor_declarado = $valor_declarado;
        return $this;
    }

    /**
     * @return string
     */
    public function GetServicoAdicional()
    {
        return $this->servico_adicional;
    }

    /**
     * @param string $servico_adicional
     */
    public function setServicoAdicional($servico_adicional)
    {
        $this->servico_adicional = $servico_adicional;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getAr()
    {
        return $this->ar;
    }

    /**
     * @param string $ar
     */
    public function setAr($ar)
    {
        $this->ar = $ar;
        return $this;
    }

    /**
     * @return string
     */
    public function getCklist()
    {
        return $this->cklist;
    }

    /**
     * @param string $ar
     */
    public function setCklist($cklist)
    {
        $this->cklist = $cklist;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @param string $documento
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;
        return $this;
    }

    /**
     * @return object
     */
    public function getRemetente()
    {
        return $this->remetente;
    }

    /**
     * @param string $remetente
     */
    public function setRemetente(Remetente $remetente)
    {
        $this->remetente = $remetente;
        return $this;
    }

    /**
     * @return object
     */
    public function getProduto()
    {
        return $this->produto;
    }

    /**
     * @param string $produto
     */
    public function setProduto(Produto $produto)
    {
        $this->produto = $produto;
        return $this;
    }

    /**
     * @return object
     */
    public function getObjCol()
    {
        return $this->obj_col;
    }

    /**
     * @param string $obj_col
     */
    public function setObjCol(ObjCol $obj_col)
    {
        $this->obj_col = $obj_col;
        return $this;
    }

}

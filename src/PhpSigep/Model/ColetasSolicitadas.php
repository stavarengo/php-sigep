<?php
namespace PhpSigep\Model;

/**
 * @author: Renan Zanelato <renan.zanelato96@gmail.com
 */
class ColetasSolicitadas extends AbstractModel
{

    protected $tipo;
    protected $numero;
    protected $id_cliente;
    protected $ag;
    protected $valor_declarado;
    protected $servico_adicional;
    protected $descricao;
    protected $ar;
    protected $cklist;
    protected $documento;

    /**
     * @var \PhpSigep\Model\Remetente 
     */
    protected $remetente;
    protected $produto;

    /**
     * @var \PhpSigep\Model\ObjCol 
     */
    protected $obj_col;

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function getId_cliente()
    {
        return $this->id_cliente;
    }

    public function getAg()
    {
        return $this->ag;
    }

    public function getValor_declarado()
    {
        return $this->valor_declarado;
    }

    public function getServico_adicional()
    {
        return $this->servico_adicional;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getAr()
    {
        return $this->ar;
    }

    public function getCklist()
    {
        return $this->cklist;
    }

    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @return \PhpSigep\Model\Remetente
     */
    public function getRemetente()
    {
        return $this->remetente;
    }

    public function getProduto()
    {
        return $this->produto;
    }

    /**
     * @return <array>\PhpSigep\Model\ObjCol
     */
    public function getObj_col()
    {
        return $this->obj_col;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
        return $this;
    }

    public function setId_cliente($id_cliente)
    {
        $this->id_cliente = $id_cliente;
        return $this;
    }

    public function setAg($ag)
    {
        $this->ag = $ag;
        return $this;
    }

    public function setValor_declarado($valor_declarado)
    {
        $this->valor_declarado = $valor_declarado;
        return $this;
    }

    public function setServico_adicional($servico_adicional)
    {
        $this->servico_adicional = $servico_adicional;
        return $this;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function setAr($ar)
    {
        $this->ar = $ar;
        return $this;
    }

    public function setCklist($cklist)
    {
        $this->cklist = $cklist;
        return $this;
    }

    public function setDocumento($documento)
    {
        $this->documento = $documento;
        return $this;
    }

    /**
     * Set remetente.
     *
     * @param \PhpSigep\Model\Remetente $remetente
     *
     * @return ColetasSolicitadas
     */
    public function setRemetente(\PhpSigep\Model\Remetente $remetente)
    {
        $this->remetente = $remetente;
        return $this;
    }

    public function setProduto($produto)
    {
        $this->produto = $produto;
        return $this;
    }

    /**
     * Set obj_col.
     *
     * @param \PhpSigep\Model\ObjCol $obj_col
     *
     * @return ColetasSolicitadas
     */
    public function setObj_col(\PhpSigep\Model\ObjCol $obj_col)
    {
        return $this->AddObj_col($obj_col);
    }

    /**
     * Add obj_col.
     *
     * @param \PhpSigep\Model\ObjCol $obj_col
     *
     * @return ColetasSolicitadas
     */
    public function AddObj_col(\PhpSigep\Model\ObjCol $obj_col)
    {

        $this->obj_col[] = $obj_col;
        return $this;
    }
}

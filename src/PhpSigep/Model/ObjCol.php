<?php
namespace PhpSigep\Model;

/**
 * @author: Renan Zanelato <email:renan.zanelato96@gmail.com>
 */
class ObjCol extends AbstractModel
{
    /*
     * Obrigatorio
     */

    protected $item;
    /*
     * Nao Obrigatorio
     */
    protected $desc;
    /*
     * Nao Obrigatorio
     */
    protected $entrega;
    /*
     * Nao Obrigatorio
     */
    protected $num;
    /*
     * Nao Obrigatorio
     */
    protected $id;

    public function getItem()
    {
        return $this->item;
    }

    public function getDesc()
    {
        return $this->desc;
    }

    public function getEntrega()
    {
        return $this->entrega;
    }

    public function getNum()
    {
        return $this->num;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setItem($item)
    {
        $this->item = $item;
        return $this;
    }

    public function setDesc($desc)
    {
        $this->desc = $desc;
        return $this;
    }

    public function setEntrega($entrega)
    {
        $this->entrega = $entrega;
        return $this;
    }

    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}

<?php
namespace PhpSigep\Model;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class ObjCol extends AbstractModel
{

    /**
     * @var string
     */
    protected $item;
    /**
     * @var string
     */
    protected $desc;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $entrega;
    /**
     * @var string
     */
    protected $num;

    /**
     * ObjCol class constructor.
     * @access public
     * @param array $data
     * @return null
     */
    public function __construct($data = array())
    {
        $this->item = ( isset($data['item']) ? $data['item'] : null );
        $this->desc = ( isset($data['desc']) ? $data['desc'] : null );
        $this->id = ( isset($data['id']) ? $data['id'] : null );
        $this->entrega = ( isset($data['entrega']) ? $data['entrega'] : null );
        $this->num = ( isset($data['num']) ? $data['num'] : null );
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
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param string $item
     */
    public function setItem($item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntrega()
    {
        return $this->entrega;
    }

    /**
     * @param string $entrega
     */
    public function setEntrega($entrega)
    {
        $this->entrega = $entrega;
        return $this;
    }

    /**
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * @param string $num
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

}

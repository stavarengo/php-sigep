<?php
namespace PhpSigep\Model;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class Produto extends AbstractModel
{

    /**
     * @var string
     */
    protected $tipo;
    /**
     * @var string
     */
    protected $codigo;
    /**
     * @var int
     */
    protected $qtd;

    /**
     * Produto class constructor.
     * @access public
     * @param array $data
     * @return null
     */
    public function __construct($data = array())
    {
        $this->tipo = ( isset($data['tipo']) ? $data['tipo'] : null );
        $this->codigo = ( isset($data['codigo']) ? $data['codigo'] : null );
        $this->qtd = ( isset($data['qtd']) ? $data['qtd'] : null );
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
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param string $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
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
    public function getQtd()
    {
        return $this->qtd;
    }

    /**
     * @param string $qtd
     */
    public function setQtd($qtd)
    {
        $this->qtd = $qtd;
        return $this;
    }

}

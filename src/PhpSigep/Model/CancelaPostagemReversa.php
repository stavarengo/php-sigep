<?php
namespace PhpSigep\Model;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class CancelaPostagemReversa extends AbstractModel
{

    /**
     * Opcional.
     * Quando não informado será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     * @var AccessData
     */
    protected $accessData;
    /**
     * Dados da pessoa que está remetendo esta encomenda.
     * @var tipo
     */
    protected $tipo;
    /**
     * Os objetos que estão sendo postados.
     * @var NumeroPedido
     */
    protected $numeroPedido;

    /**
     * CancelaPostagemReversa class constructor.
     * @access public
     * @param array $data
     * @return null
     */
    public function __construct($data = array())
    {
        $this->accessData = ( isset($data['accessData']) ? $data['accessData'] : \PhpSigep\Model\AccessData::class );
        $this->tipo = ( isset($data['tipo']) ? $data['tipo'] : null );
        $this->numeroPedido = ( isset($data['numeroPedido']) ? $data['numeroPedido'] : null );
    }

    /**
     * @param \PhpSigep\Model\AccessData $accessData
     *      Opcional.
     *      Quando null será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     */
    public function setAccessData(\PhpSigep\Model\AccessData $accessData)
    {
        $this->accessData = $accessData;
    }

    /**
     * @return \PhpSigep\Model\AccessData
     */
    public function getAccessData()
    {
        return $this->accessData;
    }

    /**
     * @param string $numeroPedido
     */
    public function setNumeroPedido($numeroPedido)
    {
        $this->numeroPedido = $numeroPedido;
    }

    /**
     * @return string
     */
    public function getNumeroPedido()
    {
        return $this->numeroPedido;
    }

    /**
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

}

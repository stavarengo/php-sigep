<?php
namespace PhpSigep\Model;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class AcompanhaPostagemReversa extends AbstractModel
{

    /**
     * Opcional.
     * Quando não informado será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     * @var AccessData
     */
    protected $accessData;
    /**
     * Dados da pessoa que está remetendo esta encomenda.
     * @var string
     */
    protected $tipoBusca;
    /**
     * Os objetos que estão sendo postados.
     * @var string
     */
    protected $numeroPedido;
    /**
     * Os objetos que estão sendo postados.
     * @var string
     */
    protected $tipoSolicitacao;

    /**
     * AcompanhaPostagemReversa class constructor.
     * @access public
     * @param array $data
     * @return null
     */
    public function __construct($data = array())
    {
        $this->accessData = ( isset($data['accessData']) ? $data['accessData'] : \PhpSigep\Model\AccessData::class );
        $this->tipoBusca = ( isset($data['tipoBusca']) ? $data['tipoBusca'] : null );
        $this->numeroPedido = ( isset($data['numeroPedido']) ? $data['numeroPedido'] : null );
        $this->tipoSolicitacao = ( isset($data['tipoSolicitacao']) ? $data['tipoSolicitacao'] : null );
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
     * @param string $tipoBusca
     */
    public function setTipoBusca($tipoBusca)
    {
        $this->tipoBusca = $tipoBusca;
    }

    /**
     * @return string
     */
    public function getTipoBusca()
    {
        return $this->tipoBusca;
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

}

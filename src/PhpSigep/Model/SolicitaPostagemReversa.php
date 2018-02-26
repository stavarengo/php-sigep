<?php
namespace PhpSigep\Model;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class SolicitaPostagemReversa extends AbstractModel
{

    /**
     * Opcional.
     * Quando não informado será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     * @var AccessData
     */
    protected $accessData;
    /**
     * Dados da pessoa que está remetendo esta encomenda.
     * @var destinatario
     */
    protected $destinatario;
    /**
     * Os objetos que estão sendo postados.
     * @var ColetasSolicitadas
     */
    protected $coletasSolicitadas;

    /**
     * SolicitaPostagemReversa class constructor.
     * @access public
     * @param array $data
     * @return null
     */
    public function __construct($data = array())
    {
        $this->accessData = ( isset($data['accessData']) ? $data['accessData'] : \PhpSigep\Model\AccessData::class );
        $this->destinatario = ( isset($data['destinatario']) ? $data['destinatario'] : \PhpSigep\Model\Destinatario::class );
        $this->accessData = ( isset($data['coletasSolicitadas']) ? $data['coletasSolicitadas'] : \PhpSigep\Model\ColetasSolicitadas::class );
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
     * @param \PhpSigep\Model\ColetasSolicitadas $coletasSolicitadas
     */
    public function setColetasSolicitadas(\PhpSigep\Model\ColetasSolicitadas $coletasSolicitadas)
    {
        $this->coletasSolicitadas = $coletasSolicitadas;
    }

    /**
     * @return \PhpSigep\Model\ColetasSolicitadas
     */
    public function getColetasSolicitadas()
    {
        return $this->coletasSolicitadas;
    }

    /**
     * @param \PhpSigep\Model\Destinatario $destinatario
     */
    public function setDestinatario(\PhpSigep\Model\Destinatario $destinatario)
    {
        $this->destinatario = $destinatario;
    }

    /**
     * @return \PhpSigep\Model\Destinatario
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

}

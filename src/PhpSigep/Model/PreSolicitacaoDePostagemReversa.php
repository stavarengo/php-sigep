<?php
namespace PhpSigep\Model;

/**
 * @author WilliamNovak
 */
class PreSolicitacaoDePostagemReversa extends AbstractModel
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
     * @param \PhpSigep\Model\AccessData $accessData
     *      Opcional.
     *      Quando null será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     */
    public function setAccessData($accessData)
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
    public function setColetasSolicitadas($coletasSolicitadas)
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
     * @param \PhpSigep\Model\destinatario $destinatario
     */
    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;
    }

    /**
     * @return \PhpSigep\Model\destinatario
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

}

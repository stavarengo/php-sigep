<?php
namespace PhpSigep\Model;

/**

 * @author: Renan Zanelato <email:renan.zanelato96@gmail.com>
 */
class SolicitarPostagemReversa extends AbstractModel
{

    /**
     * @var \PhpSigep\Model\AccessData
     */
    protected $AccessData;

    /**
     * @var \PhpSigep\Model\Destinatario
     */
    protected $destinatario;

    /**
     * @var \PhpSigep\Model\ColetasSolicitadas
     */
    protected $coletas_solicitadas;
    protected $contrato;
    protected $codigo_servico;

    /**
     * Get AccessData.
     *
     * @return \PhpSigep\Model\AccessData
     */
    public function getAccessData()
    {
        return $this->AccessData;
    }

    /**
     * Get destinatario.
     *
     * @return \PhpSigep\Model\Destinatario
     */
    public function getDestinatario()
    {
        return $this->destinatario;
    }

    /**
     * Get coletas_solicitadas.
     *
     * @return \PhpSigep\Model\coletas_solicitadas
     */
    public function getColetas_solicitadas()
    {
        return $this->coletas_solicitadas;
    }

    /**
     * Set AccessData.
     *
     * @param \PhpSigep\Model\AccessData $AccessData
     *
     * @return SolicitarPostagemRervsa
     */
    public function setAccessData(\PhpSigep\Model\AccessData $AccessData)
    {
        $this->AccessData = $AccessData;
        return $this;
    }

    /**
     * Set destinatario.
     *
     * @param \PhpSigep\Model\Destinatario $destinatario
     *
     * @return SolicitarPostagemRervsa
     */
    public function setDestinatario(\PhpSigep\Model\Destinatario $destinatario)
    {
        $this->destinatario = $destinatario;
        return $this;
    }

    /**
     * Set coletas_solicitadas.
     *
     * @param \PhpSigep\Model\ColetasSolicitadas $coletas_solicitadas
     *
     * @return SolicitarPostagemRervsa
     */
    public function setColetas_solicitadas(\PhpSigep\Model\ColetasSolicitadas $coletas_solicitadas)
    {
        $this->coletas_solicitadas = $coletas_solicitadas;
        return $this;
    }

    public function getContrato()
    {
        return $this->contrato;
    }

    public function setContrato($contrato)
    {
        $this->contrato = $contrato;
        return $this;
    }

    public function getCodigo_servico()
    {
        return $this->codigo_servico;
    }

    public function setCodigo_servico($codigo_servico)
    {
        $this->codigo_servico = $codigo_servico;
        return $this;
    }
}

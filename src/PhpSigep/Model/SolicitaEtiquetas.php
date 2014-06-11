<?php
namespace PhpSigep\Model;
use PhpSigep\Bootstrap;
use PhpSigep\InvalidArgument;

/**
 * @author: Stavarengo
 */
class SolicitaEtiquetas extends AbstractModel
{

    /**
     * @var int
     */
    protected $servicoDePostagem;
    /**
     * @var int
     */
    protected $qtdEtiquetas;
    /**
     * Opcional.
     * Quando não informado será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     * @var AccessData
     */
    protected $accessData;

    /**
     * @return \PhpSigep\Model\AccessData
     */
    public function getAccessData()
    {
        return ($this->accessData ? $this->accessData : Bootstrap::getConfig());
    }

    /**
     * @param AccessData $accessData
     *      Opcional.
     *      Quando null será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     */
    public function setAccessData(AccessData $accessData)
    {
        $this->accessData = $accessData;
    }

    /**
     * @return int
     */
    public function getQtdEtiquetas()
    {
        return $this->qtdEtiquetas;
    }

    /**
     * @param int $qtdEtiquetas
     */
    public function setQtdEtiquetas($qtdEtiquetas)
    {
        $this->qtdEtiquetas = $qtdEtiquetas;
    }

    /**
     * @return ServicoDePostagem
     */
    public function getServicoDePostagem()
    {
        return $this->servicoDePostagem;
    }

    /**
     * @param int|ServicoDePostagem $servicoDePostagem
     * @throws \PhpSigep\InvalidArgument
     */
    public function setServicoDePostagem($servicoDePostagem)
    {
        if (is_int($servicoDePostagem)) {
            $servicoDePostagem = new \PhpSigep\Model\ServicoDePostagem($servicoDePostagem);
        }
        
        if (!($servicoDePostagem instanceof ServicoDePostagem)) {
            throw new InvalidArgument('Serviço de postagem deve ser um integer ou uma instância de ' .
                '\PhpSigep\Model\ServicoDePostagem.');
        }
        
        $this->servicoDePostagem = $servicoDePostagem;
    }

}

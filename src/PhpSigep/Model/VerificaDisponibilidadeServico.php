<?php
namespace PhpSigep\Model;
use PhpSigep\InvalidArgument;
use PhpSigep\Bootstrap;

/**
 * @author: Stavarengo
 */
class VerificaDisponibilidadeServico extends AbstractModel
{

    /**
     * @var ServicoDePostagem[]
     */
    protected $servicos = array();
    /**
     * @var string
     */
    protected $cepOrigem;
    /**
     * @var string
     */
    protected $cepDestino;
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
        return ($this->accessData ? $this->accessData : Bootstrap::getConfig()->getAccessData());
    }

    /**
     * @param \PhpSigep\Model\AccessData $accessData
     *      Opcional.
     *      Quando null será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     */
    public function setAccessData(AccessData $accessData)
    {
        $this->accessData = $accessData;
    }

    /**
     * @return string
     */
    public function getCepDestino()
    {
        return $this->cepDestino;
    }

    /**
     * @param string $cepDestino
     */
    public function setCepDestino($cepDestino)
    {
        $this->cepDestino = preg_replace('/[^\d]/', '', $cepDestino);
    }

    /**
     * @return string
     */
    public function getCepOrigem()
    {
        return $this->cepOrigem;
    }

    /**
     * @param string $cepOrigem
     */
    public function setCepOrigem($cepOrigem)
    {
        $this->cepOrigem = preg_replace('/[^\d]/', '', $cepOrigem);
    }

    /**
     * @return ServicoDePostagem[]
     */
    public function getServicos()
    {
        return $this->servicos;
    }

    /**
     * @param \PhpSigep\Model\ServicoDePostagem|\PhpSigep\Model\ServicoDePostagem[] $servicos
     * @throws \PhpSigep\InvalidArgument
     */
    public function setServicos($servicos)
    {
        $piece = $servicos;
        if (is_array($servicos)) {
            $piece = null;
            if (count($servicos)) {
                $piece = reset($servicos);
            }
        } else {
            $servicos = array($servicos);
        }
        if ($piece && !($piece instanceof ServicoDePostagem)) {
            throw new InvalidArgument('Este parâmetro precisa ser uma instância de ' .
                '"PhpSigep\Model\ServicoDePostagem" ou um array de "PhpSigep\Model\ServicoDePostagem"');
        }
        
        $this->servicos = $servicos;
    }

    /**
     * @param ServicoDePostagem $servico
     * @return $this
     */
    public function addServico(ServicoDePostagem $servico)
    {
        $this->servicos[] = $servico;
        return $this;
    }
}

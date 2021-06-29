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
     * Padrão true
     * Quando true fará para cada etiqueta solicitada uma requisição para os correios com base no valor de $qtdEtiquetas
     * Quando false incorporará ao XML de solicitação de etiqueta e portanto apenas uma requisição para os correios.
     * @var boolean
     */
    protected $modoMultiplasRequisicoes = true;
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
     * Atribui para modoMultiplasRequisicoes true
     */
    public function setModoMultiplasRequisicoes(){
        $this->modoMultiplasRequisicoes = true;
    }
    
    /**
     * Atribui para modoMultiplasRequisicoes false
     */
    public function setModoUmaRequisicao(){
        $this->modoMultiplasRequisicoes = false;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isModoMultiplasRequisicoes(){
        return $this->modoMultiplasRequisicoes;
    }

    /**
     * @param int|ServicoDePostagem $servicoDePostagem
     * @throws \PhpSigep\InvalidArgument
     */
    public function setServicoDePostagem($servicoDePostagem)
    {
        if (is_string($servicoDePostagem)) {
            $servicoDePostagem = new \PhpSigep\Model\ServicoDePostagem($servicoDePostagem);
        }
        
        if (!($servicoDePostagem instanceof ServicoDePostagem)) {
            throw new InvalidArgument('Serviço de postagem deve ser uma string ou uma instância de ' .
                '\PhpSigep\Model\ServicoDePostagem.');
        }
        
        $this->servicoDePostagem = $servicoDePostagem;
    }

}

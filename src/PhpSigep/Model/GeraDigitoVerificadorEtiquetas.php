<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class GeraDigitoVerificadorEtiquetas extends AbstractModel
{

    /**
     * Opcional.
     * Quando não informado será usado o valor retornado pelo método {@link \PhpSigep\Bootstrap::getConfig() }
     * @var AccessData
     */
    protected $accessData;
    /**
     * @var Etiqueta[]
     */
    protected $etiquetas;

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
     * @param Etiqueta $etiqueta
     */
    public function addEtiqueta($etiqueta)
    {
        $this->etiquetas[] = $etiqueta;
    }

    /**
     * @param Etiqueta[] $etiquetas
     */
    public function setEtiquetas(array $etiquetas)
    {
        $this->etiquetas = $etiquetas;
    }

    /**
     * @return Etiqueta[]
     */
    public function getEtiquetas()
    {
        return (array)$this->etiquetas;
    }

}
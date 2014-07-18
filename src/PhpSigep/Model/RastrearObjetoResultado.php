<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class RastrearObjetoResultado extends AbstractModel
{
    /**
     * @var Etiqueta
     */
    protected $etiqueta;

    /**
     * @var RastrearObjetoEvento[]
     */
    protected $eventos;

    /**
     * @param \PhpSigep\Model\Etiqueta $etiqueta
     * @return $this;
     */
    public function setEtiqueta(\PhpSigep\Model\Etiqueta $etiqueta)
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    /**
     * @return \PhpSigep\Model\Etiqueta
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    /**
     * @param \PhpSigep\Model\RastrearObjetoEvento[] $eventos
     * @return $this;
     */
    public function setEventos(array $eventos)
    {
        $this->eventos = $eventos;

        return $this;
    }
    
    /**
     * @param \PhpSigep\Model\RastrearObjetoEvento[] $eventos
     * @return $this;
     */
    public function addEvento(RastrearObjetoEvento $evento)
    {
        if (!is_array($this->eventos)) {
            $this->eventos = array();
        }
        
        $this->eventos[] = $evento;

        return $this;
    }

    /**
     * @return \PhpSigep\Model\RastrearObjetoEvento[]
     */
    public function getEventos()
    {
        return $this->eventos;
    }

}

<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class Sigla extends AbstractModel
{

    /**
     * @var string
     */
    protected $etiquetaComDv;
    /**
     * @var string
     */
    protected $etiquetaSemDv;
    /**
     * @var int
     */
    protected $dv;



    /**
     * @param string $etiquetaSemDv
     */
    public function setEtiquetaSemDv($etiquetaSemDv)
    {
        $etiquetaSemDv       = str_replace(' ', '', $etiquetaSemDv);
        $this->etiquetaSemDv = $etiquetaSemDv;
    }

    /**
     * @return string
     */
    public function getEtiquetaComDv()
    {
        if (!$this->etiquetaComDv) {
            $semDv               = $this->getEtiquetaSemDv();
            $this->etiquetaComDv = substr($semDv, 0, 10) . $this->getDv() . substr($semDv, 10);
        }

        return $this->etiquetaComDv;
    }

    /**
     * @param string $etiquetaComDv
     */
    public function setEtiquetaComDv($etiquetaComDv)
    {
        $this->etiquetaComDv = $etiquetaComDv;
    }

    /**
     * @return string
     */
    public function getNumeroSemDv()
    {
        return substr($this->getEtiquetaSemDv(), 2, 8);
    }

}
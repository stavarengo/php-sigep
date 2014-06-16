<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */
 
namespace PhpSigep\Model;

class FechaPlpVariosServicosRetorno extends AbstractModel
{
    /**
     * @var int
     */
    protected $idPlp;

    /**
     * @param int $idPlp
     * @return $this;
     */
    public function setIdPlp($idPlp)
    {
        $this->idPlp = $idPlp;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdPlp()
    {
        return (int)$this->idPlp;
    }
} 
<?php

namespace PhpSigep\Model;

/**
 *
 * @author Williamnovak
 */

class FechaSolicitacaoReversaRetorno extends AbstractModel
{
    /**
     * @var int
     */
    protected $numeroColeta;

    /**
     * @param int $numeroColeta
     * @return $this;
     */
    public function setNumeroColeta($numeroColeta)
    {
        $this->numeroColeta = $numeroColeta;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumeroColeta()
    {
        return (int) $this->numeroColeta;
    }
}

<?php

namespace PhpSigep\Model;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class SolicitaPostagemReversaRetorno extends AbstractModel
{
    /**
     * @var object
     */
    public $numeroColeta;

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
        return $this->numeroColeta;
    }

}

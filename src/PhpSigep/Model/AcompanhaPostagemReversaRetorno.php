<?php

namespace PhpSigep\Model;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class AcompanhaPostagemReversaRetorno extends AbstractModel
{
    /**
     * @var object
     */
    protected $coleta;

    /**
     * @param int $coleta
     * @return $this;
     */
    public function setColeta($coleta)
    {
        $this->coleta = $coleta;
        return $this;
    }

    /**
     * @return int
     */
    public function getColeta()
    {
        return $this->coleta;
    }

}

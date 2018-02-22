<?php

namespace PhpSigep\Model;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */

class CancelaSolicitacaoDePostagemReversaRetorno extends AbstractModel
{
    /**
     * @var object
     */
    protected $objetoPostal;

    /**
     * @param int $objetoPostal
     * @return $this;
     */
    public function setObjetoPostal($objetoPostal)
    {
        $this->objetoPostal = $objetoPostal;
        return $this;
    }

    /**
     * @return int
     */
    public function getObjetoPostal()
    {
        return $this->objetoPostal;
    }

}

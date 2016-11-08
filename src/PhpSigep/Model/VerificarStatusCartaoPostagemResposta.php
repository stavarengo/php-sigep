<?php

namespace PhpSigep\Model;

/**
 * @author: davidalves1
 */
class VerificarStatusCartaoPostagemResposta extends AbstractModel
{
    /**
     * @var string
     */
    protected $return;

    /**
     * @param $return
     * @return $this;
     */
    public function setReturn($return)
    {
        $this->return = $return;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getReturn()
    {
        return $this->return;
    }
}
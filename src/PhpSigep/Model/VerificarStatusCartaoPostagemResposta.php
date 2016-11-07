<?php

namespace PhpSigep\Model;

/**
 * @author: davidalves1
 */
class VerificaStatusCartaoPostagemResposta extends AbstractModel
{
    protected $return;

    public function setReturn($return)
    {
        $this->return = $return;

        return $this;
    }
}
<?php

namespace PhpSigep\Model;

/**
 * @author: denisbr
 */
class CancelarObjetoResposta extends AbstractModel
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

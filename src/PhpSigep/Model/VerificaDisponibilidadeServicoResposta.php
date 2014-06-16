<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class VerificaDisponibilidadeServicoResposta extends AbstractModel
{

    /**
     * @var bool
     */
    protected $disponivel = false;

    /**
     * @param boolean $disponivel
     * @return $this;
     */
    public function setDisponivel($disponivel)
    {
        $this->disponivel = $disponivel;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getDisponivel()
    {
        return $this->disponivel;
    }

}
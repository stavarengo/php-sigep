<?php

namespace PhpSigep\Model;

/**
 * @author: Rodrigo Job (rodrigo at econector.com.br)
 */
class LogisticaReversaPedidoResposta extends AbstractModel
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
        print_r($return);
        if (is_object($return->resultado_solicitacao)){
            if (sizeof((array)$return->resultado_solicitacao)>0){
                $this->coletas_solicitadas = $return->resultado_solicitacao;
            }

            if (sizeof((array)$return->coleta)>0){
                $this->coletas_solicitadas = $return->coleta;
            }
        }

        return $this;
    }
    
    /**
     * @return string
     */
    public function getReturn()
    {
        return $this->return;
    }
    
    /**
     * @return string
     */
    public function getColeta()
    {
        return $this->coletas_solicitadas;
    }
}
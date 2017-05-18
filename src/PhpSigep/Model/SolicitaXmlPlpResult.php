<?php
namespace PhpSigep\Model;

/**
 * @author: Cristiano Soares
 * @link: http://comerciobr.com
 */
class SolicitaXmlPlpResult extends AbstractModel
{
    /**
     * @var string
     */
    protected $tipo_arquivo;

    /**
     * @var string
     */
    protected $versao_arquivo;

    /**
     * @var array
     */
    protected $plp;

    /**
     * @var array
     */
    protected $remetente;

    /**
     * @var array
     */
    protected $forma_pagamento;

    /**
     * @var array
     */
    protected $objeto_postal;

    /**
     * @var array armazena o resultado bruto, caso seja necessário para outro fins
     */
    protected $resultArray;
    
    public function __construct(array $initialValues = array())
    {
        $this->resultArray = $initialValues;

        $this->_failIfAtributeNotExiste = false;
        parent::__construct($initialValues);
    }

    /**
     * @return array
     */
    public function getTipoArquivo()
    {
        return $this->tipo_arquivo;
    }

    /**
     * @return array
     */
    public function getVersaoArquivo()
    {
        return $this->versao_arquivo;
    }

    /**
     * @return array
     */
    public function getPlp()
    {
        return $this->plp;
    }

    /**
     * @return array
     */
    public function getRemetente()
    {
        return $this->remetente;
    }

    /**
     * @return array
     */
    public function getFormaPagamento()
    {
        return $this->forma_pagamento;
    }

    /**
     * @return array
     */
    public function getObjetoPostal()
    {
        return $this->objeto_postal;
    }

    /**
     * @return array
     */
    public function getResultArray()
    {
        return $this->resultArray;
    }
}
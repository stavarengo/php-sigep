<?php
namespace PhpSigep\Model;

/**
 * @author: Cristiano Soares
 * @link: http://comerciobr.com
 */
class ListarAgenciasCliqueRetireResult extends AbstractModel
{
    /**
     * @var string
     */
    protected $codigoRetorno;

    /**
     * @var string
     */
    protected $descricaoRetorno;

    /**
     * @var strng
     */
    protected $versao;

    /**
     * @var string
     */
    protected $dataHoraRetorno;

    /**
     * @var array
     */
    protected $listaAgencias;

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
    public function getCodigoRetorno()
    {
        return $this->codigoRetorno;
    }

    /**
     * @return array
     */
    public function getDescricaoRetorno()
    {
        return $this->descricaoRetorno;
    }

    /**
     * @return array
     */
    public function getVersao()
    {
        return $this->versao;
    }

    /**
     * @return array
     */
    public function getDataHoraRetorno()
    {
        return $this->dataHoraRetorno;
    }

    /**
     * @return array
     */
    public function getListaAgencias()
    {
        return $this->listaAgencias;
    }

    /**
     * @return array
     */
    public function getResultArray()
    {
        return $this->resultArray;
    }
}
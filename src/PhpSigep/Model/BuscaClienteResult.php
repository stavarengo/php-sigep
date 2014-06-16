<?php
namespace PhpSigep\Model;

/**
 * O manual de implementação do Correios não documenta todos os campos que o WebSevice retorna.
 * Todos os demais atributos que não estão declarados nesta classe serão inseridos em tempo de execução.
 * Use o método {@link BuscaClienteResult::toArray()} para ver todos os atributo criados dinamicamente ou use o 
 * método {@link BuscaClienteResult::get()} para obter o valor de um atributo específico.
 * 
 * @author: Stavarengo
 */
class BuscaClienteResult extends AbstractModel
{
    /**
     * @var string
     */
    protected $cnpj;
    /**
     * @var \stdClass
     */
    protected $contratos;

    public function __construct(array $initialValues = array())
    {
        $this->_failIfAtributeNotExiste = false;
        parent::__construct($initialValues);
    }

    /**
     * @param string $cnpj
     * @return $this;
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * @return string
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @param \stdClass $contratos
     * @return $this;
     */
    public function setContratos(\stdClass $contratos)
    {
        $this->contratos = $contratos;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getContratos()
    {
        return $this->contratos;
    }
    
}
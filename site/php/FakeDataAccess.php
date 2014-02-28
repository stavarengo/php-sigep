<?php

class FakeDataAccess extends \PhpSigep\Model\AccessData
{

    public function __construct(array $initialValues = array())
    {
        parent::__construct($initialValues);
    }
    
    public static function create(array $initialValues = array())
    {
        $initialValues = array_merge($initialValues, array(
            'codAdministrativo' => '',
            'senha'             => '',
            'usuario'           => '',
            'cartaoPostagem'    => '',
            'cnpjEmpresa'       => '',
            'anoContrato'       => '',
            'numeroContrato'    => '',
            'diretoria'         => new \PhpSigep\Model\Diretoria(\PhpSigep\Model\Diretoria::DIRETORIA_DR_PARANA),
        ));
        return new self($initialValues);
    }
} 
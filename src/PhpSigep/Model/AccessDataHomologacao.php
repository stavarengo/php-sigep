<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
class AccessDataHomologacao extends AccessData
{
    /**
     * Atalho para criar uma {@link AccessData} com os dados do ambiente de homologação.
     */
    public function __construct()
    {
        parent::__construct(
            array(
                'codAdministrativo' => '08082650',
                'usuario'           => 'sigep',
                'senha'             => 'n5f9t8',
                'cartaoPostagem'    => '0057018901',
                'numeroContrato'    => '9912208555',
                'cnpjEmpresa'       => null, // Não consta no manual.
                'anoContrato'       => null, // Não consta no manual.
                'diretoria'         => null, // Não consta no manual.
            )
        );
    }
}
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
                'usuario'           => 'sigep',
                'senha'             => 'n5f9t8',
                'codAdministrativo' => '08082650',
                'numeroContrato'    => '9912208555',
                'cartaoPostagem'    => '0057018901',
                'cnpjEmpresa'       => '34028316000103', // Obtido no método 'buscaCliente'.
                'anoContrato'       => null, // Não consta no manual.
                'diretoria'         => new Diretoria(Diretoria::DIRETORIA_DR_BRASILIA), // Obtido no método 'buscaCliente'.
            )
        );
        try {\PhpSigep\Bootstrap::getConfig()->setEnv(\PhpSigep\Config::ENV_DEVELOPMENT);} catch (\Exception $e) {}
    }
}

/*

        parent::__construct(
            array(
                'usuario'           => '9912330055',
                'senha'             => 'a2k4e5',
                'codAdministrativo' => '13296531',
                'numeroContrato'    => '9912330055',
                'cartaoPostagem'    => '0067362850',
                'cnpjEmpresa'       => '79018982000107', // Obtido no método 'buscaCliente'.
                'anoContrato'       => 2013, // Não consta no manual.
                'diretoria'         => new Diretoria(Diretoria::DIRETORIA_DR_SANTA_CATARINA), // Obtido no método 'buscaCliente'.
            )
        );
        try {\PhpSigep\Bootstrap::getConfig()->setEnv(\PhpSigep\Config::ENV_PRODUCTION);} catch (\Exception $e) {}

*/

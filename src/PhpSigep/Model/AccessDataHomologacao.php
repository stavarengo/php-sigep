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
                'codAdministrativo' => '17000190',
                'numeroContrato'    => '9992157880',
                'cartaoPostagem'    => '0067599079',
                'cnpjEmpresa'       => '34028316000103', // Obtido no método 'buscaCliente'.
                'anoContrato'       => null, // Não consta no manual.
                'diretoria'         => new Diretoria(Diretoria::DIRETORIA_DR_BRASILIA), // Obtido no método 'buscaCliente'.
            )
        );
        try {\PhpSigep\Bootstrap::getConfig()->setEnv(\PhpSigep\Config::ENV_DEVELOPMENT);} catch (\Exception $e) {}
    }
}

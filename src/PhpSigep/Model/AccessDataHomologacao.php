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
                'cnpjEmpresa'       => null, // Não consta no manual.
                'anoContrato'       => null, // Não consta no manual.
                'diretoria'         => new Diretoria(Diretoria::DIRETORIA_DR_RIO_DE_JANEIRO), // Não consta no manual, mas precisamos setar um valor para conseguir imprimir as etiquetas.
            )
        );
        try {\PhpSigep\Bootstrap::getConfig()->setEnv(\PhpSigep\Config::ENV_DEVELOPMENT);} catch (\Exception $e) {}
    }
}
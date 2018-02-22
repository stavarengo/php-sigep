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
    public function __construct($reverso = false)
    {
        $data = array(
            'usuario'           => ($reverso ? 'empresacws' : 'sigep'),
            'senha'             => ($reverso ? '123456' : 'n5f9t8'),
            'codAdministrativo' => ($reverso ? '17000190' : '08082650'),
            'numeroContrato'    => ($reverso ? '9992157880' : '9912208555'),
            'cartaoPostagem'    => ($reverso ? '0067599079' : '0057018901'),
            'codigoServico'     => ($reverso ? '04677' : ''),
            'cnpjEmpresa'       => '34028316000103', // Obtido no método 'buscaCliente'.
            'anoContrato'       => null, // Não consta no manual.
            'diretoria'         => new Diretoria(Diretoria::DIRETORIA_DR_BRASILIA), // Obtido no método 'buscaCliente'.
        );
        parent::__construct($data);
        try {\PhpSigep\Bootstrap::getConfig()->setEnv(\PhpSigep\Config::ENV_DEVELOPMENT);} catch (\Exception $e) {}
    }
}

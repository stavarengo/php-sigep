<?php
namespace PhpSigep\Model;
use PhpSigep\Bootstrap;
use PhpSigep\Config;

/**
 * @author: Barreirinha (barreirinha@gmail.com)
 */
class AccessDataLaravel extends AccessData
{
    public $dados_sigep;
    /**
     * Atalho para criar uma {@link AccessData} com os dados do ambiente de homologação.
     */
    public function __construct($producao = null)
    {
        $this->dados_sigep = [
            'usuario'           => $producao ?  env('SIGEP_Usuario') :  'sigep',
            'senha'             => $producao ?  env('SIGEP_Senha') :  'n5f9t8',
            'codAdministrativo' => $producao ?  env('SIGEP_Codigo') :  '17000190',
            'numeroContrato'    => $producao ?  env('SIGEP_Contrato') :  '9992157880',
            'cartaoPostagem'    => $producao ?  env('SIGEP_Cartao') :  '0067599079',
            'cnpjEmpresa'       => $producao ?  env('SIGEP_CNPJ') :  '34028316000103', // Obtido no método 'buscaCliente'.
            'anoContrato'       => $producao ?  env('SIGEP_AnoContrato') :  null, // Não consta no manual.
            'diretoria'         => new Diretoria($producao ?  env('SIGEP_Diretoria') :  Diretoria::DIRETORIA_DR_BRASILIA), // Obtido no método 'buscaCliente'.
        ];

        parent::__construct($this->dados_sigep);

        $config = new Config();
        $config->setAccessData($this);
        $ambiente = $producao ? Config::ENV_PRODUCTION : Config::ENV_DEVELOPMENT;
        $config->setEnv($ambiente);
        $config->setCacheOptions(
            array(
                'storageOptions' => array(
                    // Qualquer valor setado neste atributo será mesclado ao atributos das classes
                    // "\PhpSigep\Cache\Storage\Adapter\AdapterOptions" e "\PhpSigep\Cache\Storage\Adapter\FileSystemOptions".
                    // Por tanto as chaves devem ser o nome de um dos atributos dessas classes.
                    'enabled' => false,
                    'ttl' => 10,// "time to live" de 10 segundos
                    'cacheDir' => sys_get_temp_dir(), // Opcional. Quando não inforado é usado o valor retornado de "sys_get_temp_dir()"
                ),
            )
        );

        Bootstrap::start($config);

    }
}

<?php

// Altera as configurações do PHP para mostrar todos os erros, já que este é apenas um script de exemplo.
// No seu ambiente de produção, você não vai precisar alterar estas configurações.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', 'E_ALL|E_STRICT');
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}
if (!class_exists('PhpSigepFPDF')) {
    throw new RuntimeException(
        'Não encontrei a classe PhpSigepFPDF. Execute "php composer.phar install" ou baixe o projeto ' .
        'https://github.com/stavarengo/php-sigep-fpdf manualmente e adicione a classe no seu path.'
    );
}

// Configura o php-sigep

// Se você não usou o composer é necessário carregar o script Boostrap.php manualmente.
// Caso você tenha usado o composer o Bootstrap PHP será carregado automáticamente pelo autoload (quando necessário).
require_once __DIR__ . '/../src/PhpSigep/Bootstrap.php';

$accessDataParaAmbienteDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();

$config = new \PhpSigep\Config();
$config->setAccessData($accessDataParaAmbienteDeHomologacao);
$config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);
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

\PhpSigep\Bootstrap::start($config);
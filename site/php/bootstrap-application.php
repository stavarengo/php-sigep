<?php
date_default_timezone_set('America/Sao_Paulo');

//require_once __DIR__ . '/fpdf17/fpdf.php';
//require_once __DIR__ . '/php-sigep/src/PhpSigep/Bootstrap.php';

require __DIR__ . '/vendor/autoload.php';

$accessDataParaAmbienteDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();

$config = new \PhpSigep\Config();
$config->setAccessData($accessDataParaAmbienteDeHomologacao);
$config->setEnv(\PhpSigep\Config::ENV_DEVELOPMENT);
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


$baseUrl = (isset($_SERVER['PHP_SELF']) ? dirname($_SERVER['PHP_SELF']) : '/');
$baseUrl = str_replace('\\', '/', $baseUrl);
if ($baseUrl == '/') $baseUrl = '';

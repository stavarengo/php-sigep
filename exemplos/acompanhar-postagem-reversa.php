<?php

    // Altera as configurações do PHP para mostrar todos os erros, já que este é apenas um script de exemplo.
    // No seu ambiente de produção, você não vai precisar alterar estas configurações.
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('error_reporting', 'E_ALL|E_STRICT');
    error_reporting(E_ALL);

    header('Content-Type: text/html; charset=utf-8');

    require_once '../src/PhpSigep/Bootstrap.php';
    $accessDataParaAmbienteDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao(true /* logistica reversa */);

    $config = new \PhpSigep\Config();
    $config->setLogisticaReversa(true);
    $config->setAccessData($accessDataParaAmbienteDeHomologacao);
    $config->setEnv(\PhpSigep\Config::ENV_DEVELOPMENT, true);
    \PhpSigep\Bootstrap::start($config);

    $acompanhaPostagem = new \PhpSigep\Model\AcompanhaPostagemReversa();
    $acompanhaPostagem->setAccessData($accessDataParaAmbienteDeHomologacao);
    $acompanhaPostagem->setTipoBusca('H');
    $acompanhaPostagem->setTipoSolicitacao('A');
    $acompanhaPostagem->setNumeroPedido('232574125');

    $phpSigep = new \PhpSigep\Services\SoapClient\Real();
    $result = $phpSigep->acompanharPostagemReversa($acompanhaPostagem);

    echo "<pre>";
    print_r($result);
    echo "</pre>";

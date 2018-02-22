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
    $config->setEnv(\PhpSigep\Config::ENV_DEVELOPMENT, true, true);
    \PhpSigep\Bootstrap::start($config);

    $destinatario = new \PhpSigep\Model\Destinatario();
    $destinatario->setNome('Usuário Destinatário');
    $destinatario->setLogradouro('Avenida Morumbi');
    $destinatario->setNumero('2500');
    $destinatario->setComplemento('911');
    $destinatario->setReferencia('referencia de teste');
    $destinatario->setCidade('São Paulo');
    $destinatario->setUf('SP');
    $destinatario->setCep('05606200');
    $destinatario->setBairro('Morumbi');
    $destinatario->setDdd('41');
    $destinatario->setTelefone('123456789');
    $destinatario->setEmail('teste@teste.com');

    $remetente = new \PhpSigep\Model\Remetente();
    $remetente->setNome('Usuário Remetente');
    $remetente->setLogradouro('Avenida Vicente Machado');
    $remetente->setNumero('15');
    $remetente->setComplemento('911');
    $remetente->setReferencia('referencia de teste');
    $remetente->setCidade('Curitiba');
    $remetente->setBairro('Centro');
    $remetente->setUf('PR');
    $remetente->setCep('80420010');
    $remetente->setDdd('41');
    $remetente->setTelefone('123456789');
    $remetente->setDddCelular('41');
    $remetente->setCelular('123456789');
    $remetente->setEmail('teste@teste.com');
    $remetente->setIdentificacao('26355508830');
    $remetente->setSms('S');

    $produto = new \PhpSigep\Model\Produto();
    $produto->setCodigo(116600403);
    $produto->setTipo(0);
    $produto->setQtd(1);

    $objCol = new \PhpSigep\Model\ObjCol;
    $objCol->setId(123456);
    $objCol->setNum('');
    $objCol->setEntrega('');
    $objCol->setItem(1);
    $objCol->setDesc('');

    $coletasSolicitadas = new \PhpSigep\Model\ColetasSolicitadas();
    $coletasSolicitadas->setTipo('A');
    $coletasSolicitadas->setNumero('');
    $coletasSolicitadas->setIdCliente(2325167);
    $coletasSolicitadas->setValorDeclarado(null);
    $coletasSolicitadas->setServicoAdicional('');
    $coletasSolicitadas->setAr(1);
    $coletasSolicitadas->setAg(10);
    $coletasSolicitadas->setRemetente($remetente);
    $coletasSolicitadas->setObjCol($objCol);

    $postagem = new \PhpSigep\Model\SolicitaPostagemReversa();
    $postagem->setAccessData($accessDataParaAmbienteDeHomologacao);
    $postagem->setDestinatario($destinatario);
    $postagem->setColetasSolicitadas($coletasSolicitadas);

    $phpSigep = new \PhpSigep\Services\SoapClient\Real();
    $result = $phpSigep->solicitarPostagemReversa($postagem);

    echo "<pre>";
    print_r($result);
    echo "</pre>";

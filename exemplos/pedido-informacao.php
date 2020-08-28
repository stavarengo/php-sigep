<?php

/**
 * Para utilizar essa funcionalidade, é necessário habilitar o componente Fale Conosco no id Correios,
 * solicite para o seu gerente de conta nos Correios.
 */

// Dados de Acesso
$accessData = new \PhpSigep\Model\AccessData();
$accessData->setIdCorreiosUsuario('usuario do id correios');
$accessData->setIdCorreiosSenha('senha dos componentes no id correios');
$accessData->setNumeroContrato('numero do seu contrato com os correios');
$accessData->setCartaoPostagem('seu cartao de postagem');

// Configurações
$config = new \PhpSigep\Config();
$config->setAccessData($accessData);
$config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);
// $config->setWsdlPI('pedidoInformacaoWS.xml'); // para acesso local, baixar o wsdl

// Bootstrap
\PhpSigep\Bootstrap::start($config);

// Pedido de Informação
$pedidoInformacao = new \PhpSigep\Model\PedidoInformacao();
$pedidoInformacao->setTelefone('telefone do cliente');
$pedidoInformacao->setCodigoObjeto('código de rastreamento');
$pedidoInformacao->setEmailResposta('e-mail do cliente');
$pedidoInformacao->setNomeDestinatario('nome do cliente');

$phpSigep = new \PhpSigep\Services\SoapClient\Real();
$result = $phpSigep->cadastrarPi($pedidoInformacao);

$result = $phpSigep->cadastrarPi($pedidoInformacao);

// Obtem o objeto retornado
$pedidoInformacaoResponse = $result->getResult();

var_dump($pedidoInformacaoResponse);

?>
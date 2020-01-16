<?php
require_once __DIR__ . '/bootstrap-exemplos.php';

$accessDataDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();
$usuario = trim((isset($_GET['usuario']) ? $_GET['usuario'] : $accessDataDeHomologacao->getUsuario()));
$senha = trim((isset($_GET['senha']) ? $_GET['senha'] : $accessDataDeHomologacao->getSenha()));
$cnpjEmpresa = $accessDataDeHomologacao->getCnpjEmpresa();

$accessData = new \PhpSigep\Model\AccessData();
$accessData->setUsuario($usuario);
$accessData->setSenha($senha);
$accessData->setCnpjEmpresa($cnpjEmpresa);

$params = new \PhpSigep\Model\SolicitaEtiquetas();
$params->setQtdEtiquetas(1);
$params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_E_SEDEX_STANDARD);
$params->setAccessData($accessData);

$phpSigep = new PhpSigep\Services\SoapClient\Real();

?>
<!doctype html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Exemplo de Solicitação de Etiquetas - PHP Sigep</title>
    </head>
    <body>
        <h1>Resposta</h1>
        <hr/>
        <pre><?php var_dump($phpSigep->solicitaEtiquetas($params)); ?></pre>
    </body>
</html>
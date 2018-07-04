<?php

require '../vendor/autoload.php';

$accessData = new \PhpSigep\Model\AccessDataHomologacao();
$accessData->setIdCorreiosUsuario('USUÀRIO'); // Não consegui um de teste
$accessData->setIdCorreiosSenha('SENHA'); // Não consegui um de teste

$config = new \PhpSigep\Config();
$config->setAccessData($accessData);

\PhpSigep\Bootstrap::start($config);

$phpSigep = new PhpSigep\Services\SoapClient\Real();

$uf = (isset($_GET['uf']) ? $_GET['uf'] : 'RJ');
$municipio = (isset($_GET['municipio']) ? $_GET['municipio'] : 'Rio de Janeiro');
$bairro = (isset($_GET['bairro']) ? $_GET['bairro'] : 'Centro');
$cep = (isset($_GET['cep']) ? $_GET['cep'] : false);

if ($cep) {
    $result = $phpSigep->listarAgenciasCliqueRetireByCep($cep);
} else {
    $result = $phpSigep->listarAgenciasCliqueRetire($uf, $municipio, $bairro);
}

$dumpResult = print_r($result, true);
?>
<!doctype html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Exemplo Consulta CEP - PHP Sigep</title>
    </head>
    <body>
        <br/>
        <form action="" method="get">
            <input type="text" name="uf" placeholder="uf" value="<?php echo htmlspecialchars($uf, ENT_QUOTES); ?>"/>
            <br />
            <input type="text" name="municipio" placeholder="municipio" value="<?php echo htmlspecialchars($municipio, ENT_QUOTES); ?>"/>
            <br />
            <input type="text" name="bairro" placeholder="bairro" value="<?php echo htmlspecialchars($bairro, ENT_QUOTES); ?>"/>
            <br />
            OU
            <br />
            <input type="text" name="cep" placeholder="cep" value="<?php echo htmlspecialchars($cep, ENT_QUOTES); ?>"/>
            <br />
            <button type="submit">Listar Agências clique e retire</button>
        </form>
        <br/>
        <hr/>
        <pre>
        <?php echo $dumpResult ?>
    </body>
</html>
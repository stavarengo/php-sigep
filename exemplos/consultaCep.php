<?php

require_once __DIR__ . '/bootstrap-exemplos.php';

$cep = (isset($_GET['cep']) ? $_GET['cep'] : '30170-010');

$phpSigep = new PhpSigep\Services\SoapClient\Real();
$result = $phpSigep->consultaCep($cep);

var_dump((array)$result);
$dumpResult = ob_get_clean();
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
            <input type="text" pattern="\d{5}-{0,1}\d{3}" maxlength="9" name="cep" value="<?php echo htmlspecialchars($cep, ENT_QUOTES); ?>"/>
            <button type="submit">Consulta CEP</button>
        </form>
        <br/>
        <hr/>
        <?php echo $dumpResult ?>
    </body>
</html>
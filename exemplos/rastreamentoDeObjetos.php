<?php

require_once __DIR__ . '/bootstrap-exemplos.php';


$etiquetasFromQueryRaw = trim((isset($_GET['etiquetas']) ? $_GET['etiquetas'] : ''));
$etiquetas = array();
if ($etiquetasFromQueryRaw) {
    $etiquetasFromQuery = explode(',', $etiquetasFromQueryRaw);
    foreach ($etiquetasFromQuery as $etiquetaFromQuery) {
        $etiqueta = new \PhpSigep\Model\Etiqueta();
        $etiqueta->setEtiquetaComDv(trim($etiquetaFromQuery));
        $etiquetas[] = $etiqueta;
    }
}

if (count($etiquetas)) {
    $accessDataDeHomologacao = new \PhpSigep\Model\AccessDataHomologacao();
    $accessDataDeHomologacao->setUsuario('ECT');// Usuário e senha para teste passado no manual
    $accessDataDeHomologacao->setSenha('SRO');
    
    $params = new \PhpSigep\Model\RastrearObjeto();
    $params->setAccessData($accessDataDeHomologacao);
    $params->setEtiquetas($etiquetas);
        
    $phpSigep = new PhpSigep\Services\SoapClient\Real();
    $result = $phpSigep->rastrearObjeto($params);
    
    var_dump((array)$result);
    $dumpResult = ob_get_clean();
}
?>
<!doctype html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Exemplo Rastrear Objetos - PHP Sigep</title>
    </head>
    <body>
        <form action="" method="get">
            <br/>
            <label for="etiquetas">Etiquetas - separadas por vírgula</label>
            <br/>
            <textarea name="etiquetas" id="etiquetas" cols="60" rows="3"><?php echo htmlspecialchars($etiquetasFromQueryRaw); ?></textarea>
            <br/>
            <button type="submit">Rastrear</button>
        </form>
        <br/>
        <hr/>
        <h1>Resposta</h1>
        <?php
            if (count($etiquetas)) {
                echo $dumpResult;
            } else {
                echo 'Informe o código de rastreamento e clique no botão "Rastrear"';
            }
        ?>
    </body>
</html>
<?php

$etiquetas = explode("\n", $_POST['etiquetas']);

if (count($etiquetas) > 10) {
    die(json_encode(array('errorMsg' => 'Não peça mais do que 10 etiquetas.')));
}

$etiquetasArray = array();
foreach ($etiquetas as $etiquetaSemDv) {
    $etiqueta = new \PhpSigep\Model\Etiqueta();
    $etiqueta->setEtiquetaSemDv($etiquetaSemDv);
    $etiquetasArray[] = $etiqueta;

}

$params = new \PhpSigep\Model\GeraDigitoVerificadorEtiquetas();
$params->setAccessData(FakeDataAccess::create());
$params->setEtiquetas($etiquetasArray);

$servico       = new \PhpSigep\Services\GeraDigitoVerificadorEtiquetas();
$serviceResult = $servico->execute($params);
$r             = array();
foreach ($serviceResult as $result) {
    $r[$result->getEtiquetaSemDv()] = $result->getDv();
}

$r = array(
    'resultado' => $r,
);

if (defined('JSON_PRETTY_PRINT')) {
    die(json_encode($r, JSON_PRETTY_PRINT));
} else {
    die(json_encode($r));
}

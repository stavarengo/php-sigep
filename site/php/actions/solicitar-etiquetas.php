<?php

$qtdEtiquetas      = (int)$_POST['qtdEtiquetas'];
$servicoDePostagem = $_POST['servicoDePostagem'];

if ($qtdEtiquetas > 10) {
    die(json_encode(array('errorMsg' => 'Não peça mais do que 10 etiquetas.')));    
}

$params = new \PhpSigep\Model\SolicitaEtiquetas();
$params->setAccessData(\PhpSigep\Bootstrap::getConfig()->getAccessData());
$params->setQtdEtiquetas($qtdEtiquetas);
$params->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem($servicoDePostagem));

$phpSigep = new PhpSigep\Services\SoapClient\Real();
$serviceResult = $phpSigep->solicitaEtiquetas($params);
$r = array();
foreach ($serviceResult as $result) {
    $r[] = $result->getEtiquetaComDv();
}

$r = array(
    'resultado' => $r,
);

if (defined('JSON_PRETTY_PRINT')) {
    die(json_encode($r, JSON_PRETTY_PRINT));
} else {
    die(json_encode($r));
}

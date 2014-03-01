<?php

$qtdEtiquetas      = (int)$_POST['qtdEtiquetas'];
$servicoDePostagem = $_POST['servicoDePostagem'];

if ($qtdEtiquetas > 10) {
    die(json_encode(array('errorMsg' => 'Não peça mais do que 10 etiquetas.'), JSON_PRETTY_PRINT));    
}

$params = new \PhpSigep\Model\SolicitaEtiquetas();
$params->setAccessData(FakeDataAccess::create());
$params->setQtdEtiquetas($qtdEtiquetas);
$params->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem($servicoDePostagem));

$servico  = new \PhpSigep\Services\SolicitaEtiquetas();
$serviceResult = $servico->execute($params);
$r = array();
foreach ($serviceResult as $result) {
    $r[] = $result->getEtiquetaComDv();
}

$r = array(
    'resultado' => $r,
);

die(json_encode($r, JSON_PRETTY_PRINT));

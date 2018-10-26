<?php

require_once __DIR__ . '/bootstrap-exemplos.php';

$params = include __DIR__ . '/helper-criar-pre-lista-a4.php';

// Logo da empresa remetente
$logoFile = __DIR__ . '/logo-etiqueta-2016.png';

$pdf = new \PhpSigep\Pdf\CartaoDePostagem2018($params, time(), $logoFile, array());
$pdf->render('F', 'teste.pdf');

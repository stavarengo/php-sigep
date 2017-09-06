<?php

require_once __DIR__ . '/bootstrap-exemplos.php';

$params = include __DIR__ . '/helper-criar-pre-lista-a4.php';

$pdf = new \PhpSigep\Pdf\AvisoRecebimento($params);
$pdf->render();

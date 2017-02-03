<?php

require_once __DIR__ . '/bootstrap-exemplos.php';

$params = include __DIR__ . '/helper-criar-pre-lista.php';

$pdf  = new \PhpSigep\Pdf\ListaDePostagem($params, time());
$pdf->render('I');

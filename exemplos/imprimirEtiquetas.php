<?php

require_once __DIR__ . '/bootstrap-exemplos.php';

$params = include __DIR__ . '/helper-criar-pre-lista.php';

// Logo da empresa remetente
$logoFile = __DIR__ . '/logo-etiqueta.png';

$pdf = new \PhpSigep\Pdf\CartaoDePostagem($params, time(), $logoFile);
$pdf->render($params);

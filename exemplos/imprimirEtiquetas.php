<?php

require_once __DIR__ . '/bootstrap-exemplos.php';

$params = include __DIR__ . '/helper-criar-pre-lista.php';

// Logo da empresa remetente
$logoFile = __DIR__ . '/logo-etiqueta.png';

//Parametro opcional indica qual layout utilizar para a chancela. Ex.: CartaoDePostagem::TYPE_CHANCELA_CARTA, CartaoDePostagem::TYPE_CHANCELA_CARTA_2016
$layoutChancela = array(\PhpSigep\Pdf\CartaoDePostagem::TYPE_CHANCELA_CARTA);

$pdf = new \PhpSigep\Pdf\CartaoDePostagem($params, time(), $logoFile, $layoutChancela);
$pdf->render();

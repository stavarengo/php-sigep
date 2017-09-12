<?php

require_once __DIR__ . '/bootstrap-exemplos.php';

$params = include __DIR__ . '/helper-criar-pre-lista-a4.php';

// Logo da empresa remetente
$logoFile = __DIR__ . '/logo-etiqueta-2016.png';

//Parametro opcional indica qual layout utilizar para a chancela. Ex.: CartaoDePostagem::TYPE_CHANCELA_CARTA, CartaoDePostagem::TYPE_CHANCELA_CARTA_2016
$layoutChancela = array(); //array(\PhpSigep\Pdf\CartaoDePostagem2016::TYPE_CHANCELA_SEDEX_2016);

$pdf = new \PhpSigep\Pdf\CartaoDePostagem2016($params, time(), $logoFile, $layoutChancela);
$pdf->render();

<?php
require_once __DIR__ . '/bootstrap-exemplos.php';

$params = include __DIR__ . '/helper-criar-pre-lista.php';

$phpSigep = new PhpSigep\Services\SoapClient\Real();
$result = $phpSigep->fechaPlpVariosServicos($params);

echo '<pre>';
print_r((array)$result);
echo '</pre>';

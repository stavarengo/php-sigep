<?php

require_once __DIR__ . '/bootstrap-exemplos.php';

$cep = (isset($_GET['cep']) ? $_GET['cep'] : '95350130');

$phpSigep = new PhpSigep\Services\SoapClient\Real();
$result = $phpSigep->consultaCep($cep);

var_dump((array)$result);

echo $dumpResult;

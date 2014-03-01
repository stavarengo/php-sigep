<?php
date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/fpdf17/fpdf.php';
require_once __DIR__ . '/php-sigep/src/PhpSigep/Bootstrap.php';

\PhpSigep\Bootstrap::start(new \PhpSigep\Config(array(
    'isDebug' => true,
    'simular' => true,
)));


$baseUrl = (isset($_SERVER['PHP_SELF']) ? dirname($_SERVER['PHP_SELF']) : '/');
$baseUrl = str_replace('\\', '/', $baseUrl);
if ($baseUrl == '/') $baseUrl = '';
require_once __DIR__ . '/FakeDataAccess.php';

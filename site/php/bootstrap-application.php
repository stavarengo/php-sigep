<?php
require_once __DIR__ . '/fpdf17/fpdf.php';
require_once __DIR__ . '/php-sigep/src/PhpSigep/Bootstrap.php';

\PhpSigep\Bootstrap::start(new \PhpSigep\Config(array(
    'isDebug' => true,
    'simular' => true,
)));

require_once __DIR__ . '/FakeDataAccess.php';

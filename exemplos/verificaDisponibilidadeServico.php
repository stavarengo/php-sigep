<?php

require_once __DIR__ . '/bootstrap-exemplos.php';

$params = new \PhpSigep\Model\VerificaDisponibilidadeServico();
$params->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());
$params->setCepOrigem('30170-010');
$params->setCepDestino('04538-132');
$params->setServicos(\PhpSigep\Model\ServicoDePostagem::getAll());

$phpSigep = new PhpSigep\Services\SoapClient\Real();
$result = $phpSigep->verificaDisponibilidadeServico($params);

var_dump((array)$result);
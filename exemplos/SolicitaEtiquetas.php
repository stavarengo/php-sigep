<?php
require_once __DIR__ . '/../vendor/autoload.php';

$service = new PhpSigep\Services\SolicitaEtiquetas();

$params = new \PhpSigep\Model\SolicitaEtiquetas(array(
	'qtdEtiquetas'      => 1,
	'servicoDePostagem' => new PhpSigep\Model\ServicoDePostagem(PhpSigep\Model\ServicoDePostagem::SERVICE_E_SEDEX),
	'accessData'        => new PhpSigep\Model\AccessData(array(
		'cnpjEmpresa' => '19.852.175/0001-55',
		'usuario'     => 'usuario',
		'senha'       => 'senha',
	))
));

var_dump($service->execute($params));

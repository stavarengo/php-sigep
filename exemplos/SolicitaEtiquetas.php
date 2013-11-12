<?php
require_once __DIR__ . '/../vendor/autoload.php';

$service = new Sigep\Services\SolicitaEtiquetas();

$params = new \Sigep\Model\SolicitaEtiquetas(array(
	'qtdEtiquetas' => 1,
	'servicoDePostagem' => new Sigep\Model\ServicoDePostagem( Sigep\Model\ServicoDePostagem::SERVICE_E_SEDEX ),
	'accessData' => new Sigep\Model\AccessData(array(
		'cnpjEmpresa' => '19.852.175/0001-55',
		'usuario' => 'usuario',
		'senha' => 'senha',
	))
));

var_dump($service->execute($params));

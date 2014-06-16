<?php
require_once __DIR__ . '/bootstrap-exemplos.php';

$params = new \PhpSigep\Model\SolicitaEtiquetas();
$params->setQtdEtiquetas(1);
$params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_E_SEDEX_STANDARD);
$params->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());

$phpSigep = new PhpSigep\Services\SoapClient\Real();

echo <<<HTML
    <div style="display:inline-block;color: red;font-weight: bold;border: 1px solid silver;padding: 20px;background-color: #fffcfc">
        O Correios sempre retorna "A autenticacao de sigep falhou!" quando solicitamos etiquetas
        usando o servidor de homologação.
    </div>
HTML;

var_dump($phpSigep->solicitaEtiquetas($params));

<?php

$peso                           = $_POST['peso'];
$servicosDePostagem             = $_POST['servicosDePostagem'];
$remetenteCep                   = $_POST['remetenteCep'];
$destinatarioCep                = $_POST['destinatarioCep'];
$servicosAdicionaisSelecionados = $_POST['servicosAdicionais'];

$servicosDePostagemArray = array();
foreach ($servicosDePostagem as $tipo) {
    $servicosDePostagemArray[] = new \PhpSigep\Model\ServicoDePostagem($tipo);
}

$servicosAdicionais = array();
foreach ($servicosAdicionaisSelecionados as $servicoAdicional) {
    $valorDeclarado = null;
    if ($servicoAdicional == 'mp') {
        $codServicosAdicional = \PhpSigep\Model\ServicoAdicional::SERVICE_MAO_PROPRIA;
    } else if ($servicoAdicional == 'vd') {
        $codServicosAdicional = \PhpSigep\Model\ServicoAdicional::SERVICE_VALOR_DECLARADO;
        $valorDeclarado       = (float)$_POST['valorDeclarado'];
    } else if ($servicoAdicional == 'ar') {
        $codServicosAdicional = \PhpSigep\Model\ServicoAdicional::SERVICE_AVISO_DE_RECEBIMENTO;
    } else {
        continue;
    }
    $servicosAdicionais[] = new \PhpSigep\Model\ServicoAdicional(array(
        'codigoServicoAdicional' => $codServicosAdicional,
        'valorDeclarado'         => $valorDeclarado,
    ));
}

$dimensao = new \PhpSigep\Model\Dimensao();
$dimensao->setAltura(20);
$dimensao->setLargura(20);
$dimensao->setComprimento(20);
$dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);

$params = new \PhpSigep\Model\CalcPrecoPrazo();
$params->setServicosPostagem($servicosDePostagemArray);
$params->setServicosAdicionais($servicosAdicionais);
$params->setPeso($peso);
$params->setDimensao($dimensao);
$params->setCepOrigem($remetenteCep);
$params->setCepDestino($destinatarioCep);
$params->setAccessData(\PhpSigep\Bootstrap::getConfig()->getAccessData());

$phpSigep = new PhpSigep\Services\SoapClient\Real();
$r = $phpSigep->calcPrecoPrazo($params);

$help = file_get_contents(__DIR__ . '/calc-preco-prazo.help.html');

$r = array(
    'resultado' => $r->toArray(),
    'help'      => $help,
);
if (defined('JSON_PRETTY_PRINT')) {
    die(json_encode($r, JSON_PRETTY_PRINT));
} else {
    die(json_encode($r));
}

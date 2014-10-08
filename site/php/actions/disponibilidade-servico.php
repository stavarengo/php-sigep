<?php

$remetenteCep      = $_POST['remetenteCep'];
$destinatarioCep   = $_POST['destinatarioCep'];
$servicoDePostagem = $_POST['servicoDePostagem'];

$servicoPostagem = new \PhpSigep\Model\ServicoDePostagem($servicoDePostagem);

$dimensao = new \PhpSigep\Model\Dimensao();
$dimensao->setAltura(20);
$dimensao->setLargura(20);
$dimensao->setComprimento(20);
$dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);

$params = new \PhpSigep\Model\VerificaDisponibilidadeServico();
$params->setCepDestino($destinatarioCep);
$params->setCepOrigem($remetenteCep);
$params->setServicos(array($servicoPostagem));
$params->setAccessData(\PhpSigep\Bootstrap::getConfig()->getAccessData());

$phpSigep = new PhpSigep\Services\SoapClient\Real();
$r = $phpSigep->verificaDisponibilidadeServico($params);

$r = array(
    'resultado' => $r->toArray(),
);
if (defined('JSON_PRETTY_PRINT')) {
    die(json_encode($r, JSON_PRETTY_PRINT));
} else {
    die(json_encode($r));
}

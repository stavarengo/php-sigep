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
$params->setServico($servicoPostagem);
$params->setAccessData(FakeDataAccess::create());

$servico = new \PhpSigep\Services\VerificaDisponibilidadeServico();

$r = $servico->execute($params);

die(json_encode($r, JSON_PRETTY_PRINT));

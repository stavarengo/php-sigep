<?php


$peso                           = $_POST['peso'];
$servicoDePostagem              = $_POST['servicoDePostagem'];
$remetenteNome                  = $_POST['remetenteNome'];
$remetenteLogradouro            = $_POST['remetenteLogradouro'];
$remetenteNumero                = $_POST['remetenteNumero'];
$remetenteComplemento           = $_POST['remetenteComplemento'];
$remetenteBairro                = $_POST['remetenteBairro'];
$remetenteCidade                = $_POST['remetenteCidade'];
$remetenteEstado                = $_POST['remetenteEstado'];
$remetenteCep                   = $_POST['remetenteCep'];
$destinatarioNome               = $_POST['destinatarioNome'];
$destinatarioLogradouro         = $_POST['destinatarioLogradouro'];
$destinatarioNumero             = $_POST['destinatarioNumero'];
$destinatarioComplemento        = $_POST['destinatarioComplemento'];
$destinatarioBairro             = $_POST['destinatarioBairro'];
$destinatarioCidade             = $_POST['destinatarioCidade'];
$destinatarioEstado             = $_POST['destinatarioEstado'];
$destinatarioCep                = $_POST['destinatarioCep'];
$servicosAdicionaisSelecionados = $_POST['servicosAdicionais'];
$relatorio = $_POST['relatorio'];

$servicoDePostagem = new \PhpSigep\Model\ServicoDePostagem($servicoDePostagem);

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

$accessData = \PhpSigep\Bootstrap::getConfig()->getAccessData();

$destinatario = new \PhpSigep\Model\Destinatario();
$destinatario->setNome($destinatarioNome);
$destinatario->setLogradouro($destinatarioLogradouro);
$destinatario->setNumero($destinatarioNumero);
$destinatario->setComplemento($destinatarioComplemento);

$destino = new \PhpSigep\Model\DestinoNacional();
$destino->setBairro($destinatarioBairro);
$destino->setCep($destinatarioCep);
$destino->setCidade($destinatarioCidade);
$destino->setUf($destinatarioEstado);

$etiqueta = new \PhpSigep\Model\Etiqueta();
$etiqueta->setEtiquetaSemDv('SI' . mt_rand(10000000, 99999999) . 'BR');

$encomenda = new \PhpSigep\Model\ObjetoPostal();
$encomenda->setServicosAdicionais($servicosAdicionais);
$encomenda->setDestinatario($destinatario);
$encomenda->setDestino($destino);
$encomenda->setDimensao($dimensao);
$encomenda->setEtiqueta($etiqueta);
$encomenda->setPeso($peso);
$encomenda->setServicoDePostagem($servicoDePostagem);

$remetente = new \PhpSigep\Model\Remetente();
$remetente->setNome($remetenteNome);
$remetente->setNumero($remetenteNumero);
$remetente->setUf($remetenteEstado);
$remetente->setCidade($remetenteCidade);
$remetente->setBairro($remetenteBairro);
$remetente->setCep($remetenteCep);
//$remetente->setCodigoAdministrativo($accessData->getCodAdministrativo());
$remetente->setComplemento($remetenteComplemento);
//$remetente->setDiretoria($accessData->getDiretoria());
$remetente->setLogradouro($remetenteLogradouro);

$params = new \PhpSigep\Model\PreListaDePostagem();
$params->setAccessData($accessData);
$params->setEncomendas(array($encomenda));
$params->setRemetente($remetente);

if ($relatorio == 'etiquetas') {
    $logoFile = __DIR__ . '/../../img/logo-etiqueta.png';
    $servico  = new \PhpSigep\Pdf\CartaoDePostagem($params, time(), $logoFile);
} else {
    $servico  = new \PhpSigep\Pdf\ListaDePostagem($params, time());
}

$servico->render($params);

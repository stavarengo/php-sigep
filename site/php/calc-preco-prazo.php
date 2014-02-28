<?php
require_once __DIR__ . '/php-sigep/src/PhpSigep/Bootstrap.php';

\PhpSigep\Bootstrap::start(new \PhpSigep\Config(array(
    'isDebug' => true,
)));

$peso            = $_POST['peso'];
$tipoTransporte  = $_POST['tipoTransporte'];
$remetenteCep    = $_POST['remetenteCep'];
$destinatarioCep = $_POST['destinatarioCep'];
$servicosAdicionaisSelecionados = $_POST['servicosAdicionais'];

$contratoCodAdministrativo = $_POST['contratoCodAdministrativo'];
$contratoSenha             = $_POST['contratoSenha'];

$servicosPostagem = array();
foreach ($tipoTransporte as $tipo) {
    $servicosPostagem[] = new \PhpSigep\Model\ServicoDePostagem($tipo);
} 

$servicosAdicionais = array();
foreach ($servicosAdicionaisSelecionados as $servicoAdicional) {
    $valorDeclarado = null;
    if ($servicoAdicional == 'mp') {
        $codServicosAdicional = \PhpSigep\Model\ServicoAdicional::SERVICE_MAO_PROPRIA;
    } else if ($servicoAdicional == 'vd') {
        $codServicosAdicional = \PhpSigep\Model\ServicoAdicional::SERVICE_VALOR_DECLARADO;
        $valorDeclarado = (float)$_POST['valorDeclarado'];
    } else if ($servicoAdicional == 'ar') {
        $codServicosAdicional = \PhpSigep\Model\ServicoAdicional::SERVICE_AVISO_DE_RECEBIMENTO;
    } else {
        continue;
    }
    $servicosAdicionais[] = new \PhpSigep\Model\ServicoAdicional(array(
        'codigoServicoAdicional' => $codServicosAdicional,
        'valorDeclarado' => $valorDeclarado,
    ));
} 

$dimensao = new \PhpSigep\Model\Dimensao();
$dimensao->setAltura(20);
$dimensao->setLargura(20);
$dimensao->setComprimento(20);
$dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);

$accessData = new \PhpSigep\Model\AccessData(array(
    'codAdministrativo' => $contratoCodAdministrativo,
    'senha'             => $contratoSenha,
    'usuario'           => '',
    'cartaoPostagem'    => '',
    'cnpjEmpresa'       => '',
    'anoContrato'       => '',
    'numeroContrato'    => '',
    'diretoria'         => '',
));

$params = new \PhpSigep\Model\CalcPrecoPrazo();
$params->setServicosPostagem($servicosPostagem);
$params->setServicosAdicionais($servicosAdicionais);
$params->setPeso($peso);
$params->setDimensao($dimensao);
$params->setCepOrigem($remetenteCep);
$params->setCepDestino($destinatarioCep);
$params->setAccessData($accessData);

$servico = new \PhpSigep\Services\CalcPrecoPrazo();

try {
    $r = $servico->execute($params);
} catch (Exception $e) {
    $message = $e->getMessage();
    if ($message == 'Service Unavailable') {
        if (!self::$Service_Unavailable) {
            //Tenta mais uma vez
            self::$Service_Unavailable = true;
            sleep(1);
            return $this->execute();
        } else {
            $this->getResponse()->ok(200, null);
        }
    }
    $r = array('errorMsg' => $message);
}
$help = file_get_contents(__DIR__ . '/calc-preco-prazo.help.html');

$r = array(
    'resultado' => $r,
    'help' => $help,
);
die(json_encode($r, JSON_PRETTY_PRINT));

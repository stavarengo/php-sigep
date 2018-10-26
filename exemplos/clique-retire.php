<?php
require '../vendor/autoload.php';

$accessData = new \PhpSigep\Model\AccessDataHomologacao();

$config = new \PhpSigep\Config();
$config->setAccessData($accessData);

\PhpSigep\Bootstrap::start($config);

$preListaDePostagem = new \PhpSigep\Model\PreListaDePostagem();
$preListaDePostagem->setAccessData($accessData);

$dimensao_por_cubagem = 1000 ** (1/3);
$dimensao = new \PhpSigep\Model\Dimensao();
$dimensao->setAltura($dimensao_por_cubagem);
$dimensao->setLargura($dimensao_por_cubagem);
$dimensao->setComprimento($dimensao_por_cubagem);
$dimensao->setDiametro(0);
$dimensao->setTipo(\PhpSigep\Model\Dimensao::TIPO_PACOTE_CAIXA);

// *** DADOS DO REMETENTE *** //
$remetente = new \PhpSigep\Model\Remetente();
$remetente->setNome('Google São Paulo');
$remetente->setLogradouro('Av. Brigadeiro Faria Lima');
$remetente->setNumero('3900');
$remetente->setComplemento('5º andar');
$remetente->setBairro('Itaim');
$remetente->setCep('04538-132');
$remetente->setUf('SP');
$remetente->setCidade('São Paulo');
// *** FIM DOS DADOS DO REMETENTE *** //

$destinatario = new \PhpSigep\Model\Destinatario();
$destinatario->setNome('Google Belo Horizonte');
$destinatario->setLogradouro('Av. Bias Fortes');
$destinatario->setNumero('382');
$destinatario->setComplemento('6º andar');
$destinatario->setIsCliqueRetire(true);

$destino = new \PhpSigep\Model\DestinoNacional();
$destino->setAgencia('Agencia BH');
$destino->setBairro('Lourdes');
$destino->setCep('30170-010');
$destino->setCidade('Belo Horizonte');
$destino->setUf('MG');

$servicoAdicional = new \PhpSigep\Model\ServicoAdicional();
$servicoAdicional->setCodigoServicoAdicional(\PhpSigep\Model\ServicoAdicional::SERVICE_REGISTRO);
// Se não tiver valor declarado informar 0 (zero)
$servicoAdicional->setValorDeclarado(250);

$etiqueta = new \PhpSigep\Model\Etiqueta();
$etiqueta->setEtiquetaComDv('EC373812299BR');

$encomenda = new \PhpSigep\Model\ObjetoPostal();
$encomenda->setServicosAdicionais(array($servicoAdicional));
$encomenda->setDestinatario($destinatario);
$encomenda->setDestino($destino);
$encomenda->setDimensao($dimensao);
$encomenda->setEtiqueta($etiqueta);
$encomenda->setPeso(1.2);
$encomenda->setServicoDePostagem(new \PhpSigep\Model\ServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA));

$preListaDePostagem->setEncomendas(array($encomenda));
$preListaDePostagem->setRemetente($remetente);

$phpSigep = new PhpSigep\Services\SoapClient\Real();

$idPlp = 0;
try {
    $result = $phpSigep->fechaPlpVariosServicos($preListaDePostagem);
    if (!$result->hasError()) {
        $idPlp = $result->getResult()->getIdPlp();
    } else {
        var_dump($result->getErrorMsg());
    }
} catch (\Exception $ex) {
    var_dump($ex->getMessage());
}

if ($idPlp) {
    $pdf = new \PhpSigep\Pdf\CartaoDePostagem2016($preListaDePostagem, $idPlp, false);
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="doc.pdf"');
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    echo $pdf->render();
}
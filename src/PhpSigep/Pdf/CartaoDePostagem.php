<?php
namespace PhpSigep\Pdf;

use PhpSigep\Bootstrap;
use PhpSigep\Model\ServicoDePostagem;
use PhpSigep\Model\ServicoAdicional;
use PhpSigep\Pdf\Chancela\Carta;
use PhpSigep\Pdf\Chancela\Pac;
use PhpSigep\Pdf\Chancela\Sedex;
use PhpSigep\Pdf\Chancela\Carta2016;
use PhpSigep\Pdf\Chancela\Pac2016;
use PhpSigep\Pdf\Chancela\Sedex2016;

/**
 * @author: Stavarengo
 * @modify José Domingos Grieco <jdgrieco@gmail.com>
 * @modify Jonathan Célio da Silva <jonathan.clio@hotmail.com>
 */
class CartaoDePostagem
{

    const TYPE_CHANCELA_CARTA = 'carta';
    const TYPE_CHANCELA_SEDEX = 'sedex';
    const TYPE_CHANCELA_PAC   = 'pac';

    const TYPE_CHANCELA_CARTA_2016 = 'carta-2016';
    const TYPE_CHANCELA_SEDEX_2016 = 'sedex-2016';
    const TYPE_CHANCELA_PAC_2016   = 'pac-2016';

    /**
     * @var \PhpSigep\Pdf\ImprovedFPDF
     */
    public $pdf;
    /**
     * @var \PhpSigep\Model\PreListaDePostagem
     */
    private $plp;
    /**
     * @var int
     */
    private $idPlpCorreios;
    /**
     * Uma imagem com tamanho 120 x 140
     * @var string
     */
    private $logoFile;
    /**
     * Layout da chancela do Sedex que deve ser utilizado
     * @var string
     */
    private $layoutSedex = 'sedex';
    /**
     * Layout da chancela do PAC que deve ser utilizado
     * @var string
     */
    private $layoutPac = 'pac';
    /**
     * Layout da chancela da Carta que deve ser utilizado
     * @var string
     */
    private $layoutCarta = 'carta';
    /**
     * Gerar etiquetas para o mesmo destinatário
     * Irá gerar na etiqueta a escrita: Volume 1 de 2, volume 2 de 2.
     * @var boolean $envioMesmoDestinatario
     */
    private $envioMesmoDestinatario = true;
    /**
     * @param \PhpSigep\Model\PreListaDePostagem $plp
     * @param int $idPlpCorreios
     * @param string $logoFile
     * @throws InvalidArgument
     *      Se o arquivo $logoFile não existir.
     */
    public function __construct($plp, $idPlpCorreios, $logoFile, $chancelas = array())
    {
        if ($logoFile && !@getimagesize($logoFile)) {
            throw new InvalidArgument('O arquivo "' . $logoFile . '" não existe.');
        }

        $this->plp = $plp;
        $this->idPlpCorreios = $idPlpCorreios;
        $this->logoFile = $logoFile;

        $rClass = new \ReflectionClass(__CLASS__);
        $tiposChancela = $rClass->getConstants();
        foreach ($chancelas as $chancela) {
            switch ($chancela) {
                case CartaoDePostagem::TYPE_CHANCELA_CARTA:
                case CartaoDePostagem::TYPE_CHANCELA_CARTA_2016:
                    $this->layoutCarta = $chancela;
                    break;
                case CartaoDePostagem::TYPE_CHANCELA_SEDEX:
                case CartaoDePostagem::TYPE_CHANCELA_SEDEX_2016:
                    $this->layoutSedex = $chancela;
                    break;
                case CartaoDePostagem::TYPE_CHANCELA_PAC:
                case CartaoDePostagem::TYPE_CHANCELA_PAC_2016:
                    $this->layoutPac = $chancela;
                    break;
                default:
                    throw new \PhpSigep\Pdf\Exception\InvalidChancelaEntry('O tipo de chancela deve ser uma das constantes da classe');
            }
        }
        $this->init();
    }

    public function render($dest='', $filename = '')
    {
        $cacheKey = md5(serialize($this->plp) . $this->idPlpCorreios . get_class($this));
        if ($pdfContent = Bootstrap::getConfig()->getCacheInstance()->getItem($cacheKey)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="doc.pdf"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            echo $pdfContent;
        } else {
            if($dest == 'S'){
                return $this->_render($dest, $filename);
            }
            else{
                $this->_render($dest, $filename);
                Bootstrap::getConfig()->getCacheInstance()->setItem($cacheKey, $this->pdf->buffer);
            }
        }
    }

    private function _render($dest='', $filename='')
    {
        $un = 72 / 25.4;
        $wFourAreas = $this->pdf->w;
        $hFourAreas = $this->pdf->h; //-Menos 1.5CM porque algumas impressoras não conseguem imprimir nos ultimos 1cm da página
        $tMarginFourAreas = 0;
        $rMarginFourAreas = 0;
        $bMarginFourAreas = 0;
        $lMarginFourAreas = 0;
        $wInnerFourAreas = $wFourAreas - $lMarginFourAreas - $rMarginFourAreas;
        $hInnerFourAreas = 0;

        $margins = array(
            array(
                'l' => $lMarginFourAreas,
                'r' => $wFourAreas - $rMarginFourAreas,
                't' => $tMarginFourAreas,
                'b' => $hFourAreas - $bMarginFourAreas
            ),
            array(
                'l' => $wFourAreas + $lMarginFourAreas,
                'r' => $wFourAreas * 2 - $rMarginFourAreas,
                't' => $tMarginFourAreas,
                'b' => $hFourAreas - $bMarginFourAreas,
            ),
            array(
                'l' => $lMarginFourAreas,
                'r' => $wFourAreas - $rMarginFourAreas,
                't' => $hFourAreas + $tMarginFourAreas,
                'b' => $hFourAreas * 2 - $bMarginFourAreas,
            ),
            array(
                'l' => $wFourAreas + $lMarginFourAreas,
                'r' => $wFourAreas * 2 - $rMarginFourAreas,
                't' => $hFourAreas + $tMarginFourAreas,
                'b' => $hFourAreas * 2 - $bMarginFourAreas,
            ),
        );

        $objetosPostais = $this->plp->getEncomendas();
        $total = count($objetosPostais);
        $index = 1;
        while (count($objetosPostais)) {
            $this->pdf->AddPage();

            if (Bootstrap::getConfig()->getSimular()) {
                $this->pdf->SetFont('Arial', 'B', 50);
                $this->pdf->SetTextColor(240, 240, 240);
                $this->pdf->SetXY($lMarginFourAreas, $hFourAreas - $this->pdf->getLineHeigth());
                $this->pdf->MultiCellXp(
                    $this->pdf->w - $this->pdf->lMargin - $this->pdf->rMargin,
                    "Simulação Documento sem valor",
                    null,
                    0,
                    'C'
                );
                $this->pdf->SetXY(
                    $lMarginFourAreas,
                    $margins[2]['t'] + $hFourAreas - $this->pdf->getLineHeigth()
                );
                $this->pdf->MultiCellXp(
                    $this->pdf->w - $this->pdf->lMargin - $this->pdf->rMargin,
                    "Simulação Documento sem valor",
                    null,
                    0,
                    'C'
                );
                $this->pdf->SetTextColor(0, 0, 0);
            }

//            $crossMargin = 50;
//            $this->pdf->SetDrawColor(200, 200, 200);
//            for ($lineY = $crossMargin; $lineY < $this->pdf->h - $crossMargin; $lineY += 10) {
//                $this->pdf->Line($wFourAreas, $lineY, $wFourAreas, $lineY + 3);
//            }
//            for ($lineX = $crossMargin; $lineX < $this->pdf->w - $crossMargin; $lineX += 10) {
//                $this->pdf->Line($lineX, $hFourAreas, $lineX + 3, $hFourAreas);
//            }

            $this->pdf->SetDrawColor(0, 0, 0);
            for ($area = 0; $area < 1; $area++) {
                if (!count($objetosPostais)) {
                    break;
                }
                /** @var $objetoPostal \PhpSigep\Model\ObjetoPostal */
                $objetoPostal = array_shift($objetosPostais);

                $lPosFourAreas = $margins[$area]['l'];
                $rPosFourAreas = $margins[$area]['r'];
                $tPosFourAreas = $margins[$area]['t'];
                $bPosFourAreas = $margins[$area]['b'];

                // Logo
                $this->pdf->SetXY($lPosFourAreas, $tPosFourAreas);
                $this->setFillColor(222, 222, 222);
                $headerColWidth = $wInnerFourAreas / 3;
                $headerHeigth = 106;
                if ($this->logoFile) {
                    $this->pdf->Image($this->logoFile, 3, 0, 25);
                }

                // Chancela
                $this->pdf->SetXY(66, $this->pdf->GetY() + 3);
                $this->setFillColor(150, 150, 200);
                $wChancela = 101.5;
                $hChancela = 72.5;
                $lPosChancela = 66;
                $tPosChancela = $this->pdf->GetY();

                $servicoDePostagem = $objetoPostal->getServicoDePostagem();
                $nomeRemetente = $this->plp->getRemetente()->getNome();
                $accessData = $this->plp->getAccessData();

                switch ($servicoDePostagem->getCodigo()) {
                    case ServicoDePostagem::SERVICE_PAC_41068:
                    case ServicoDePostagem::SERVICE_PAC_04510:
                    case ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA:
                    case ServicoDePostagem::SERVICE_PAC_GRANDES_FORMATOS:
                    case ServicoDePostagem::SERVICE_PAC_REVERSO_CONTRATO_AGENCIA:
                    case ServicoDePostagem::SERVICE_PAC_PAGAMENTO_NA_ENTREGA:
                    case ServicoDePostagem::SERVICE_PAC_CONTRATO_UO:
                    case ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA_LM:
                    case ServicoDePostagem::SERVICE_PAC_CONTRATO_GRANDES_FORMATOS_LM:
                    case ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA_TA:
                        if ($this->layoutPac === CartaoDePostagem::TYPE_CHANCELA_PAC) {
                            $chancela = new Pac($lPosChancela, $tPosChancela, $nomeRemetente, $accessData);
                        } else {
                            $chancela = new Pac2016($lPosChancela, $tPosChancela, $nomeRemetente, $accessData);
                        }
                        break;
                    case ServicoDePostagem::SERVICE_SEDEX_41556:
                    case ServicoDePostagem::SERVICE_SEDEX_A_VISTA:
                    case ServicoDePostagem::SERVICE_SEDEX_VAREJO_A_COBRAR:
                    case ServicoDePostagem::SERVICE_SEDEX_PAGAMENTO_NA_ENTREGA:
                    case ServicoDePostagem::SERVICE_SEDEX_AGRUPADO:
                    case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA:
                    case ServicoDePostagem::SERVICE_SEDEX_REVERSO_CONTRATO_AGENCIA:
                    case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_UO:
                    case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA_LM:
                    case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_GRANDES_FORMATOS_LM:
                    case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA_TA:
                        if ($this->layoutSedex === CartaoDePostagem::TYPE_CHANCELA_SEDEX) {
                            $chancela = new Sedex($lPosChancela, $tPosChancela, $nomeRemetente, Sedex::SERVICE_SEDEX, $accessData);
                        } else {
                            $chancela = new Sedex2016($lPosChancela, $tPosChancela, $nomeRemetente, Sedex::SERVICE_SEDEX, $accessData);
                        }
                        break;

                    case ServicoDePostagem::SERVICE_SEDEX_12:
                        if ($this->layoutSedex === CartaoDePostagem::TYPE_CHANCELA_SEDEX) {
                            $chancela = new Sedex($lPosChancela, $tPosChancela, $nomeRemetente, Sedex::SERVICE_SEDEX_12, $accessData);
                        } else {
                            $chancela = new Sedex2016($lPosChancela, $tPosChancela, $nomeRemetente, Sedex::SERVICE_SEDEX_12, $accessData);
                        }
                        break;

                    case ServicoDePostagem::SERVICE_SEDEX_10:
                    case ServicoDePostagem::SERVICE_SEDEX_10_PACOTE:
                        if ($this->layoutSedex === CartaoDePostagem::TYPE_CHANCELA_SEDEX) {
                            $chancela = new Sedex($lPosChancela, $tPosChancela, $nomeRemetente, Sedex::SERVICE_SEDEX_10, $accessData);
                        } else {
                            $chancela = new Sedex2016($lPosChancela, $tPosChancela, $nomeRemetente, Sedex::SERVICE_SEDEX_10, $accessData);
                        }
                        break;

                    case ServicoDePostagem::SERVICE_SEDEX_HOJE_40290:
                    case ServicoDePostagem::SERVICE_SEDEX_HOJE_40878:
                        if ($this->layoutSedex === CartaoDePostagem::TYPE_CHANCELA_SEDEX) {
                            $chancela = new Sedex($lPosChancela, $tPosChancela, $nomeRemetente, Sedex::SERVICE_SEDEX_HOJE, $accessData);
                        } else {
                            $chancela = new Sedex2016($lPosChancela, $tPosChancela, $nomeRemetente, Sedex::SERVICE_SEDEX_HOJE, $accessData);
                        }
                        break;

                    case ServicoDePostagem::SERVICE_CARTA_COMERCIAL_A_FATURAR:
                    case ServicoDePostagem::SERVICE_CARTA_REGISTRADA:
                    case ServicoDePostagem::SERVICE_CARTA_COMERCIAL_REGISTRADA_CTR_EP_MAQ_FRAN:
                    case ServicoDePostagem::SERVICE_CARTA_COM_A_FATURAR_SELO_E_SE:
                        if ($this->layoutCarta === CartaoDePostagem::TYPE_CHANCELA_CARTA) {
                            $chancela = new Carta($lPosChancela, $tPosChancela, $nomeRemetente, $accessData);
                        } else {
                            $chancela = new Carta2016($lPosChancela, $tPosChancela, $nomeRemetente, $accessData);
                        }
                        break;
                    case ServicoDePostagem::SERVICE_SEDEX_REVERSO:
                    default:
                        $chancela = null;
                        break;
                }

                if ($chancela) {
                    $chancela->draw($this->pdf);
                }

                // Título da etiqueta
                if($this->idPlpCorreios > 0) {
                    $lPosHeaderCol2 = $headerColWidth + $lPosFourAreas;
                    // $this->pdf->SetXY($lPosHeaderCol2, $tPosFourAreas);
                    // $this->setFillColor(200, 200, 200);
                    // $this->pdf->SetFontSize(12);
                    // $this->pdf->SetFont('', 'B');
                    // $this->t($headerColWidth, 'Cartão de Postagem', 2, 'C');

                    // Número da plp
                    $this->pdf->SetXY($lPosHeaderCol2, $tPosFourAreas + 32);
                    $this->setFillColor(150, 200, 200);
                    $this->pdf->SetFont('', '');
                    $this->pdf->SetFontSize(9);
                    $this->t($headerColWidth * 3, 'PLP: ' . $this->idPlpCorreios, 0, 'C');
                }

                // Volume
                $this->setFillColor(100, 150, 200);
                $this->pdf->SetXY(0, 30);

                $nf = (float)$objetoPostal->getDestino()->getNumeroNotaFiscal();
                if($nf > 0) {
                    $nf = '    NF: '. $nf;
                } else {
                    $nf = '';
                }

                $numeroPedido = trim($objetoPostal->getDestino()->getNumeroPedido());
                if(!empty($numeroPedido)) {
                    $numeroPedido = '    Pedido: ' . $numeroPedido;
                }

                $this->pdf->SetFontSize(7);
                if ($this->getEnvioMesmoDestinatario()){
                	$this->t($this->pdf->w, "Volume: $index/$total    ".'Peso(kg): ' . ((float)$objetoPostal->getPeso()) . $nf . $numeroPedido, 1, 'C',  null);
                }else{
                    $this->t($this->pdf->w, 'Peso(kg): ' . ((float)$objetoPostal->getPeso()) . $nf . $numeroPedido, 1, 'C',  null);
                }

                // Número da etiqueta
                $this->setFillColor(100, 100, 200);
                $this->pdf->SetXY(0, $this->pdf->GetY() + 1);
                $this->pdf->SetFontSize(9);
                $this->pdf->SetFont('', 'B');
                $etiquetaComDv = $objetoPostal->getEtiqueta()->getEtiquetaComDv();
                $this->t($wInnerFourAreas, $etiquetaComDv, 1, 'C');

                // Código de barras da etiqueta
                $this->setFillColor(0, 0, 0);
                $tPosEtiquetaBarCode = $this->pdf->GetY();

                $hEtiquetaBarCode = 22;
                $wEtiquetaBarCode = 78;

                $code128 = new \PhpSigep\Pdf\Script\BarCode128();
                $code128->draw(
                    $this->pdf,
                    ($this->pdf->w - $wEtiquetaBarCode) / 2,
                    $tPosEtiquetaBarCode,
                    $etiquetaComDv,
                    $wEtiquetaBarCode,
                    $hEtiquetaBarCode
                );

                // Nome legivel, doc e rubrica
                //
                $this->pdf->SetFontSize(7);
                $this->pdf->SetXY(3, $this->pdf->GetY() + 23);
                $this->t(0, 'Nome Legível:___________________________________________',1, 'L',  null);
                $this->pdf->SetXY(3, $this->pdf->GetY() + 1);
                $this->t(0, 'Documento:_______________________Rubrica:_____________________',1, 'L',  null);

                // Destinatário
                $wAddressLeftCol = $this->pdf->w - 5;

                $tPosAfterNameBlock = 71 ;

                $t = $this->writeDestinatario(
                    $lPosFourAreas,
                    $tPosAfterNameBlock,
                    $wAddressLeftCol,
                    $objetoPostal
                );

                $destino = $objetoPostal->getDestino();

                if ($destino instanceof \PhpSigep\Model\DestinoNacional) {

                    // Número do CEP
                    $cep = $destino->getCep();
                    $cep = preg_replace('/[^\d]/', '', $cep);

                    $tPosCepBarCode = $t + 1;

                    // Etiqueta do CEP
                    $hCepBarCode = 22;
                    $wCepBarCode = 47;
                    $this->setFillColor(0, 0, 0);
                    $code128->draw(
                        $this->pdf,
                        6,
                        $tPosCepBarCode,
                        $cep,
                        $wCepBarCode,
                        $hCepBarCode
                    );

                    $valorDeclarado = null;
                    $sSer = "";

                    foreach ($objetoPostal->getServicosAdicionais() as $servicoAdicional) {
                        if ($servicoAdicional->is(ServicoAdicional::SERVICE_AVISO_DE_RECEBIMENTO)) {
                            $temAr = true;
                            $sSer = $sSer . "01";
                        } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_MAO_PROPRIA)) {
                            $temMp = true;
                            $sSer = $sSer . "02";
                        } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_VALOR_DECLARADO_SEDEX)) {
                            $temVd = true;
                            $sSer = $sSer . "19";
                            $valorDeclarado = $servicoAdicional->getValorDeclarado();
                        } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_VALOR_DECLARADO_PAC)) {
                            $temVd = true;
                            $sSer = $sSer . "64";
                            $valorDeclarado = $servicoAdicional->getValorDeclarado();
                        } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_REGISTRO)) {
                            $temRe = true;
                            $sSer = $sSer . "25";
                        }
                    }
                    while (strlen($sSer) < 12) {
                        $sSer = $sSer . "00";
                    }

                    $sM2Dtext = $this->getM2Dstr(
                        $cep,
                        $objetoPostal->getDestinatario()->getNumero(),
                        $this->plp->getRemetente()->getCep(),
                        $this->plp->getRemetente()->getNumero(),
                        $etiquetaComDv,
                        $sSer,
                        $this->plp->getAccessData()->getCartaoPostagem(),
                        $objetoPostal->getServicoDePostagem()->getCodigo(),
                        $valorDeclarado,
                        $objetoPostal->getDestinatario()->getTelefone()
                    );

                    require_once  'Semacode.php';
                    $semacode = new \Semacode();

                    $semaCodeGD = $semacode->asGDImage($sM2Dtext);

                    $this->setFillColor(222, 222, 222);
                    $this->pdf->gdImage($semaCodeGD, 35, 3, 25);
                    imagedestroy($semaCodeGD);

                }

                $this->writeRemetente(0,  $this->pdf->GetY() + $hCepBarCode + 5, $wAddressLeftCol, $this->plp->getRemetente());

            }

            $index++;
        }

        if($dest == 'S'){
            return $this->pdf->Output($filename,$dest);
        }
        else{
            $this->pdf->Output($filename,$dest);
        }
    }

    private function _($str)
    {
        $replaces = array(
            'ā' => 'a',
        );
        $str = str_replace(array_keys($replaces), array_values($replaces), $str);
        if (extension_loaded('iconv')) {
            return iconv('UTF-8', 'ISO-8859-1', $str);
        } else {
            return utf8_decode($str);
        }
    }

    private function init()
    {
        $this->pdf = new \PhpSigep\Pdf\ImprovedFPDF('P', 'mm', array(100, 140));
        $this->pdf->SetFont('Arial', '', 10);
    }

    /**
     * @param $l
     * @param $tPosEtiquetaBarCode
     * @param $hEtiquetaBarCode
     * @param $w
     * @param $lineHeigth
     * @param $objetoPostal
     */
    private function writeDestinatario($l, $t, $w, $objetoPostal)
    {
        $titulo = 'Destinatário';
        $nomeDestinatario = $objetoPostal->getDestinatario()->getNome();
        $logradouro = $objetoPostal->getDestinatario()->getLogradouro();
        $numero = $objetoPostal->getDestinatario()->getNumero();
        $complemento = $objetoPostal->getDestinatario()->getComplemento();
        $bairro = '';
        $cidade = '';
        $uf = '';
        $cep = '';
        $destino = $objetoPostal->getDestino();

        if ($destino instanceof \PhpSigep\Model\DestinoNacional) {
            $bairro = $destino->getBairro();
            $cidade = $destino->getCidade();
            $uf = $destino->getUf();
            $cep = $destino->getCep();
        }

        $cep = preg_replace('/(\d{5})-{0,1}(\d{3})/', '$1-$2', $cep);

        return $this->writeEndereco(
            $t,
            $l,
            $w,
            $titulo,
            $nomeDestinatario,
            $logradouro,
            $numero,
            $complemento,
            $bairro,
            $cidade,
            $uf,
            $cep
        );
    }

    private function writeRemetente($l, $t, $w, \PhpSigep\Model\Remetente $remetente)
    {
        $titulo = 'Remetente';
        $nomeDestinatario = $remetente->getNome();
        $logradouro = $remetente->getLogradouro();
        $numero = $remetente->getNumero();
        $complemento = $remetente->getComplemento();
        $bairro = $remetente->getBairro();
        $cidade = $remetente->getCidade();
        $uf = $remetente->getUf();
        $cep = $remetente->getCep();

        $cep = preg_replace('/(\d{5})-{0,1}(\d{3})/', '$1-$2', $cep);

        return $this->writeEndereco(
            $t,
            $l,
            $w,
            $titulo,
            $nomeDestinatario,
            $logradouro,
            $numero,
            $complemento,
            $bairro,
            $cidade,
            $uf,
            $cep
        );
    }

    /**
     * @param $t
     * @param $l
     * @param $w
     * @param $titulo
     * @param $nomeDestinatario
     * @param $logradouro
     * @param $numero1
     * @param $complemento
     * @param $bairro
     * @param $cidade
     * @param $uf
     * @param $cep
     *
     * @internal param $lineHeigth
     * @internal param $objetoPostal
     */
    private function writeEndereco(
        $t, $l, $w, $titulo, $nomeDestinatario, $logradouro, $numero1, $complemento, $bairro,
        $cidade, $uf, $cep = null
    )
    {
        // Titulo do bloco: destinatario ou remetente
        $this->pdf->SetFont('', 'B');
        $this->setFillColor(60, 60, 60);
        $this->pdf->SetFontSize(7);
        $this->pdf->SetXY($l + 3, $t);
        $this->t($w, $titulo, 2, '');

        $addressPadding = 5;
        $w = $w - $addressPadding;
        $l = $l + $addressPadding;

        // Nome da pessoa
        $this->pdf->SetFont('', '');
        $this->setFillColor(190, 190, 190);
        $this->pdf->SetX($l);
        $this->multiLines($w, $nomeDestinatario, 'L');

        //Primeria parte do endereco
        $address1 = $logradouro;
        $numero = $numero1;
        if (!$numero || strtolower($numero) == 'sn') {
            $address1 .= ', s/ nº';
        } else {
            $address1 .= ', ' . $numero;
        }
        if ($complemento) {
            $address1 .= ' - ' . $complemento;
        }
        $this->setFillColor(100, 190, 190);
        $this->pdf->SetX($l);
        $this->multiLines($w, $address1, 'L');

        //Segunda parte do endereco
        $this->pdf->SetX($l);
        $this->setFillColor(100, 130, 190);
        $this->multiLines($w, '' . $bairro, 'L');
        $this->setFillColor(100, 30, 210);
        $this->pdf->SetX($l);
        $this->multiLines($w, ($cep ? $cep . ' - ' : '') . $cidade . ' - ' . $uf, 'L');

        return $this->pdf->GetY();
    }

    private function setFillColor($r, $g, $b)
    {
        $this->pdf->SetFillColor($r, $g, $b);
    }

    private function t($w, $txt, $ln, $align, $h = null, $multiLines = false, $utf8 = true)
    {
        if ($utf8) {
            $txt = $this->_($txt);
        }
//		$border = 1;
//		$fill   = true;
        $border = 0;
        $fill = false;

        if ($h === null) {
            $h = $this->pdf->getLineHeigth();
        }

        if ($multiLines) {
            $this->pdf->MultiCell($w, $h, $txt, $border, $align, $fill);
        } else {
            $this->pdf->Cell($w, $h, $txt, $border, $ln, $align, $fill);
        }
    }

    private function multiLines($w, $txt, $align, $h = null, $utf8 = true)
    {
        $this->t($w, $txt, null, $align, $h, true, $utf8);
    }

    private function CalcDigCep($cep)
    {
        $str = str_split($cep);
        $sum = 0;
        for ($i = 0; $i <= 7; $i++) {
            $sum = $sum + intval($str[$i]);
        }
        $mul = $sum - $sum % 10 + 10;
        return $mul - $sum;
    }

    private function getM2Dstr($cepD, $numD, $cepO, $numO, $etq, $srvA, $carP, $codS, $valD, $telD, $msg='')
    {
        $str = '';
        $str .= str_replace('-', '', $cepD);
        $str .= sprintf('%05d', $numD);
        $str .= str_replace('-', '', $cepO);
        $str .= sprintf('%05d', $numO);
        $str .= intval($this->CalcDigCep(str_replace('-', '', $cepD)));
        $str .= '51';
        $str .= $etq;
        $str .= $srvA;
        $str .= $carP;
        $str .= sprintf('%05d', $codS);
        $str .= '01';
        $str .= sprintf('%05d', $numD);
        $str .= sprintf('%05d', (int)$valD);
        $str .= $telD;
        $str .= '-00.000000';
        $str .= '-00.000000';
        $str .= '|';
        $str .= $msg;
        return $str;
    }
    
    public function getEnvioMesmoDestinatario() {
        return $this->envioMesmoDestinatario;
    }
    public function setEnvioMesmoDestinatario($envioMesmoDestinatario) {
        $this->envioMesmoDestinatario = $envioMesmoDestinatario;
        return $this;
    }
    
}

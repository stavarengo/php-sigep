<?php

namespace PhpSigep\Pdf;

use PhpSigep\Bootstrap;
use PhpSigep\Model\ObjetoPostal;
use PhpSigep\Model\ServicoDePostagem;
use PhpSigep\Model\ServicoAdicional;
use PhpSigep\Pdf\Chancela\Pac2018;

class CartaoDePostagem2018
{

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
     * Volume do pacote
     * @var string
     */
    public $_volume;

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

    /**
     * @param string $dest
     * @param string $fileName
     * @return mixed
     */
    private function _render ($dest='', $fileName= '')
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

            $this->pdf->SetDrawColor(0, 0, 0);
            /** @var $objetoPostal ObjetoPostal */
            $objetoPostal = array_shift($objetosPostais);

            $lPosFourAreas = $margins[0]['l'];
            $rPosFourAreas = $margins[0]['r'];
            $tPosFourAreas = $margins[0]['t'];
            $bPosFourAreas = $margins[0]['b'];

            // Logo
            $this->pdf->SetXY($lPosFourAreas, $tPosFourAreas);
            $this->setFillColor(222, 222, 222);
            if ($this->logoFile) {
                $this->pdf->Image($this->logoFile, 5, ($this->pdf->GetY() + 2), 25, 25);
            }

            $nomeRemetente = $this->plp->getRemetente()->getNome();
            $accessData = $this->plp->getAccessData();

            $this->setFillColor(150, 150, 200);

            $simbolo_de_encaminhamento = null;
            $chancela = null;
            $servicoDePostagem = $objetoPostal->getServicoDePostagem();

            switch ($servicoDePostagem->getCodigo()) {
                case ServicoDePostagem::SERVICE_PAC_41068:
                case ServicoDePostagem::SERVICE_PAC_ADMINISTRATIVO:
                case ServicoDePostagem::SERVICE_PAC_04510:
                case ServicoDePostagem::SERVICE_PAC_GRANDES_FORMATOS:
                case ServicoDePostagem::SERVICE_PAC_CONTRATO_GRANDES_FORMATOS;
                case ServicoDePostagem::SERVICE_PAC_CONTRATO_UO:
                case ServicoDePostagem::SERVICE_PAC_PAGAMENTO_NA_ENTREGA:
                case ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA:
                case ServicoDePostagem::SERVICE_PAC_REVERSO_CONTRATO_AGENCIA:
                case ServicoDePostagem::SERVICE_PAC_CONTRATO_GRANDES_FORMATOS_LM:
                case ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA_LM:
                case ServicoDePostagem::SERVICE_PAC_REVERSO_LM:
                case ServicoDePostagem::SERVICE_PAC_CONTRATO_UO_LM:
                case ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA_PAGAMENTO_NA_ENTREGA_LM:
                case ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA_TA:
                case ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA_03298:
                case ServicoDePostagem::SERVICE_PAC_CONTRATO_AGENCIA_03085:
                    $chancela = new Pac2018(86, $this->pdf->GetY() + 13, $nomeRemetente, $accessData);
                    $_texto = 'PAC';
                    break;
                case ServicoDePostagem::SERVICE_SEDEX_41556:
                case ServicoDePostagem::SERVICE_SEDEX_A_VISTA:
                case ServicoDePostagem::SERVICE_SEDEX_ADMINISTRATIVO:
                case ServicoDePostagem::SERVICE_SEDEX_VAREJO_A_COBRAR:
                case ServicoDePostagem::SERVICE_SEDEX_PAGAMENTO_NA_ENTREGA:
                case ServicoDePostagem::SERVICE_SEDEX_AGRUPADO:
                case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA:
                case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_UO:
                case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_GRANDES_FORMATOS_LM:
                case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA_LM:
                case ServicoDePostagem::SERVICE_SEDEX_REVERSO_LM:
                case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_UO_LM:
                case ServicoDePostagem::SERVICE_SEDEX_REVERSO_CONTRATO_AGENCIA:
                case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA_PAGAMENTO_NA_ENTREGA_LM:
                case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA_TA:
                case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA_03220:
                case ServicoDePostagem::SERVICE_SEDEX_CONTRATO_AGENCIA_03050:
                    $simbolo_de_encaminhamento = realpath(dirname(__FILE__)) . '/simbolo-sedex-standard.png';
                    $_texto = 'SEDEX';
                    break;
                case ServicoDePostagem::SERVICE_SEDEX_12:
                case ServicoDePostagem::SERVICE_SEDEX_12_CONTRATO_AGENCIA_03140:
                    $simbolo_de_encaminhamento = realpath(dirname(__FILE__)) . '/simbolo-sedex-expresso.png';
                    $_texto = 'SEDEX 12';
                    break;
                case ServicoDePostagem::SERVICE_SEDEX_10:
                case ServicoDePostagem::SERVICE_SEDEX_10_PACOTE:
                case ServicoDePostagem::SERVICE_SEDEX_10_CONTRATO_AGENCIA_03158:
                    $simbolo_de_encaminhamento = realpath(dirname(__FILE__)) . '/simbolo-sedex-expresso.png';
                    $_texto = 'SEDEX 10';
                    break;
                case ServicoDePostagem::SERVICE_SEDEX_HOJE_40290:
                case ServicoDePostagem::SERVICE_SEDEX_HOJE_40878:
                    $simbolo_de_encaminhamento = realpath(dirname(__FILE__)) . '/simbolo-sedex-expresso.png';
                    $_texto = 'SEDEX Hoje';
                    break;
                case ServicoDePostagem::SERVICE_CARTA_COMERCIAL_A_FATURAR:
                case ServicoDePostagem::SERVICE_CARTA_REGISTRADA:
                case ServicoDePostagem::SERVICE_CARTA_REGISTRADA_AGENCIA_80250:
                case ServicoDePostagem::SERVICE_CARTA_REGISTRADA_80659:
                case ServicoDePostagem::SERVICE_CARTA_COMERCIAL_REGISTRADA_CTR_EP_MAQ_FRAN:
                case ServicoDePostagem::SERVICE_CARTA_COM_A_FATURAR_SELO_E_SE:
                    $simbolo_de_encaminhamento = realpath(dirname(__FILE__)) . '/simbolo-sem-especificacao.png';
                    $_texto = 'Carta';
                    break;
                case ServicoDePostagem::SERVICE_SEDEX_REVERSO:
                    $simbolo_de_encaminhamento = realpath(dirname(__FILE__)) . '/simbolo-sedex-standard.png';
                    $_texto = 'SEDEX';
                    break;
                case ServicoDePostagem::SERVICE_MINI_ENVIOS_04227:
                case ServicoDePostagem::SERVICE_MINI_ENVIOS_04235:
                case ServicoDePostagem::SERVICE_MINI_ENVIOS_04391:
                    $simbolo_de_encaminhamento = realpath(dirname(__FILE__)) . '/simbolo-sem-especificacao.png';
                    $_texto = 'Mini Envios';
                    break;
                default:
                    $simbolo_de_encaminhamento = null;
                    break;
            }

            if ($simbolo_de_encaminhamento) {
                $this->pdf->Image($simbolo_de_encaminhamento, 81, $this->pdf->GetY() + 2, 20, 20);
            } else if ($chancela) {
                $chancela->draw($this->pdf);
            }

            $this->setFillColor(100, 150, 200);
            // nota fiscal
            $this->pdf->SetXY(5, 27);
            $this->pdf->SetFontSize(9);
            //$this->pdf->SetTextColor(51,51,51);
            $nf = $objetoPostal->getDestino()->getNumeroNotaFiscal();
            $str = $nf > 0 ?  'NF: '. substr($nf,5) : ' ';
            $this->t(15, $str, 1, 'L',  null);

            // Contrato
            $AccessData = $this->plp->getAccessData();
            $ncontrato = (int) $AccessData->getNumeroContrato() > 0 ? $AccessData->getNumeroContrato() : '';

            $this->pdf->SetXY(35, 27);
            $this->t(15, 'Contrato:', 1, 'L', null);

            $this->pdf->SetFont('', 'B');
            $this->pdf->SetXY(50, 27);
            $this->t(15, $ncontrato, 1, 'L', null);
            $this->pdf->SetFont('');

            // Volume
            $this->pdf->SetXY(81, 27);
            $str = $this->_volume != "" ?  'Volume: '. $this->_volume : ' ';
            $this->t(15, $str, 1, 'L', null);

            // Pedido
            $this->pdf->SetXY(5, 31);
            //$this->pdf->SetTextColor(51,51,51);
            $pedido = $objetoPostal->getDestino()->getNumeroPedido();
            $str = $pedido != "" ?  'Pedido: '. $pedido : ' ';
            $this->pdf->SetFontSize(9);
            $this->t(15, $str, 1, 'L', null);

            $this->pdf->SetFont('', 'B');
            $this->pdf->SetXY(35, 31);
            $this->t(40, $_texto, 1, 'C', null);
            $this->pdf->SetFont('');

            // Peso
            if(!empty($objetoPostal->getPeso()*1000))) {
                $this->pdf->SetXY(81, 31);
                $this->t(15, 'Peso (g):', 1, 'L', null);
                $this->pdf->SetFont('', 'B');
                $this->pdf->SetXY(95, 31);
                $this->t(15, round($objetoPostal->getPeso()*1000), 1, 'L', null);
                $this->pdf->SetFont('');
            }

            // Número da etiqueta
            $Yetiqueta = $this->pdf->GetY() + 1;
            $this->setFillColor(100, 100, 200);
            $this->pdf->SetXY(0, $Yetiqueta);
            $this->pdf->SetFontSize(11);
            $this->pdf->SetFont('', 'B');
            $etiquetaComDv = $objetoPostal->getEtiqueta()->getEtiquetaComDv();
            $etiquetaFormatada = substr($etiquetaComDv, 0, 2) . ' '
                . substr($etiquetaComDv, 2, 3) . ' '
                . substr($etiquetaComDv, 5, 3) . ' '
                . substr($etiquetaComDv, 8, 3) . ' '
                . substr($etiquetaComDv, 11, 2);

            $this->t(85, $etiquetaFormatada, 2, 'C');

            // Código de barras da etiqueta
            $this->setFillColor(0, 0, 0);
            $tPosEtiquetaBarCode = $this->pdf->GetY();

            $hEtiquetaBarCode = 16;
            $wEtiquetaBarCode = 80;

            $code128 = new \PhpSigep\Pdf\Script\BarCode128();
            $code128->draw(
                $this->pdf,
                5,
                $tPosEtiquetaBarCode,
                $etiquetaComDv,
                $wEtiquetaBarCode,
                $hEtiquetaBarCode
            );

            $valorDeclarado = null;
            $_siglaAdicinal = array();
            $sSer = "";
            foreach ($objetoPostal->getServicosAdicionais() as $servicoAdicional) {
                if ($servicoAdicional->is(ServicoAdicional::SERVICE_AVISO_DE_RECEBIMENTO)) {
                    $sSer = $sSer . "01";
                    $_siglaAdicinal[] = "AR";
                } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_MAO_PROPRIA)) {
                    $sSer = $sSer . "02";
                    $_siglaAdicinal[] = "MP";
                } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_VALOR_DECLARADO_SEDEX)) {
                    $sSer = $sSer . "19";
                    $_siglaAdicinal[] = "VD";
                    $valorDeclarado = $servicoAdicional->getValorDeclarado();
                } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_VALOR_DECLARADO_PAC)) {
                    $sSer = $sSer . "64";
                    $_siglaAdicinal[] = "VD";
                    $valorDeclarado = $servicoAdicional->getValorDeclarado();
                } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_VALOR_DECLARADO_MINI_ENVIOS)) {
                    $sSer = $sSer . "65";
                    $_siglaAdicinal[] = "VD";
                    $valorDeclarado = $servicoAdicional->getValorDeclarado();
                } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_REGISTRO)) {
                    $sSer = $sSer . "25";
                }
            }

            $_ctadc = 1;
            $_winit = 90;
            $_hinit = $this->pdf->GetY() - 1;
            $_hupdate = $_hinit;

            foreach ($_siglaAdicinal as $_key => $_sigla) {
                if ($_ctadc > 1 && $_ctadc <= 4) {
                    $_hupdate += 5;
                } else if ($_ctadc == 5) {
                    $_hupdate = $_hinit;
                    $_winit = 98;
                } else if ($_ctadc >= 6) {
                    $_hupdate += 5;
                }

                // Siglas Serviços Adicionais
                $this->pdf->SetXY($_winit, $_hupdate);
                $this->pdf->SetFont('Arial', 'B', 11);
                $this->t(10, $_sigla, 0, 'L', null);

                $_ctadc++;
            }

            $this->pdf->SetFont('');
            // Nome legivel, doc e rubrica
            $this->pdf->SetFontSize(9);
            $this->pdf->SetXY(5, $_hinit + 20);
            $this->t(0, 'Recebedor: _____________________________________________', 1, 'L', null);
            $this->pdf->SetXY(5, $this->pdf->GetY() + 2);
            $this->t(0, 'Assinatura: ______________________ Documento: ____________', 1, 'L', null);
            $this->t(0, '', 1, 'L', null);

            // Destinatário
            $wAddressLeftCol = $this->pdf->w - 5;

            $tPosAfterNameBlock = 71;

            $t = $this->writeDestinatario(
                $lPosFourAreas,
                $tPosAfterNameBlock,
                $wAddressLeftCol,
                $objetoPostal
            );

            $currentY = $this->pdf->GetY();
            // Observações
            $observacoes = $objetoPostal->getObservacao();
            if (!empty($observacoes)) {
                $this->pdf->SetFontSize(9);
                $this->pdf->SetXY(55, $currentY + 1);
                $this->multiLines(50, 'Obs: ' . $observacoes, 'L', null);
            }

            $destino = $objetoPostal->getDestino();

            // Número do CEP
            $cep = $destino->getCep();
            $cep = preg_replace('/[^\d]/', '', $cep);

            $tPosCepBarCode = $t + 1;

            // Etiqueta do CEP
            $hCepBarCode = 16;
            $wCepBarCode = 40;
            $this->setFillColor(0, 0, 0);
            $code128 = new \PhpSigep\Pdf\Script\BarCode128();
            $code128->draw(
                $this->pdf,
                6,
                $tPosCepBarCode,
                $cep,
                $wCepBarCode,
                $hCepBarCode
            );

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
            // $objetoPostal->getDestinatario()->getComplemento()
            );

            require_once  'Semacode.php';
            $semacode = new \Semacode();

            $semaCodeGD = $semacode->asGDImage($sM2Dtext);

            $this->setFillColor(222, 222, 222);
            $this->pdf->gdImage($semaCodeGD, 40, 2, 25, 25);
            imagedestroy($semaCodeGD);

            $this->writeRemetente(0, $currentY + $hCepBarCode + 4, $wAddressLeftCol, $this->plp->getRemetente());

            $this->pdf->SetXY(0, 0);
            $this->pdf->SetDrawColor(0,0,0);
            $this->pdf->Rect(0, 0, 106.36, 140);
        }

        return $this->pdf->Output($fileName, $dest);
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
        $this->pdf = new \PhpSigep\Pdf\ImprovedFPDF('P', 'mm', array(106.36, 140));
        $this->pdf->SetFont('Arial', '', 10);
    }

    /**
     * @param $l
     * @param $t
     * @param $w
     * @param $objetoPostal
     * @return
     * @internal param $tPosEtiquetaBarCode
     * @internal param $hEtiquetaBarCode
     * @internal param $lineHeigth
     * @internal param \Sigep\Cliente $destinatario
     */
    private function writeDestinatario ($l, $t, $w, $objetoPostal)
    {
        $l = $this->pdf->GetX();
        $t1 = $this->pdf->GetY();
        $l = 0;

        $titulo = 'DESTINATÁRIO';
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

        $t = $this->writeEndereco(
            $t1,
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
            $cep,
            true
        );


        //$this->pdf->SetDrawColor(0,0,0);
        //$this->pdf->Rect(0, $t1, 106.36, $t - $t1 + 25);

        return $t;
    }

    private function writeRemetente ($l, $t, $w, \PhpSigep\Model\Remetente $remetente)
    {
        $titulo = 'Remetente:';
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
    private function writeEndereco (
        $t, $l, $w, $titulo, $nomeDestinatario, $logradouro, $numero1, $complemento, $bairro,
        $cidade, $uf, $cep = null, $destinatario = false
    ) {
        //$this->pdf->SetTextColor(51,51,51);
        if ($destinatario === true) {
            $addressPadding = 5;

            $t = $t-2;
            $this->pdf->SetDrawColor(0,0,0);
            $this->pdf->Line(0, $t, 106.36, $t);

            // Titulo do bloco: destinatario
            $this->pdf->setFillColor(0,0,0);
            $this->pdf->SetDrawColor(0,0,0);
            $this->pdf->Rect(0, $t, 36, 5, 'F');

            $this->pdf->SetFont('', 'B');
            $this->pdf->SetFontSize(11);
            $this->pdf->SetTextColor(255,255,255);
            $this->pdf->SetXY($l + 3, $t);
            $this->t($w, $titulo, 2, '');

            $this->pdf->SetTextColor(0,0,0);

            $this->pdf->Image(realpath(dirname(__FILE__)) . '/logo-correios.png', 84, $t+1, 20, 4);

            // Nome da pessoa
            $this->pdf->SetFont('', '', 11);
            $this->setFillColor(190, 190, 190);
            $this->pdf->SetX($l + $addressPadding);
            $this->multiLines($w, $nomeDestinatario, 'L');

        } else {
            $addressPadding = 2;
            $t = $t -1;
            $this->pdf->SetDrawColor(0,0,0);
            $this->pdf->Line(0, $t, 106.36, $t);

            $t++;

            // Titulo do bloco: destinatario ou remetente
            $this->pdf->SetFont('', 'B');
            $this->setFillColor(60, 60, 60);
            $this->pdf->SetFontSize(9);
            $this->pdf->SetXY(2, $t);
            $this->t($w, $titulo, 2, '');

            // Nome da pessoa
            $this->pdf->SetFont('', '', 9);
            $this->setFillColor(190, 190, 190);
            $this->pdf->SetXY(22, $t);
            $this->multiLines($w, trim($nomeDestinatario), 'L');
        }

        $w = $w - $addressPadding;
        $l = $l + $addressPadding;

        //Primeria parte do endereco
        $address1 = $logradouro;
        $numero = $numero1;
        if (!$numero || strtolower($numero) == 'sn') {
            $address1 .= ', s/ nº';
        } else {
            $address1 .= ', ' . $numero;
        }
        if ($complemento) {
            $complemento = $complemento . ' ';
        }
        $this->setFillColor(100, 190, 190);
        $this->pdf->SetX($l);
        $this->multiLines($w, $address1, 'L');

        //Segunda parte do endereco
        $this->pdf->SetX($l);

        $this->setFillColor(100, 130, 190);
        $this->multiLines($w, $complemento . $bairro, 'L');

        $this->setFillColor(100, 30, 210);
        $this->pdf->SetX($l);
        $this->pdf->SetFont('', 'B');
        $this->t($l, ($cep ? $cep . '  ' : ''), 0, 'L');

        $this->pdf->SetFont('');
        $this->pdf->SetX($l + 20);
        $this->t(15, ucfirst(trim($cidade)) . '/' . strtoupper(trim($uf)), 2, 'L');

        return $this->pdf->GetY();
    }

    private function setFillColor ($r, $g, $b)
    {
        $this->pdf->SetFillColor ($r, $g, $b);
    }

    private function t ($w, $txt, $ln, $align, $h = null, $multiLines = false, $utf8 = true)
    {
        if ($utf8) {
            $txt = $this->_($txt);
        }

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

    private function multiLines ($w, $txt, $align, $h = null, $utf8 = true)
    {
        $this->t($w, $txt, null, $align, $h, true, $utf8);
    }

    private function CalcDigCep ($cep)
    {
        $str = str_split($cep);
        $sum = 0;
        for ($i = 0; $i <= 7; $i++) {
            $sum = $sum + intval($str[$i]);
        }
        $mul = $sum - $sum % 10 + 10;
        $digCep = ($mul - $sum)%10 == 0 ? 0 : $mul - $sum;
        return $digCep;
    }

    private function getM2Dstr ($cepD, $numD, $cepO, $numO, $etq, $srvA, $carP, $codS, $valD, $telD, $msg='')
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
        // $str .= str_pad($cplD, 20, ' ');
        $str .= sprintf('%05d', (int)$valD);
        $str .= $telD;
        $str .= '-00.000000';
        $str .= '-00.000000';
        $str .= '|';
        $str .= str_pad($msg, 30, ' ');
        return $str;
    }
}

<?php
namespace PhpSigep\Pdf;

use PhpSigep\Bootstrap;
use PhpSigep\Model\ServicoAdicional;

/**
 * @author: Stavarengo
 */
class ListaDePostagem
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
     * @param \PhpSigep\Model\PreListaDePostagem $plp
     * @param int $idPlpCorreios
     */
    public function __construct($plp, $idPlpCorreios)
    {
        $this->plp           = $plp;
        $this->idPlpCorreios = $idPlpCorreios;

        $this->init();
    }

    public function render($dest='', $fileName = '')
    {
        $cacheKey = md5(serialize($this->plp) . $this->idPlpCorreios . get_class($this));
        if ($pdfContent = Bootstrap::getConfig()->getCacheInstance()->getItem($cacheKey)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="doc.pdf"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');
            echo $pdfContent;
        } else {
            $pdf    = $this->pdf;
            $k      = $pdf->k;
            $wInner = $pdf->w - $pdf->lMargin - $pdf->rMargin;
            
            $this->addPage();
    
            $this->writeTitle($k, $pdf, $wInner);
            $this->writeHeader($pdf, $k, $wInner);
            $pdf->Ln();
            $this->writeList();
            $pdf->Ln();
            $pdf->Ln();
            $this->writeBottom();
            $this->writeFooter();

            if($dest == 'S'){
               return $pdf->Output('',$dest);
            }
            else{
                $pdf->Output($fileName, $dest);
                Bootstrap::getConfig()->getCacheInstance()->setItem($cacheKey, $pdf->buffer);
            }
        }
    }

    /**
     * @param $pdf
     * @param $k
     * @param $wInner
     */
    private function writeHeader($pdf, $k, $wInner)
    {
// Cria o cabeçalho
        $yHeaderRect = $pdf->y + 10 / $k;
        $pdf->SetY($yHeaderRect);
        $pdf->SetFont('', 'B', 13);
        $pdf->CellXp($wInner, 'LISTA DE POSTAGEM', 'C', 1);

        $pdf->SetFont('', '', 10);
        $wHeaderCols = $wInner / 3;
        $plp         = $this->plp;

        $remetente = $plp->getRemetente();

        $remeAddress = $remetente->getLogradouro();
        $numero      = $remetente->getNumero();
        if (!$numero || strtolower($numero) == 'sn') {
            $remeAddress .= ', s/ nº';
        } else {
            $remeAddress .= ', #' . $numero;
        }
        if ($remetente->getComplemento()) {
            $remeAddress .= ' - ' . $remetente->getComplemento();
        }
        $remeAddress .= ' - Bairro: ' . $remetente->getBairro();
        $remeAddress .= ' - Cidade: ' . $remetente->getCidade() . '/' . $remetente->getUf();

        $pdf->setLineHeightPadding(50 / $k);
        $this->labeledText($pdf, 'Nº da lista:', $this->idPlpCorreios, $wHeaderCols);
        $this->labeledText($pdf, 'Cliente:', $remetente->getNome(), $wHeaderCols, 1);
        $this->labeledText($pdf, 'Contrato:', $plp->getAccessData()->getNumeroContrato(), $wHeaderCols);
        $this->labeledText($pdf, 'Cod. adm.:', $plp->getAccessData()->getCodAdministrativo(), $wHeaderCols);
        $this->labeledText($pdf, 'Cartão:', $plp->getAccessData()->getCartaoPostagem(), $wHeaderCols, 1);
        $this->labeledText($pdf, 'Remetente:', $remetente->getNome(), $wHeaderCols, 1);
        $this->labeledText($pdf, 'Telefone:', $remetente->getTelefone(), $wHeaderCols, 1);
        $yAboveFone = $pdf->y;
        $pdf->y -= ($pdf->getLineHeigth() * 2);
        $pdf->x += $wHeaderCols;
        $this->labeledText($pdf, 'Endereço:', $remeAddress, $wHeaderCols * 2, 1, 16 / $k);
        $yAboveAddress = $pdf->y;

        $yRec = ($yAboveFone > $yAboveAddress ? $yAboveFone : $yAboveAddress);
        $pdf->Rect($pdf->lMargin, $yHeaderRect, $wInner, $yRec - $yHeaderRect);
    }

    private function writeList()
    {
        $pdf = $this->pdf;
        $k   = $pdf->k;
        $pdf->SetFont('Courier', 'B', 8);
        $pdf->setLineHeightPadding(7 / $k);
        $y1         = $pdf->y;
        $lineHeigth = $pdf->getLineHeigth();
        $y2         = $pdf->y + $lineHeigth;

        // Cabeçalho da lista
        $space  = $pdf->GetStringWidth(' ');
        $xCol1  = $pdf->lMargin;
        $wCol1  = $space * 15;
        $xCol2  = $xCol1 + $wCol1 + $space;
        $wCol2  = $space * 9;
        $xCol3  = $xCol2 + $wCol2 + $space;
        $wCol3  = $space * 9;
        $xCol4  = $xCol3 + $wCol3 + $space;
        $wCol4  = $space * 2;
        $xCol5  = $xCol4 + $wCol4 + $space;
        $wCol5  = $space * 2;
        $xCol6  = $xCol5 + $wCol5 + $space;
        $wCol6  = $space * 2;
        $xCol7  = $xCol6 + $wCol6 + $space;
        $wCol7  = $space * 11;
        $xCol8  = $xCol7 + $wCol7 + $space;
        $wCol8  = $space * 8;
        $xCol9  = $xCol8 + $wCol8 + $space;
        $wCol9  = $space * 6;
        $xCol10 = $xCol9 + $wCol9 + $space;
        $wCol10 = $pdf->w - $pdf->rMargin - $xCol10;

        $this->writeListHeader(
            $pdf,
            $xCol1,
            $y2,
            $wCol1,
            $xCol2,
            $wCol2,
            $xCol3,
            $wCol3,
            $xCol4,
            $wCol4,
            $xCol5,
            $wCol5,
            $xCol6,
            $wCol6,
            $xCol7,
            $y1,
            $wCol7,
            $xCol8,
            $wCol8,
            $xCol9,
            $wCol9,
            $xCol10,
            $wCol10
        );

        $this->writeListBody(
            $pdf,
            $k,
            $y1,
            $y2,
            $xCol1,
            $wCol1,
            $xCol2,
            $wCol2,
            $xCol8,
            $wCol8,
            $xCol3,
            $wCol3,
            $xCol4,
            $wCol4,
            $xCol5,
            $wCol5,
            $xCol6,
            $wCol6,
            $xCol7,
            $wCol7,
            $xCol9,
            $wCol9,
            $xCol10,
            $wCol10
        );
    }

    private function labeledText(ImprovedFPDF $pdf, $label, $text, $maxW, $ln = 0, $multLines = false)
    {
        $pdf->saveState();

        $pdf->SetFont('', '');
        $wLabel = $pdf->GetStringWidthXd($label . '  ');

        $pdf->SetFont('', 'B');
        $xLabel = $pdf->x;
        $pdf->CellXp($wLabel, $label);

        $pdf->SetFont('', '');
        $maxTextW = $maxW - $wLabel;
        if ($multLines) {
            if (is_float($multLines) || is_int($multLines)) {
                $pdf->setLineHeightPadding($multLines);
            }
            $x = $pdf->x - $wLabel;
            $pdf->MultiCellXp($maxTextW, $text);
            if ($ln === 0) {
                $pdf->SetX($x + $maxTextW);
            } else if ($ln == 1) {
                $pdf->SetX($pdf->lMargin);
            } else {
                if ($ln == 2) {
                    $pdf->SetX($x);
                }
            }
        } else {
            while ($text && $maxTextW < $pdf->GetStringWidth($text)) {
                $text = substr($text, 0, strlen($text) - 1);
            }
            $pdf->CellXp($maxTextW, $text, '', $ln);
            if ($ln == 2) {
                $pdf->x -= $wLabel;
            }
        }

        $lastX = $pdf->x;
        $lastY = $pdf->y;

        $pdf->restoreLastState();

        if ($ln === 0) {
            $pdf->SetX($xLabel + $maxW);
        } else if ($ln === 1) {
            $pdf->SetXY($lastX, $lastY);
        } else if ($ln === 2) {
            $pdf->SetXY($lastX, $lastY);
        }
    }

    private function init()
    {
        $this->pdf = new ImprovedFPDF('P', 'in');
        $this->pdf->SetFont('Arial', '', 10);
    }

    /**
     * @param $pdf
     * @param $xCol1
     * @param $y2
     * @param $wCol1
     * @param $xCol2
     * @param $wCol2
     * @param $xCol3
     * @param $wCol3
     * @param $xCol4
     * @param $wCol4
     * @param $xCol5
     * @param $wCol5
     * @param $xCol6
     * @param $wCol6
     * @param $xCol7
     * @param $y1
     * @param $wCol7
     * @param $xCol8
     * @param $wCol8
     * @param $xCol9
     * @param $wCol9
     * @param $xCol10
     * @param $wCol10
     */
    private function writeListHeader($pdf, $xCol1, $y2, $wCol1, $xCol2, $wCol2, $xCol3, $wCol3, $xCol4, $wCol4, $xCol5, $wCol5, $xCol6, $wCol6, $xCol7, $y1, $wCol7, $xCol8, $wCol8, $xCol9, $wCol9, $xCol10, $wCol10)
    {
        $pdf->SetFont('Courier', 'B', 8);
        $pdf->SetXY($xCol1, $y2);
        $pdf->CellXp($wCol1, 'Nº do objeto');
        $pdf->SetX($xCol2);
        $pdf->CellXp($wCol2, 'CEP', 'C');
        $pdf->SetX($xCol3);
        $pdf->CellXp($wCol3, 'Peso(g)', 'C');
        $pdf->SetX($xCol4);
        $pdf->CellXp($wCol4, 'AR', 'C');
        $pdf->SetX($xCol5);
        $pdf->CellXp($wCol5, 'MP', 'C');
        $pdf->SetX($xCol6);
        $pdf->CellXp($wCol6, 'VD', 'C');
        $pdf->SetXY($xCol7, $y1);
        $pdf->MultiCellXp($wCol7, 'Valor declarado', null, 0, 'C');
        $pdf->SetXY($xCol8, $y1);
        $pdf->MultiCellXp($wCol8, 'Nota fiscal', null, 0, 'C');
        $pdf->SetXY($xCol9, $y2);
        $pdf->CellXp($wCol9, 'Volume', 'C');
        $pdf->SetX($xCol10);
        $pdf->CellXp($wCol10, 'Destinatário', 'C');
    }

    /**
     * @param $pdf
     * @param $k
     * @param $y1
     * @param $y2
     * @param $xCol1
     * @param $wCol1
     * @param $xCol2
     * @param $wCol2
     * @param $xCol8
     * @param $wCol8
     * @param $xCol3
     * @param $wCol3
     * @param $xCol4
     * @param $wCol4
     * @param $xCol5
     * @param $wCol5
     * @param $xCol6
     * @param $wCol6
     * @param $xCol7
     * @param $wCol7
     * @param $xCol9
     * @param $wCol9
     * @param $xCol10
     * @param $wCol10
     */
    private function writeListBody($pdf, $k, $y1, $y2, $xCol1, $wCol1, $xCol2, $wCol2, $xCol8, $wCol8, $xCol3, $wCol3, $xCol4, $wCol4, $xCol5, $wCol5, $xCol6, $wCol6, $xCol7, $wCol7, $xCol9, $wCol9, $xCol10, $wCol10)
    {
        $pdf->setLineHeightPadding(20 / $k);
        $lineHeigth    = $pdf->getLineHeigth();
        $plp           = $this->plp;
        $objetoPostais = $plp->getEncomendas();

        $i = 1;
        $pdf->SetFont('Courier', '', 8);
        foreach ($objetoPostais as $objetoPostal) {
            if ($y1 > $pdf->h - $pdf->tMargin - $pdf->bMargin) {
                $this->addPage();
                $i  = 1;
                $y1 = $pdf->tMargin;
                $y2 = $y1 + $lineHeigth;
                $this->writeListHeader(
                    $pdf,
                    $xCol1,
                    $y2,
                    $wCol1,
                    $xCol2,
                    $wCol2,
                    $xCol3,
                    $wCol3,
                    $xCol4,
                    $wCol4,
                    $xCol5,
                    $wCol5,
                    $xCol6,
                    $wCol6,
                    $xCol7,
                    $y1,
                    $wCol7,
                    $xCol8,
                    $wCol8,
                    $xCol9,
                    $wCol9,
                    $xCol10,
                    $wCol10
                );
                $pdf->SetFont('Courier', '', 8);
            }

            $y1 += $lineHeigth;
            $y2 += $lineHeigth;
            $pdf->SetY($y1);

            $temAr          = false;
            $temMp          = false;
            $temVd          = false;
            $valorDeclarado = null;
            foreach ($objetoPostal->getServicosAdicionais() as $servicoAdicional) {
                $valorDeclarado = $servicoAdicional->getValorDeclarado();
                if ($servicoAdicional->is(ServicoAdicional::SERVICE_AVISO_DE_RECEBIMENTO)) {
                    $temAr = true;
                } else if ($servicoAdicional->is(ServicoAdicional::SERVICE_MAO_PROPRIA)) {
                    $temMp = true;
                } else if ($valorDeclarado>0) {
                    $temVd = true;
                }
            }

            if ($i++ % 2 != 0) {
                $fc = 225;
                $pdf->SetFillColor($fc, $fc, $fc);
                $pdf->SetXY($xCol1, $y2);
                $pdf->Cell($pdf->w - $pdf->rMargin - $pdf->lMargin, $lineHeigth, '', 0, 0, '', true);
            }

            $pdf->SetXY($xCol1, $y2);
            $etiqueta = $objetoPostal->getEtiqueta();
            if ($etiqueta) {
                $etiquetaComDv = $etiqueta->getEtiquetaComDv();
            } else {
                $etiquetaComDv = '';
            }
            $pdf->CellXp($wCol1, $etiquetaComDv);
            $destino = $objetoPostal->getDestino();
            if ($destino instanceof \PhpSigep\Model\DestinoNacional) {
                $pdf->SetX($xCol2);
                $pdf->CellXp($wCol2, preg_replace('/[^\d]/', '', $destino->getCep()), 'C');
                $pdf->SetXY($xCol8, $y2);
                $pdf->MultiCellXp($wCol8, $destino->getNumeroNotaFiscal(), null, 0, 'C');
            }
            $pdf->SetXY($xCol3, $y2);
            $pdf->CellXp($wCol3, round($objetoPostal->getPeso()*1000), 'C');
            $pdf->SetX($xCol4);
            $pdf->CellXp($wCol4, ($temAr ? 'S' : 'N'), 'C');
            $pdf->SetX($xCol5);
            $pdf->CellXp($wCol5, ($temMp ? 'S' : 'N'), 'C');
            $pdf->SetX($xCol6);
            $pdf->CellXp($wCol6, ($temVd ? 'S' : 'N'), 'C');
            $pdf->SetX($xCol7);
            $pdf->MultiCellXp($wCol7, ($temVd ? $valorDeclarado : ''), null, 0, 'C');
            $pdf->SetXY($xCol9, $y2);
            $pdf->CellXp($wCol9, '1/1', 'C');
            $pdf->SetX($xCol10);
            $pdf->CellXp($wCol10, $objetoPostal->getDestinatario()->getNome(), 'C');
        }
    }

    /**
     * @param $k
     * @param $pdf
     * @param $wInner
     */
    private function writeTitle($k, $pdf, $wInner)
    {
// Adiciona a logo
        $logoCorreios = realpath(dirname(__FILE__) . '/logo-correios.png');
        $wLogo        = 110 / $k;
        $lPosLogo     = $pdf->x;
        $pdf->Image($logoCorreios, $lPosLogo, null, $wLogo);

        // Escreve o titulo do relatório
        $lPosTitle = $lPosLogo + $wLogo;
        $pdf->SetXY($lPosTitle, $pdf->tMargin);
        $pdf->SetFont('', 'B', 15);
        $pdf->CellXp($wInner - $wLogo, 'EMPRESA BRASILEIRA DE CORREIOS E TELÉGRAFOS', 'C', 1, 23 / $k);
    }

    private function writeBottom()
    {
        $pdf = $this->pdf;
        $k   = $pdf->k;

        $pdf->SetFont('Arial', '', 8);
        $pdf->setLineHeightPadding(7 / $k);

        $lineHeigth = $pdf->getLineHeigth();
        $wInner     = $pdf->w - $pdf->lMargin - $pdf->rMargin;

        $bottomH = $lineHeigth * 12.5;
        if ($pdf->y + $bottomH > $pdf->h - $pdf->bMargin) {
            $this->addPage();
        }

        $pdf->Rect($pdf->lMargin, $pdf->y, $wInner, $bottomH);

        $pdf->SetFont('', 'B');
        $pdf->y += ($lineHeigth / 2);
        $pdf->CellXp($wInner, 'APRESENTAR ESTA LISTA EM CASO DE PEDIDO DE INFORMAÇÕES', 'C', 1);
        $pdf->SetFont('', '');
        $pdf->Ln();
        $str     = 'Estou ciente do disposto na clásula terceria do contrato de prestação de serviços';
        $stringW = $pdf->GetStringWidthXd($str);
        $pdf->CellXp($stringW, $str);
        $pdf->CellXp($wInner - $stringW, 'Carimbo e assinatura / Matrícula dos Correios', 'R', 1);

        $pdf->y += ($lineHeigth * 4);
        $lineW  = 250 / $k;
        $lineX1 = $pdf->x + 20 / $k;
        $lineX2 = $lineX1 + $lineW;
        $pdf->Line($lineX1, $pdf->y, $lineX2, $pdf->y);
        $pdf->x = $lineX1;
        $pdf->y += ($lineHeigth / 2);
        $pdf->CellXp($lineW, 'ASSINATURA DO REMETENTE', 'C', 2);
        $pdf->y += ($lineHeigth * 2);
        $pdf->CellXp($lineW, 'Obs: 1º via p/ a Unidade de Postagem e 2º via p/ o cliente', 'C');
    }

    private function writeFooter()
    {
        $pdf = $this->pdf;


        $pdf->AutoPageBreak = false;
        $wInner             = $pdf->w - $pdf->lMargin - $pdf->rMargin;
        $dataEmissao        = "Emitido em " . date('d/m/Y \à\s H:i:s');

        foreach ($pdf->pages as $pNumber => $page) {
            $pdf->page = $pNumber;

            $pdf->SetFont('Arial', '', 8);

            $pdf->x = $pdf->lMargin;
            $pdf->y = $pdf->h - $pdf->bMargin;

            $pdf->CellXp($wInner, $dataEmissao);

            $pdf->x = $pdf->lMargin;
            $str    = "Página " . $pNumber . " de " . count($pdf->pages);

            $pdf->CellXp($wInner, $str, 'R');
        }
    }

    private function addPage()
    {
        $pdf = $this->pdf;
        $pdf->AddPage();

        if (Bootstrap::getConfig()->getSimular()) {
            $this->pdf->SetFont('Arial', 'B', 50);
            $this->pdf->SetTextColor(200, 200, 200);
            $hInner     = $pdf->h - $pdf->tMargin - $pdf->bMargin;
            $lineHeigth = $pdf->getLineHeigth();
            $pdf->SetY($hInner / 2 - $lineHeigth / 2);
            $this->pdf->MultiCellXp(
                $this->pdf->w - $this->pdf->lMargin - $this->pdf->rMargin,
                "Simulação Documento sem valor",
                null,
                0,
                'C'
            );

            $this->pdf->SetTextColor(0, 0, 0);
        }

        $pdf->x = $pdf->lMargin;
        $pdf->y = $pdf->tMargin;
    }
}

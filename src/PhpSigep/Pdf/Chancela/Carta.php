<?php
namespace PhpSigep\Pdf\Chancela;

use PhpSigep\Pdf\Script\CircularText;
use PhpSigep\Pdf\Script\Elipse;

/**
 * @author: Stavarengo
 */
class Carta extends AbstractChancela
{

    public function draw(\PhpSigep\Pdf\ImprovedFPDF $pdf)
    {
        $pdf->saveState();

        // quantos mm cabem dentro de um pt do pdf
        $un = 72 / 25.4;

        // Desenha o elipse
        $k         = $pdf->k;
        $wRect     = $un * 30 / $k;
        $h         = $un * 28 / $k;
        $lineWidth = 2 / $k;
        $pdf->SetLineWidth($lineWidth);
        $x      = $this->x;
        $y      = $this->y;
        $rx     = $wRect / 2;
        $ry     = $h / 2;
        $elipse = new Elipse();
        $pdf->SetDrawColor(0, 0, 0);
        $elipse->ellipse($pdf, $x, $y, $rx, $ry);

        // Escreve o texto
        $pdf->SetFont('Arial', 'BI');
        $pdf->SetXY($x, $y + 10 / $k);
        $fontSize = 15;
        $texto    = 'Carta';
        $pdf->SetFontSize($fontSize);
        $pdf->Cell($wRect, 23 / $k, $texto, 0, 2, 'C');

        //Faz os dois riscos brancos da parte inferior - lado esquerdo
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(3 / $k);
        $x1 = $x + 11 / $k;
        $x2 = $x1 - 1 / $k;
        $y1 = $y + $h - 15.5 / $k;
        $y2 = $y1 + (1 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);
        $space = 3.5 / $k;
        $x1 += $space;
        $x2 = $x1 - 1 / $k;
        $y1 += $space;
        $y2 = $y1 + (1 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 = $x + 19.3 / $k;
        $x2 = $x1 - .85 / $k;
        $y1 = $y + 57.26 / $k;
        $y2 = $y1 + .85 / $k;
        $pdf->Line($x1, $y1, $x2, $y2);

        //Faz os dois riscos brancos da parte inferior - lado direito
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(3 / $k);
        $x1 = $x + $wRect - 7.5 / $k;
        $x2 = $x1 + 1 / $k;
        $y1 = $y + $h - 17.5 / $k;
        $y2 = $y1 + (1 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);
        $space = 3.5 / $k;
        $x1 += $space - 7.6 / $k;
        $x2 = $x1 + 1 / $k;
        $y1 += $space - .4 / $k;
        $y2 = $y1 + (1 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 = $x1 - 4 / $k;
        $x2 = $x1 + .85 / $k;
        $y1 = $y + 58.26 / $k;
        $y2 = $y1 + .85 / $k;
        $pdf->Line($x1, $y1, $x2, $y2);

        // Número contrato e DR
        $pdf->SetFont('', '', 6);
        $texto = $this->accessData->getNumeroContrato() . '/' . $this->accessData->getAnoContrato(
            ) . '-DR/' . $this->accessData->getDiretoria()->getSigla();
        $pdf->Cell($wRect, 6 / $k, $texto, 0, 2, 'C');

        // Nome do remetente
        $pdf->SetFont('', 'B', 9);
        $pdf->MultiCell($wRect, 9 / $k, $pdf->_($this->nomeRemetente), 0, 'C');

        // Escreve CORREIOS na parte inferior
        $text = 'CORREIOS';
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(15 / $k);
        $x1 = $x + 24.5 / $k;
        $x2 = $x1 + $pdf->GetStringWidth($text) - 9.5 / $k;
        $y1 = $y + $h - 6 / $k;
        $pdf->Line($x1, $y1, $x2, $y1);
        $pdf->SetFontSize(9);
        $circularText = new CircularText();
        $circularText->CircularText($pdf, $x + $wRect / 2 + .5, ($y / $k) - 50, 80, $text, 'bottom', 110);

        $pdf->restoreLastState();
    }

}

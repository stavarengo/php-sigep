<?php
namespace PhpSigep\Pdf\Chancela;

use PhpSigep\Pdf\Script\Elipse;

/**
 * @author: Stavarengo
 */
class Carta2016 extends AbstractChancela
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

        // Número contrato e DR
        $pdf->SetFont('', '', 7);
        $texto = $this->accessData->getNumeroContrato() . '/' . $this->accessData->getAnoContrato(
            ) . '-DR/' . $this->accessData->getDiretoria()->getSigla();
        $pdf->Cell($wRect, 6 / $k, $texto, 0, 2, 'C');

        // Nome do remetente
        $pdf->SetFont('', 'B', 9);
        $pdf->MultiCell($wRect, 9 / $k, $pdf->_($this->nomeRemetente), 0, 'C');

        // Insere a logo do correios na parte inferior
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(15 / $k);
        $x1 = $x + 24.5 / $k;
        $x2 = $x1 + $pdf->GetStringWidth('CORREIOS') - 9.5 / $k;
        $y1 = $y + $h - 6 / $k;
        $pdf->Image(realpath(dirname(__FILE__)) . '/../correios-logo.png', $x1 - 2 , $y1 - 5);

        $pdf->restoreLastState();
    }

}

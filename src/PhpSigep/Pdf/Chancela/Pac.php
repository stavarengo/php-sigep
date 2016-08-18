<?php
namespace Sigep\Pdf\Chancela;

/**
 * @author: Stavarengo
 */
class Pac
{

    /**
     * @var int
     */
    private $x;
    /**
     * @var int
     */
    private $y;
    /**
     * @var string
     */
    private $nomeRemetente;
    /**
     * @var \Sigep\contrato
     */
    private $contrato;

    /**
     * @param int $x
     * @param int $y
     * @param string $nomeRemetente
     * @param \Sigep\contrato $contrato
     */
    public function __construct($x, $y, $nomeRemetente, \Sigep\contrato $contrato)
    {
        $this->x             = $x;
        $this->y             = $y;
        $this->nomeRemetente = $nomeRemetente;
        $this->contrato    = $contrato;
    }

    public function draw(\Sigep\Pdf\ImprovedFPDF $pdf)
    {
        $pdf->saveState();

        // quantos mm cabem dentro de um pt do pdf
        $un = 72 / 25.4;

        // Desenha o retangulo
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $k         = $pdf->k;
        $wRect     = $un * 31.5 / $k;
        $h         = $un * 22.5 / $k;
        $lineWidth = 2 / $k;
        $pdf->SetLineWidth($lineWidth);
        $x = $this->x;
        $y = $this->y;
        $pdf->RoundedRect($x, $y, $wRect, $h, 5);

        // Escreve o texto PAC
        $pdf->SetFont('Arial', 'B', 27);
        $pdf->SetXY($x, $y + 4.5 / $k);
        $pdf->Cell($wRect, 27 / $k, 'PAC', 0, 2, 'C');

        // Número contrato e DR
        $pdf->SetFont('', '', 7);
        $texto = $this->contrato->getNumeroContrato() . '/' . $this->contrato->getAnoContrato(
            ) . '-DR/' . $this->contrato->getDiretoria()->getSigla();
        $pdf->Cell($wRect, 6 / $k, $texto, 0, 2, 'C');

        // Nome do remetente
        $pdf->SetFont('', 'B', 9);
        $pdf->MultiCell($wRect, 8 / $k, $pdf->_($this->nomeRemetente), 0, 'C');

        // Escreve o texto CORREIOS
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(2 / $k);

        $x1    = $x + (3.15 * $un / $k);
        $x2    = $x1 + (0.54 / $k);
        $y1    = $y + $h + (.45 / $k);
        $y2    = $y1 - (0.9 / $k);
        $space = 3.6 / $k;
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 += $space;
        $x2 += $space;
        $x1 += $space;
        $x2 += $space;

        $x1 += $space;
        $pdf->SetFontSize(9);
        $stringWidth = $pdf->GetStringWidth('CORREIOS');
        $x2 += $space + $stringWidth;
        $pdf->SetLineWidth(2.5 / $k);
        $pdf->Line($x1, $y + $h, $x2, $y + $h);

        $pdf->Image(realpath(dirname(__FILE__)) . '/../correios-logo.png', $x1 , $y1 - 3);
        
        $pdf->restoreLastState();
    }
}
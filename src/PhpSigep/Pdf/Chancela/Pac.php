<?php
namespace PhpSigep\Pdf\Chancela;

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
     * @var \PhpSigep\Model\AccessData
     */
    private $accessData;

    /**
     * @param int $x
     * @param int $y
     * @param string $nomeRemetente
     * @param \PhpSigep\Model\AccessData $accessData
     */
    public function __construct($x, $y, $nomeRemetente, \PhpSigep\Model\AccessData $accessData)
    {
        $this->x             = $x;
        $this->y             = $y;
        $this->nomeRemetente = $nomeRemetente;
        $this->accessData    = $accessData;
    }

    public function draw(\PhpSigep\Pdf\ImprovedFPDF $pdf)
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
        $pdf->Rect($x, $y, $wRect, $h);

        // Escreve o texto PAC
        $pdf->SetFont('Arial', 'B', 27);
        $pdf->SetXY($x, $y + 4.5 / $k);
        $pdf->Cell($wRect, 27 / $k, 'PAC', 0, 2, 'C');

        // Número contrato e DR
        $pdf->SetFont('', '', 6);
        $texto = $this->accessData->getNumeroContrato() . '/' . $this->accessData->getAnoContrato(
            ) . '-DR/' . $this->accessData->getDiretoria()->getSigla();
        $pdf->Cell($wRect, 6 / $k, $texto, 0, 2, 'C');

        // Nome do remetente
        $pdf->SetFont('', 'B', 9);
        $pdf->MultiCell($wRect, 9 / $k, $pdf->_($this->nomeRemetente), 0, 'C');

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
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 += $space;
        $x2 += $space;
        $pdf->Line($x1, $y1, $x2, $y2);

        $texto = 'CORREIOS';
        $x1 += $space;
        $pdf->SetFontSize(9);
        $stringWidth = $pdf->GetStringWidth($texto);
        $x2 += $space + $stringWidth;
        $pdf->SetLineWidth(2.5 / $k);
        $pdf->Line($x1, $y + $h, $x2, $y + $h);
        $pdf->Text($x1, $y1 + .9, $texto);

        $x1 += $space + $pdf->GetStringWidth($texto);
        $x2 += $space;
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 += $space;
        $x2 += $space;
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 += $space;
        $x2 += $space;
        $pdf->Line($x1, $y1, $x2, $y2);

        $pdf->restoreLastState();
    }
}
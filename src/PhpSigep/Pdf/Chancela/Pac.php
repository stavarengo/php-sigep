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
        $wRect     = $un * 35 / $k;
        $h         = $un * 25 / $k;
        $lineWidth = 2 / $k;
        $pdf->SetLineWidth($lineWidth);
        $x = $this->x;
        $y = $this->y;
        $pdf->Rect($x, $y, $wRect, $h);

        // Escreve o texto PAC
        $pdf->SetFont('Arial', 'B', 30);
        $pdf->SetXY($x, $y + 5 / $k);
        $pdf->Cell($wRect, 30 / $k, 'PAC', 0, 2, 'C');

        // Número contrato e DR
        $pdf->SetFont('', '', 7);
        $texto = $this->accessData->getNumeroContrato() . '/' . $this->accessData->getAnoContrato(
            ) . '-DR/' . $this->accessData->getDiretoria()->getSigla();
        $pdf->Cell($wRect, 7 / $k, $texto, 0, 2, 'C');

        // Nome do remetente
        $pdf->SetFont('', 'B', 9);
        $pdf->MultiCell($wRect, 9 / $k, $pdf->_($this->nomeRemetente), 0, 'C');

        // Escreve o texto CORREIOS
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(2 / $k);

        $x1    = $x + (3.5 * $un / $k);
        $x2    = $x1 + (0.6 / $k);
        $y1    = $y + $h + (.5 / $k);
        $y2    = $y1 - (1 / $k);
        $space = 4 / $k;
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 += $space;
        $x2 += $space;
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 += $space;
        $x2 += $space;
        $pdf->Line($x1, $y1, $x2, $y2);

        $texto = 'CORREIOS';
        $x1 += $space;
        $pdf->SetFontSize(10);
        $stringWidth = $pdf->GetStringWidth($texto);
        $x2 += $space + $stringWidth;
        $pdf->SetLineWidth(2.5 / $k);
        $pdf->Line($x1, $y + $h, $x2, $y + $h);
        $pdf->Text($x1, $y1, $texto);
//		$pdf->Cell($stringWidth, 10/$k, $texto, 0);

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
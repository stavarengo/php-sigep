<?php
namespace PhpSigep\Pdf\Chancela;

use PhpSigep\Pdf\Script\CircularText;
use PhpSigep\Pdf\Script\Elipse;

/**
 * @author: Stavarengo
 */
class Sedex
{

    const SERVICE_E_SEDEX    = 1;
    const SERVICE_SEDEX      = 2;
    const SERVICE_SEDEX_10   = 3;
    const SERVICE_SEDEX_HOJE = 4;

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
    private $tipoServico;

    /**
     * @param int $x
     * @param int $y
     * @param string $nomeRemetente
     * @param int $tipoServico
     *        Uma das contantes {@link Sedex}::SERVICO_*
     * @param \PhpSigep\Model\AccessData $accessData
     */
    public function __construct($x, $y, $nomeRemetente, $tipoServico, \PhpSigep\Model\AccessData $accessData)
    {
        $this->x             = $x;
        $this->y             = $y;
        $this->nomeRemetente = $nomeRemetente;
        $this->tipoServico   = $tipoServico;
        $this->accessData    = $accessData;
    }

    public function draw(\PhpSigep\Pdf\ImprovedFPDF $pdf)
    {
        $pdf->saveState();

        // quantos mm cabem dentro de um pt do pdf
        $un = 72 / 25.4;

        // Desenha o retangulo
        $k         = $pdf->k;
        $wRect     = $un * 35 / $k;
        $h         = $un * 23 / $k;
        $lineWidth = 2 / $k;
        $pdf->SetLineWidth($lineWidth);
        $x      = $this->x;
        $y      = $this->y;
        $rx     = $wRect / 2;
        $ry     = $h / 2;
        $elipse = new Elipse();
        $pdf->SetDrawColor(0, 0, 0);
        $elipse->ellipse($pdf, $x, $y, $rx, $ry);

        // Escreve o texto PAC
        $pdf->SetFont('Arial', 'BI');
        $pdf->SetXY($x, $y + 5 / $k);
        $fontSize = 15;
        if ($this->tipoServico == self::SERVICE_E_SEDEX) {
            $texto = 'e-SEDEX';
        } else if ($this->tipoServico == self::SERVICE_SEDEX) {
            $texto = 'SEDEX';
        } else if ($this->tipoServico == self::SERVICE_SEDEX_10) {
            $fontSize = 13;
            $texto    = 'SEDEX 10';
        } else {
            if ($this->tipoServico == self::SERVICE_SEDEX_HOJE) {
                $fontSize = 12;
                $texto    = 'SEDEX Hoje';
            }
        }
        $pdf->SetFontSize($fontSize);
        $pdf->Cell($wRect, 23 / $k, $texto, 0, 2, 'C');

        //Faz os dois riscos brancos da parte superior
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(4 / $k);
        $x1 = $x + 11 / $k;
        $x2 = $x1 + (4.4 / $k);
        $y1 = $y + 12 / $k;
        $y2 = $y1 - (3 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 = $x + $wRect - 11 / $k;
        $x2 = $x1 - (4.4 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);

        //Faz os dois riscos brancos da parte inferior - lado esquerdo
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(3 / $k);
        $x1 = $x + 11 / $k;
        $x2 = $x1 - 1 / $k;
        $y1 = $y + $h - 13.5 / $k;
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
        $x1 = $x + $wRect - 11 / $k;
        $x2 = $x1 + 1 / $k;
        $y1 = $y + $h - 13.5 / $k;
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
        $pdf->SetFont('', '', 7);
        $texto = $this->accessData->getNumeroContrato() . '/' . $this->accessData->getAnoContrato(
            ) . '-DR/' . $this->accessData->getDiretoria()->getSigla();
        $pdf->Cell($wRect, 7 / $k, $texto, 0, 2, 'C');

        // Nome do remetente
        $pdf->SetFont('', 'B', 9);
        $pdf->MultiCell($wRect, 9 / $k, $pdf->_($this->nomeRemetente), 0, 'C');

        // Escreve CORREIOS na parte inferior
        $text = 'CORREIOS';
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(10 / $k);
        $x1 = $x + 28 / $k;
        $x2 = $x1 + $pdf->GetStringWidth($text) - 3 / $k;
        $y1 = $y + $h - 3.8 / $k;
        $pdf->Line($x1, $y1, $x2, $y1);
        $pdf->SetFontSize(9);
        $circularText = new CircularText();
        $circularText->CircularText($pdf, $x + $wRect / 2 + 1, $y - 6.5 / $k, 75, $text, 'bottom');

        $pdf->restoreLastState();
    }

}

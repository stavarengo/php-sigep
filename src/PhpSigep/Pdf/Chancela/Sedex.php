<?php
namespace Sigep\Pdf\Chancela;

use Sigep\Pdf\Script\CircularText;
use Sigep\Pdf\Script\Elipse;

/**
 * @author: Stavarengo
 */
class Sedex
{

    const SERVICE_E_SEDEX    = 1;
    const SERVICE_SEDEX      = 2;
    const SERVICE_SEDEX_10   = 3;
    const SERVICE_SEDEX_HOJE = 4;
    const SERVICE_SEDEX_12   = 5;

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
    private $tipoServico;

    /**
     * @param int $x
     * @param int $y
     * @param string $nomeRemetente
     * @param int $tipoServico
     *        Uma das contantes {@link Sedex}::SERVICO_*
     * @param \Sigep\contrato $contrato
     */
    public function __construct($x, $y, $nomeRemetente, $tipoServico, \Sigep\contrato $contrato)
    {
        $this->x             = $x;
        $this->y             = $y;
        $this->nomeRemetente = $nomeRemetente;
        $this->tipoServico   = $tipoServico;
        $this->contrato    = $contrato;
    }

    public function draw(\Sigep\Pdf\ImprovedFPDF $pdf)
    {
        $pdf->saveState();

        // quantos mm cabem dentro de um pt do pdf
        $un = 72 / 25.4;

        // Desenha o elipse
        $k         = $pdf->k;
        $wRect     = $un * 32 / $k;
        $h         = $un * 20 / $k;
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
        $fontSize = 12;
        if ($this->tipoServico == self::SERVICE_E_SEDEX) {
            $texto = 'e-SEDEX';
        } else if ($this->tipoServico == self::SERVICE_SEDEX) {
            $texto = 'SEDEX';
        } else if ($this->tipoServico == self::SERVICE_SEDEX_10) {
            $fontSize = 11;
            $texto    = 'SEDEX 10';
        } else if ($this->tipoServico == self::SERVICE_SEDEX_12) {
            $fontSize = 11;
            $texto    = 'SEDEX 12';
        } else {
            if ($this->tipoServico == self::SERVICE_SEDEX_HOJE) {
                $fontSize = 11;
                $texto    = 'SEDEX Hoje';
            }
        }
        
        $pdf->SetFontSize($fontSize);
        $pdf->Cell($wRect, 18 / $k, $texto, 0, 2, 'C');

        // Número contrato e DR
        $pdf->SetFont('', '', 7);
        $texto = $this->contrato->getNumeroContrato() . '/' . $this->contrato->getAnoContrato() . '-DR/' . $this->contrato->getDiretoria()->getSigla();
        $pdf->Cell($wRect, 6 / $k, $texto, 0, 2, 'C');

        // Nome do remetente
        $pdf->SetFont('', 'B', 9);
        $pdf->MultiCell($wRect, 10 / $k, $pdf->_($this->nomeRemetente), 0, 'C');

        // Insere a logo do correios na parte inferior
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(10 / $k);
        $x1 = $x / $k;
        $x2 = $x1 + $pdf->GetStringWidth('CORREIOS') + 40 / $k;
        $y1 = $y + $h - 3.8 / $k;
        $pdf->Line($x1, $y1, $x2, $y1);

        $pdf->Image(realpath(dirname(__FILE__)) . '/../correios-logo.png', $x1 + 9 , $y1 - 3);
        
        $pdf->restoreLastState();
    }
}

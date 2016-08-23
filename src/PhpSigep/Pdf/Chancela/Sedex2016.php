<?php
namespace PhpSigep\Pdf\Chancela;

use PhpSigep\Pdf\Script\Elipse;

/**
 * @author: Stavarengo
 */
class Sedex2016 extends AbstractChancela
{

    const SERVICE_E_SEDEX    = 1;
    const SERVICE_SEDEX      = 2;
    const SERVICE_SEDEX_10   = 3;
    const SERVICE_SEDEX_HOJE = 4;
    const SERVICE_SEDEX_12   = 5;

    private $tipoServico;

    /**
     * @param int $x
     * @param int $y
     * @param string $nomeRemetente
     * @param int $tipoServico
     *        Uma das contantes {@link Sedex}::SERVICE_*
     * @param \PhpSigep\Model\AccessData $accessData
     */
    public function __construct($x, $y, $nomeRemetente, $tipoServico, \PhpSigep\Model\AccessData $accessData)
    {
        parent::__construct($x, $y, $nomeRemetente, $accessData);
        
        $this->setServicoDePostagem($tipoServico);
    }

    public function setServicoDePostagem($servicoPostagem)
    {
        $rClass = new \ReflectionClass(__CLASS__);
        $servicos = $rClass->getConstants();
        
        if (in_array($servicoPostagem, $servicos) === false) {
            throw new \InvalidArgumentException('O serviço de postagem deve ser uma das constantes da classe');
        }
        $this->tipoServico = $servicoPostagem;
    }
    
    public function draw(\PhpSigep\Pdf\ImprovedFPDF $pdf)
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

        // Escreve o texto Sedex
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
        $texto = $this->accessData->getNumeroContrato() . '/' . $this->accessData->getAnoContrato() 
               . '-DR/' . $this->accessData->getDiretoria()->getSigla();
        $pdf->Cell($wRect, 6 / $k, $texto, 0, 2, 'C');

        // Nome do remetente
        $pdf->SetFont('', 'B', 9);
        $pdf->MultiCell($wRect, 10 / $k, $pdf->_($this->nomeRemetente), 0, 'C');

        // Insere a logo do correios na parte inferior
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(10 / $k);
        $x1 = $x - 4 / $k;
        $x2 = $x1 + $pdf->GetStringWidth('CORREIOS') + 40 / $k;
        $y1 = $y + $h - 3 / $k;
        $pdf->Line($x1, $y1, $x2, $y1);

        $pdf->Image(realpath(dirname(__FILE__)) . '/../correios-logo.png', $x1 + 9 , $y1 - 3);
        
        $pdf->restoreLastState();
    }
}

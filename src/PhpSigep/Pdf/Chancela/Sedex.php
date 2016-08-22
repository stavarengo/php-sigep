<?php
namespace PhpSigep\Pdf\Chancela;

use PhpSigep\Pdf\Script\CircularText;
use PhpSigep\Pdf\Script\Elipse;

/**
 * @author: Stavarengo
 */
class Sedex extends AbstractChancela
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
        $wRect     = $un * 31.5 / $k;
        $h         = $un * 20.7 / $k;
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

        //Faz os dois riscos brancos da parte superior
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(4 / $k);
        $x1 = $x + 9.9 / $k;
        $x2 = $x1 + (3.96 / $k);
        $y1 = $y + 10.8 / $k;
        $y2 = $y1 - (2.7 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 = $x + $wRect - 9.9 / $k;
        $x2 = $x1 - (3.96 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);

        //Faz os dois riscos brancos da parte inferior - lado esquerdo
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(2.7 / $k);
        $x1 = $x + 9.9 / $k;
        $x2 = $x1 - 0.99 / $k;
        $y1 = $y + $h - 12.15 / $k;
        $y2 = $y1 + (.99 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);
        $space = 2.835 / $k;
        $x1 += $space;
        $x2 = $x1 - .99 / $k;
        $y1 += $space;
        $y2 = $y1 + (.99 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 = $x + 17.37 / $k;
        $x2 = $x1 - 0.765 / $k;
        $y1 = $y + 51.534 / $k;
        $y2 = $y1 + 0.765 / $k;
        $pdf->Line($x1, $y1, $x2, $y2);

        //Faz os dois riscos brancos da parte inferior - lado direito
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(2.7 / $k);
        $x1 = $x + $wRect - 9.9 / $k;
        $x2 = $x1 + 0.99 / $k;
        $y1 = $y + $h - 12.15 / $k;
        $y2 = $y1 + (0.99 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);
        $space = 2.835 / $k;
        $x1 += $space - 6.84 / $k;
        $x2 = $x1 + .99 / $k;
        $y1 += $space - 0.36 / $k;
        $y2 = $y1 + (.99 / $k);
        $pdf->Line($x1, $y1, $x2, $y2);
        $x1 = $x1 - 3.6 / $k;
        $x2 = $x1 + 0.765 / $k;
        $y1 = $y + 52.434 / $k;
        $y2 = $y1 + .765 / $k;
        $pdf->Line($x1, $y1, $x2, $y2);

        // Número contrato e DR
        $pdf->SetFont('', '', 6);
        $texto = $this->accessData->getNumeroContrato() . '/' . $this->accessData->getAnoContrato() . '-DR/' . $this->accessData->getDiretoria()->getSigla();
        $pdf->Cell($wRect, 6 / $k, $texto, 0, 2, 'C');

        // Nome do remetente
        $pdf->SetFont('', 'B', 8);
        $pdf->MultiCell($wRect, 8 / $k, $pdf->_($this->nomeRemetente), 0, 'C');

        // Escreve CORREIOS na parte inferior
        $text = 'CORREIOS';
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->SetLineWidth(10 / $k);
        $x1 = $x + 23.60 / $k;
        $x2 = ($x1) + $pdf->GetStringWidth($text) - 1 / $k;
        $y1 = $y + $h - 3.8 / $k;
        $pdf->Line($x1, $y1, $x2, $y1);
        $pdf->SetFontSize(8);
        $circularText = new CircularText();
        $circularText->CircularText($pdf, $x + $wRect / 2, -144 / $k, 75, $text, 'bottom');

        $pdf->restoreLastState();
    }

}

<?php
namespace PhpSigep\Pdf\Chancela;

class Pac2018 extends AbstractChancela
{
    public function draw(\PhpSigep\Pdf\ImprovedFPDF $pdf)
    {
        $pdf->saveState();

        // Desenha o retangulo
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);

        $x = $this->x;
        $y = $this->y;
        //$pdf->RoundedRect($x, $y, $wRect, $h, 5);
        $pdf->Circle($x, $y, 10, 'F');
        
        $pdf->restoreLastState();
    }
}

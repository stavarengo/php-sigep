<?php
namespace PhpSigep\Pdf\Chancela;

class Mini2018 extends AbstractChancela
{
    public function draw(\PhpSigep\Pdf\ImprovedFPDF $pdf)
    {
        $pdf->saveState();

        // Desenha o retangulo
        $pdf->SetFillColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);

        $x = $this->x;
        $y = $this->y;
        $pdf->Rect($this->x, $this->y, 20, 20, 'F');

        $pdf->restoreLastState();
    }
}

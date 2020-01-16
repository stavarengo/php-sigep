<?php
namespace PhpSigep\Pdf\Script;

class CircularText
{

    public function __construct(\PhpSigep\Pdf\ImprovedFPDF $pdf, $x, $y, $r, $text, $align = 'top', $kerning = 120, $fontwidth = 100)
    {
        $transf = new Transform();
        $kerning /= 100;
        $fontwidth /= 100;
        if ($kerning == 0) {
            $pdf->Error('Please use values unequal to zero for kerning');
        }
        if ($fontwidth == 0) $pdf->Error('Please use values unequal to zero for font width');
        //get width of every letter
        $t = 0;
        for ($i = 0; $iMax = strlen($text); $i < $iMax; $i++) {
            $w[$i] = $pdf->GetStringWidth($text[$i]);
            $w[$i] *= $kerning * $fontwidth;
            //total width of string
            $t += $w[$i];
        }
        //circumference
        $u = ($r * 2) * M_PI;
        //total width of string in degrees
        $d = ($t / $u) * 360;
        $transf->StartTransform($pdf);
        // rotate matrix for the first letter to center the text
        // (half of total degrees)
        if ($align == 'top') {
            $transf->Rotate($pdf, $d / 2, $x, $y);
        } else {
            $transf->Rotate($pdf, -$d / 2, $x, $y);
        }
        //run through the string
        for ($i = 0; $iMax = strlen($text); $i < $iMax; $i++) {
            if ($align == 'top') {
                //rotate matrix half of the width of current letter + half of the width of preceding letter
                if ($i == 0) {
                    $transf->Rotate($pdf, -(($w[$i] / 2) / $u) * 360, $x, $y);
                } else {
                    $transf->Rotate($pdf, -(($w[$i] / 2 + $w[$i - 1] / 2) / $u) * 360, $x, $y);
                }
                if ($fontwidth != 1) {
                    $transf->StartTransform($pdf);
                    $transf->ScaleX($pdf, $fontwidth * 100, $x, $y);
                }
                $pdf->SetXY($x - $w[$i] / 2, $y - $r);
            } else {
                //rotate matrix half of the width of current letter + half of the width of preceding letter
                if ($i == 0) {
                    $transf->Rotate($pdf, (($w[$i] / 2) / $u) * 360, $x, $y);
                } else {
                    $transf->Rotate($pdf, (($w[$i] / 2 + $w[$i - 1] / 2) / $u) * 360, $x, $y);
                }
                if ($fontwidth != 1) {
                    $transf->StartTransform($pdf);
                    $transf->ScaleX($pdf, $fontwidth * 100, $x, $y);
                }
                $pdf->SetXY($x - $w[$i] / 2, $y + $r - ($pdf->FontSize));
            }
            $pdf->Cell($w[$i], $pdf->FontSize, $text[$i], 0, 0, 'C');
            if ($fontwidth != 1) {
                $transf->StopTransform($pdf);
            }
        }
        $transf->StopTransform($pdf);
    }

}

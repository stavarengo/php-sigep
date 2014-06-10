<?php
namespace PhpSigep\Pdf\Script;

class Elipse
{

    public function ellipse(\PhpSigep\Pdf\ImprovedFPDF $pdf, $x, $y, $rx, $ry, $style = 'D')
    {
        if ($style == 'F') {
            $op = 'f';
        } elseif ($style == 'FD' or $style == 'DF') {
            $op = 'B';
        } else {
            $op = 'S';
        }

        $x += $rx;
        $y += $ry;

        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;
        $k  = $pdf->k;
        $h  = $pdf->h;
        $pdf->_out(
            sprintf(
                '%.2f %.2f m %.2f %.2f %.2f %.2f %.2f %.2f c',
                ($x + $rx) * $k,
                ($h - $y) * $k,
                ($x + $rx) * $k,
                ($h - ($y - $ly)) * $k,
                ($x + $lx) * $k,
                ($h - ($y - $ry)) * $k,
                $x * $k,
                ($h - ($y - $ry)) * $k
            )
        );
        $pdf->_out(
            sprintf(
                '%.2f %.2f %.2f %.2f %.2f %.2f c',
                ($x - $lx) * $k,
                ($h - ($y - $ry)) * $k,
                ($x - $rx) * $k,
                ($h - ($y - $ly)) * $k,
                ($x - $rx) * $k,
                ($h - $y) * $k
            )
        );
        $pdf->_out(
            sprintf(
                '%.2f %.2f %.2f %.2f %.2f %.2f c',
                ($x - $rx) * $k,
                ($h - ($y + $ly)) * $k,
                ($x - $lx) * $k,
                ($h - ($y + $ry)) * $k,
                $x * $k,
                ($h - ($y + $ry)) * $k
            )
        );
        $pdf->_out(
            sprintf(
                '%.2f %.2f %.2f %.2f %.2f %.2f c %s',
                ($x + $lx) * $k,
                ($h - ($y + $ry)) * $k,
                ($x + $rx) * $k,
                ($h - ($y + $ly)) * $k,
                ($x + $rx) * $k,
                ($h - $y) * $k,
                $op
            )
        );
    }

}
<?php
namespace PhpSigep\Pdf\Script;

class Transform
{

    public function __construct(\PhpSigep\Pdf\ImprovedFPDF $pdf, $tm)
    {
        $pdf->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $tm[0], $tm[1], $tm[2], $tm[3], $tm[4], $tm[5]));
    }

    public function StartTransform(\PhpSigep\Pdf\ImprovedFPDF $pdf)
    {
        //save the current graphic state
        $pdf->_out('q');
    }

    public function ScaleX(\PhpSigep\Pdf\ImprovedFPDF $pdf, $s_x, $x = '', $y = '')
    {
        $this->Scale($pdf, $s_x, 100, $x, $y);
    }

    public function ScaleY(\PhpSigep\Pdf\ImprovedFPDF $pdf, $s_y, $x = '', $y = '')
    {
        $this->Scale($pdf, 100, $s_y, $x, $y);
    }

    public function ScaleXY(\PhpSigep\Pdf\ImprovedFPDF $pdf, $s, $x = '', $y = '')
    {
        $this->Scale($pdf, $s, $s, $x, $y);
    }

    public function Scale(\PhpSigep\Pdf\ImprovedFPDF $pdf, $s_x, $s_y, $x = '', $y = '')
    {
        if ($x === '') {
            $x = $pdf->x;
        }
        if ($y === '')
            $y = $pdf->y;
        if ($s_x == 0 || $s_y == 0)
            $pdf->Error('Please use values unequal to zero for Scaling');
        $y = ($pdf->h - $y) * $pdf->k;
        $x *= $pdf->k;
        //calculate elements of transformation matrix
        $s_x /= 100;
        $s_y /= 100;
        $tm[0] = $s_x;
        $tm[1] = 0;
        $tm[2] = 0;
        $tm[3] = $s_y;
        $tm[4] = $x * (1 - $s_x);
        $tm[5] = $y * (1 - $s_y);
        //scale the coordinate system
        $this->Transform($pdf, $tm);
    }

    public function MirrorH(\PhpSigep\Pdf\ImprovedFPDF $pdf, $x = '')
    {
        $this->Scale($pdf, -100, 100, $x);
    }

    public function MirrorV(\PhpSigep\Pdf\ImprovedFPDF $pdf, $y = '')
    {
        $this->Scale($pdf, 100, -100, '', $y);
    }

    public function MirrorP(\PhpSigep\Pdf\ImprovedFPDF $pdf, $x = '', $y = '')
    {
        $this->Scale($pdf, -100, -100, $x, $y);
    }

    public function MirrorL(\PhpSigep\Pdf\ImprovedFPDF $pdf, $angle = 0, $x = '', $y = '')
    {
        $this->Scale($pdf, -100, 100, $x, $y);
        $this->Rotate($pdf, -2 * ($angle - 90), $x, $y);
    }

    public function TranslateX(\PhpSigep\Pdf\ImprovedFPDF $pdf, $t_x)
    {
        $this->Translate($pdf, $t_x, 0);
    }

    public function TranslateY(\PhpSigep\Pdf\ImprovedFPDF $pdf, $t_y)
    {
        $this->Translate($pdf, 0, $t_y);
    }

    public function Translate(\PhpSigep\Pdf\ImprovedFPDF $pdf, $t_x, $t_y)
    {
        //calculate elements of transformation matrix
        $tm[0] = 1;
        $tm[1] = 0;
        $tm[2] = 0;
        $tm[3] = 1;
        $tm[4] = $t_x * $pdf->k;
        $tm[5] = -$t_y * $pdf->k;
        //translate the coordinate system
        $this->Transform($pdf, $tm);
    }

    public function Rotate(\PhpSigep\Pdf\ImprovedFPDF $pdf, $angle, $x = '', $y = '')
    {
        if ($x === '')
            $x = $pdf->x;
        if ($y === '')
            $y = $pdf->y;
        $y = ($pdf->h - $y) * $pdf->k;
        $x *= $pdf->k;
        //calculate elements of transformation matrix
        $tm[0] = cos(deg2rad($angle));
        $tm[1] = sin(deg2rad($angle));
        $tm[2] = -$tm[1];
        $tm[3] = $tm[0];
        $tm[4] = $x + $tm[1] * $y - $tm[0] * $x;
        $tm[5] = $y - $tm[0] * $y - $tm[1] * $x;
        //rotate the coordinate system around ($x,$y)
        $this->Transform($pdf, $tm);
    }

    public function SkewX(\PhpSigep\Pdf\ImprovedFPDF $pdf, $angle_x, $x = '', $y = '')
    {
        $this->Skew($pdf, $angle_x, 0, $x, $y);
    }

    public function SkewY(\PhpSigep\Pdf\ImprovedFPDF $pdf, $angle_y, $x = '', $y = '')
    {
        $this->Skew($pdf, 0, $angle_y, $x, $y);
    }

    public function Skew(\PhpSigep\Pdf\ImprovedFPDF $pdf, $angle_x, $angle_y, $x = '', $y = '')
    {
        if ($x === '')
            $x = $pdf->x;
        if ($y === '')
            $y = $pdf->y;
        if ($angle_x <= -90 || $angle_x >= 90 || $angle_y <= -90 || $angle_y >= 90)
            $pdf->Error('Please use values between -90? and 90? for skewing');
        $x *= $pdf->k;
        $y = ($pdf->h - $y) * $pdf->k;
        //calculate elements of transformation matrix
        $tm[0] = 1;
        $tm[1] = tan(deg2rad($angle_y));
        $tm[2] = tan(deg2rad($angle_x));
        $tm[3] = 1;
        $tm[4] = -$tm[2] * $y;
        $tm[5] = -$tm[1] * $x;
        //skew the coordinate system
        $this->Transform($pdf, $tm);
    }

    public function StopTransform(\PhpSigep\Pdf\ImprovedFPDF $pdf)
    {
        //restore previous graphic state
        $pdf->_out('Q');
    }
}

?>

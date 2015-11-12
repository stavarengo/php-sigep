<?php
namespace PhpSigep\Pdf;

class ImprovedFPDF extends \PhpSigepFPDF
{

    /**
     * @var int
     */
    private $lineHeightPadding = 33;
    /**
     * @var array
     */
    private $_savedState = array();

    function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);
        $this->lineHeightPadding = 33 / $this->k;
    }

    public function _($str)
    {
        if (extension_loaded('iconv')) {
            return iconv('UTF-8', 'ISO-8859-1', $str);
        } else {
            return utf8_decode($str);
        }
    }

    /**
     * @param int $padding
     *        Deve ser informado usando a unidade de medida passada no construtor.
     */
    public function setLineHeightPadding($padding)
    {
        $this->lineHeightPadding = $padding;
    }

    /**
     * @param int $padding
     *        Deve ser informado usando a unidade de medida passada no construtor.
     *
     * @return int
     */
    public function getLineHeigth($padding = null)
    {
        if ($padding === null) {
            $padding = $this->lineHeightPadding;
        }
        $lineHeigth = $this->FontSizePt + $this->FontSizePt * ($padding * $this->k) / 100;

        return $lineHeigth / $this->k;
    }

    public function CellXp($w, $txt, $align = '', $ln = 0, $h = null, $border = 0, $fill = false, $link = '')
    {
        if ($h === null) {
            $h = $this->getLineHeigth();
        }
        $txt = $this->_($txt);
        $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }

    public function MultiCellXp($w, $txt, $h = null, $border = 0, $align = 'L', $fill = false)
    {
        if ($h === null) {
            $h = $this->getLineHeigth();
        }
        $txt = $this->_($txt);
        $this->MultiCell($w, $h, $txt, $border, $align, $fill);
    }

    public function GetStringWidthXd($s)
    {
        return $this->GetStringWidth($this->_($s));
    }

    public function saveState()
    {
        $this->_savedState = array(
            'lineHeightPadding' => $this->lineHeightPadding,
            'LineWidth'         => $this->LineWidth,
            'DrawColor'         => $this->DrawColor,
            'TextColor'         => $this->DrawColor,
            'FontFamily'        => $this->FontFamily,
            'FontStyle'         => $this->FontStyle,
            'FontSize'          => $this->FontSize,
            'x'                 => $this->x,
            'y'                 => $this->y,
        );
    }

    public function restoreLastState()
    {
        $this->_savedState = (array)$this->_savedState;
        foreach ($this->_savedState as $k => $v) {
            if ($this->$k != $v) {
                if ($k == 'FontFamily') {
                    $this->SetFont($v);
                } else {
                    if ($k == 'FontStyle') {
                        $this->SetFont('', $v);
                    } else {
                        $methodDefinition = array($this, 'set' . ucfirst($k));
                        if (is_callable($methodDefinition, true, $callable_name)) {
                            call_user_func($methodDefinition, $v);
                        } else {
                            $this->$k = $v;
                        }
                    }
                }
            }
        }
    }
}
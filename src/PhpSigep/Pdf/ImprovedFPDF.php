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

        stream_wrapper_register("var", 'PhpSigep\Pdf\VariableStream') or die("Failed to register protocol");
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

    function memImage($data, $x=null, $y=null, $w=0, $h=0, $link='')
    {
        //Display the image contained in $data
        $v = 'img'.md5($data);
        $GLOBALS[$v] = $data;
        $a = getimagesize('var://'.$v);
        if(!$a)
            $this->Error('Invalid image data');
        $type = substr(strstr($a['mime'],'/'),1);
        $this->Image('var://'.$v, $x, $y, $w, $h, $type, $link);
        unset($GLOBALS[$v]);
    }

    function gdImage($im, $x=null, $y=null, $w=0, $h=0, $link='')
    {
        //Display the GD image associated to $im
        ob_start();
        imagepng($im);
        $data = ob_get_clean();
        $this->MemImage($data, $x, $y, $w, $h, $link);
    }
}

//Stream handler to read from global variables
class VariableStream {
    var $position;
    var $varname;

    function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        $this->varname = $url["host"];
        $this->position = 0;

        return true;
    }

    function stream_read($count)
    {
        $ret = substr($GLOBALS[$this->varname], $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_write($data)
    {
        $left = substr($GLOBALS[$this->varname], 0, $this->position);
        $right = substr($GLOBALS[$this->varname], $this->position + strlen($data));
        $GLOBALS[$this->varname] = $left . $data . $right;
        $this->position += strlen($data);
        return strlen($data);
    }

    function stream_tell()
    {
        return $this->position;
    }

    function stream_eof()
    {
        return $this->position >= strlen($GLOBALS[$this->varname]);
    }

    function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($GLOBALS[$this->varname]) && $offset >= 0) {
                    $this->position = $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->position += $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_END:
                if (strlen($GLOBALS[$this->varname]) + $offset >= 0) {
                    $this->position = strlen($GLOBALS[$this->varname]) + $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            default:
                return false;
        }
    }

    function stream_metadata($path, $option, $var)
    {
        if($option == STREAM_META_TOUCH) {
            $url = parse_url($path);
            $varname = $url["host"];
            if(!isset($GLOBALS[$varname])) {
                $GLOBALS[$varname] = '';
            }
            return true;
        }
        return false;
    }
}
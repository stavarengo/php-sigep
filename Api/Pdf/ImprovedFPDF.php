<?php
namespace Sigep\Pdf;

use Sigep\Loader;

Loader::loadVendorFile('fpdf17/fpdf.php');

class ImprovedFPDF extends \FPDF
{

	/**
	 * @var array
	 */
	private $_savedState = array();

	public function saveState()
	{
		$this->_savedState = array(
			'LineWidth'  => $this->LineWidth,
			'DrawColor'  => $this->DrawColor,
			'FontFamily' => $this->FontFamily,
			'FontStyle'  => $this->FontStyle,
			'FontSize'   => $this->FontSize,
			'x'          => $this->x,
			'y'          => $this->y,
		);
	}

	public function restoreLastState()
	{
		$this->_savedState = (array)$this->_savedState;
		foreach ($this->_savedState as $k => $v) {
			if ($this->$k != $v) {
				if ($k == 'FontFamily') {
					$this->SetFont($v);
				} else if ($k == 'FontStyle') {
					$this->SetFont('', $v);
				} else {
					call_user_func(array($this, 'set' . $k), $v);
				}
			}
		}
	}
}
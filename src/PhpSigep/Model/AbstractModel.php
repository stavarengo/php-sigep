<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 */
abstract class AbstractModel
{

	public function __construct(array $initialValues = array())
	{
		foreach ($initialValues as $attr => $value) {
			call_user_func(array($this, 'set' . ucfirst($attr)), $value);
		}
	}

    public function toArray()
    {
        return $this->_toArray($this);
    }
    
    private function _toArray($value) 
    {
        $result = array();
        $vars = get_object_vars($value);
        foreach ($vars as $var => $val) {
            if (is_object($val)) {
                $val = $this->_toArray($val);
            } else if (is_array($val)) {
                $novoVal = array();
                foreach ($val as $k => $v) {
                    $novoVal[$k] = $this->_toArray($v);
                }
                $val = $novoVal;
            }
            $result[$var] = $val; 
        }
        
        return $result;
    }
}
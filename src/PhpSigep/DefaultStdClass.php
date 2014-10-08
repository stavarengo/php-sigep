<?php
namespace PhpSigep;

/**
 * @author: Stavarengo
 */
abstract class DefaultStdClass
{

    protected $_failIfAtributeNotExiste = true;

    public function __construct(array $initialValues = array())
    {
        $this->setFromArray($initialValues);
    }

    /**
     * @param string $attributeName
     * @param $value
     * @throws InvalidArgument
     */
    public function set($attributeName, $value)
    {
        $method = 'set' . ucfirst($attributeName);
        if (is_callable(array($this, $method))) {
            $this->$method($value);

            return;
        }

        if ($this->_failIfAtributeNotExiste) {
            throw new InvalidArgument('Não existe um método para definir o valor do atributo "'
                . get_class($this) . '::' . $attributeName . '"');
        }
        
        $this->$attributeName = $value;
    }

    /**
     * @param $attributeName
     * @throws InvalidArgument
     * @return mixed
     */
    public function get($attributeName)
    {
        $method = 'get' . ucfirst($attributeName);
        if (is_callable(array($this, $method))) {
            return $this->$method();
        }
        $method = 'is' . ucfirst($attributeName);
        if (is_callable(array($this, $method))) {
            return $this->$method();
        }

        if ($this->_failIfAtributeNotExiste) {
            throw new InvalidArgument('Não existe um método para retornar o valor do atributo: "'
                . $attributeName . '"');
        }
        
        return null;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->_toArray($this);
    }

    /**
     * @param $value
     * @return array
     */
    private function _toArray($value)
    {
        $result = array();
        $vars   = get_object_vars($value);
        foreach ($vars as $var => $val) {
            try {
                if (is_object($value) && $value instanceof DefaultStdClass) {
                    $val = $value->get($var);
                }
            } catch (InvalidArgument $e) {
                // Ignora essa propiedade se ela não tiver um método get definido.
                continue;
            }

            if (is_object($val)) {
                $val = $value->_toArray($val);
            } else {
                if (is_array($val)) {
                    $novoVal = array();
                    foreach ($val as $k => $v) {
                        $novoVal[$k] = $value->_toArray($v);
                    }
                    $val = $novoVal;
                }
            }
            $result[$var] = $val;
        }

        return $result;
    }

    /**
     * @param array $values
     */
    public function setFromArray(array $values)
    {
        foreach ($values as $attr => $value) {
            $this->set($attr, $value);
        }
    }

}
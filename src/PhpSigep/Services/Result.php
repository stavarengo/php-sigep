<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */
 
namespace PhpSigep\Services;

use PhpSigep\DefaultStdClass;
use PhpSigep\Model\AbstractModel;
use PhpSigep\Services\Real\SoapClientFactory;

class Result extends DefaultStdClass
{
    /**
     * @var bool
     */
    protected $isSoapFault = false;
    
    /**
     * @var int
     */
    protected $errorCode = null;

    /**
     * @var string
     */
    protected $errorMsg = null;

    /**
     * @var AbstractModel|AbstractModel[]
     */
    protected $result;

    /**
     * @var \SoapFault
     */
    protected $soapFault;
    
    public function hasError()
    {
        return ($this->errorCode !== null || $this->errorMsg !== null || $this->isSoapFault);
    }

    /**
     * @param boolean $isSoapFault
     * @return $this;
     */
    public function setIsSoapFault($isSoapFault)
    {
        $this->isSoapFault = $isSoapFault;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsSoapFault()
    {
        return $this->isSoapFault;
    }
    
    /**
     * @param int $errorCode
     * @return $this;
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorMsg
     * @return $this;
     */
    public function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * @param \SoapFault $soapFault
     * @return $this;
     */
    public function setSoapFault(\SoapFault $soapFault)
    {
        $this->soapFault = $soapFault;
        $this->setIsSoapFault(true);

        return $this;
    }

    /**
     * @return \SoapFault
     */
    public function getSoapFault()
    {
        return $this->soapFault;
    }

    /**
     * @param AbstractModel|AbstractModel[]|\SoapFault $result
     * @throws InvalidArgument
     * @return $this;
     */
    public function setResult($result)
    {
        if ($result instanceof \SoapFault) {
            $this->setIsSoapFault(true);
            $this->setErrorCode($result->getCode());
            $this->setErrorMsg(SoapClientFactory::convertEncoding($result->getMessage()));
            $this->result = null;
            $this->setSoapFault($result);
        } else {
            $piece = $result;
            if (is_array($result)) {
                if (count($result)) {
                    $piece = reset($result);
                } else {
                    $piece = null;
                }
            }
            
            if ($piece !== null && !($piece instanceof AbstractModel) && !($piece instanceof \SoapFault)) {
                throw new InvalidArgument('O resultado deve ser uma instância de PhpSigep\Model\AbstractModel ou um ' .
                    'array de PhpSigep\Model\AbstractModel ou uma instância de \SoapFault.');
            }
            
            $this->result = $result;
        }


        return $this;
    }

    /**
     * @return \PhpSigep\Model\AbstractModel|\PhpSigep\Model\AbstractModel[]
     */
    public function getResult()
    {
        return $this->result;
    }
    
    
} 
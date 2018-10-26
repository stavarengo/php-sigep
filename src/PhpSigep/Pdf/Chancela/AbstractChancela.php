<?php
namespace PhpSigep\Pdf\Chancela;

abstract class AbstractChancela
{
    /**
     * @var int
     */
    protected $x;
    /**
     * @var int
     */
    protected $y;
    /**
     * @var string
     */
    protected $nomeRemetente;
    /**
     * @var \PhpSigep\Model\AccessData
     */
    protected $accessData;
    
    /**
     * @param int $x
     * @param int $y
     * @param string $nomeRemetente
     * @param \PhpSigep\Model\AccessData $accessData
     */
    public function __construct($x, $y, $nomeRemetente, \PhpSigep\Model\AccessData $accessData)
    {
        $this->setLeftPos($x);
        $this->setTopPos($y);
        $this->setNomeRemetente($nomeRemetente);
        $this->setAccessData($accessData);
    }
    
    public function setLeftPos($x)
    {
        if (is_numeric($x) === false) {
            throw new \InvalidArgumentException('Parâmetro $x precisa ser do tipo numérico');
        }
        $this->x = $x;
    }
    
    public function setTopPos($y)
    {
        if (is_numeric($y) === false) {
            throw new \InvalidArgumentException('Parâmetro $x precisa ser do tipo numérico');
        }
        $this->y = $y;
    }
    
    public function setNomeRemetente($nomeRemetente)
    {
        if (is_string($nomeRemetente) === false) {
            throw new \InvalidArgumentException('Parâmetro $nomeRemetente precisa ser do tipo string');
        }
        $this->nomeRemetente = $nomeRemetente;
    }
    
    public function setAccessData(\PhpSigep\Model\AccessData $accessData)
    {
        $this->accessData = $accessData;
    }
    
    public abstract function draw(\PhpSigep\Pdf\ImprovedFPDF $pdf);
}
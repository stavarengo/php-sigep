<?php
namespace PhpSigep\Pdf;

use PhpSigep\Bootstrap;


class AvisoRecebimento{

    /** @var \PhpSigep\Pdf\ImprovedFPDF */
    public $pdf;

    public function __construct(){

        $this->init();
    }

    public function render($dest='', $filename = '') {
        
        $cacheKey = '123';
        
        if($dest == 'S'){
            //TODO implementar
            exit();
//            return $this->_render($dest, $filename);
        }else{
            $this->_render($dest, $filename);
            Bootstrap::getConfig()->getCacheInstance()->setItem($cacheKey, $this->pdf->buffer);
        }
        
    }
    
    
     private function addPage() {
        $pdf = $this->pdf;
        $pdf->AddPage();

        if (Bootstrap::getConfig()->getSimular()) {
            $this->pdf->SetFont('Arial', 'B', 50);
            $this->pdf->SetTextColor(200, 200, 200);
            $hInner     = $pdf->h - $pdf->tMargin - $pdf->bMargin;
            $lineHeigth = $pdf->getLineHeigth();
            $pdf->SetY($hInner / 2 - $lineHeigth / 2);
            $this->pdf->MultiCellXp(
                $this->pdf->w - $this->pdf->lMargin - $this->pdf->rMargin,
                "Simulação Documento sem valor",
                null,
                0,
                'C'
            );

            $this->pdf->SetTextColor(0, 0, 0);
        }

        $pdf->x = $pdf->lMargin;
        $pdf->y = $pdf->tMargin;
    }

    private function _render($dest='', $filename=''){

        $this->addPage();

        //configs
        $wInner = $this->pdf->w - $this->pdf->lMargin - $this->pdf->rMargin;
        
        
        //TODO programar
        $this->writeBorderPaste($wInner);
//        $this->writeHeader($wInner);
        
        
        
        
        if($dest == 'S'){
            return $this->pdf->Output($filename,$dest);
        }
        else{
            $this->pdf->Output($filename,$dest);
        }
    }
    
    private function writeBorderPaste($wInner){
        
        $yHeaderRect = $this->pdf->y + 10 / $this->pdf->k;
        $this->pdf->SetY($yHeaderRect);
        
        $this->rectDash(2,1);
        $this->pdf->Rect($this->pdf->lMargin, $this->pdf->y, 4, 30);
        $this->rectDash();
        
//        function Rect($x, $y, $w, $h, $style='')
        
    }
    private function writeHeader($wInner){
        
        $yHeaderRect = $this->pdf->y + 10 / $this->pdf->k;
        $this->pdf->SetY($yHeaderRect);
        $this->pdf->SetFont('', 'B', 13);
        $y = $this->pdf->y;
        
        //logo correios
        $logoCorreios = realpath(dirname(__FILE__) . '/logo-correios.jpg');
        $wLogo = ($wInner * 30) / 100;
        $wImage = ($wInner * 15) / 100;
        $this->pdf->Image($logoCorreios, $this->pdf->x, $y+1, $wImage);
        
        //Label sigep
        $this->pdf->SetXY($wLogo, $y+2);
        $this->pdf->SetFont('', 'B', 17);
        $wLabelSigep = ($wInner * 11) / 100;
        $this->pdf->CellXp($wLabelSigep, 'SIGEP', 'L', 0, null, 0);
        
        //Label aviso de recebimento
        $this->pdf->SetXY($this->pdf->x, $y+1);
        $wLabelAR = ($wInner * 18) / 100;
        $this->pdf->SetFont('', 'B', 8);
        $this->pdf->MultiCellXp($wLabelAR, 'AVISO DE RECEBIMENTO', 4, 0, 'L');
        $lastYBorder = $this->pdf->y;
        
        //Label contrato
        $lastX = $wLabelAR + $wLabelSigep + $wLogo;
        $this->pdf->SetXY($lastX, $y+2);
        $this->pdf->SetFont('', '', 9);
        $wLabelContrato = ($wInner * 11) / 100;
        $this->pdf->CellXp($wLabelContrato, 'CONTRATO', 'L', 0, null, 0);
        
        //Label cd contrato
        $wLabelCdContrato = $wInner - $wLabelContrato - $wLabelAR - $wLabelSigep - $wLogo;
        $this->pdf->CellXp($wLabelCdContrato, '9912208555', 'L', 0, null, 0);
        
        $this->pdf->Rect($this->pdf->lMargin, $yHeaderRect, $wInner, $lastYBorder - $yHeaderRect, 'S');
        
    }

    private function init() {
        $this->pdf = new \PhpSigep\Pdf\ImprovedFPDF('P', 'mm');
        $this->pdf->SetFont('Arial', '', 10);
    }

    private function rectDash($black=null, $white=null){
        if($black!==null){
            $s=\sprintf('[%.3F %.3F] 0 d',$black*$this->pdf->k,$white*$this->pdf->k);
            
        }else{
            $s='[] 0 d';
        }
        
        $this->pdf->_out($s);
    }







}

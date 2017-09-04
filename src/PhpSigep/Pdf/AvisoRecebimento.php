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
    
    private function init() {
        $this->pdf = new \PhpSigep\Pdf\ImprovedFPDF('P', 'mm');
        $this->pdf->SetFont('Arial', '', 10);
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
        $widthBorderPaste = 5;
        $xContent = $this->pdf->rMargin + $widthBorderPaste + 1;
        $wInner = $this->pdf->w - $this->pdf->lMargin - $xContent;
        $yBorderPaste = $this->pdf->y + 10 / $this->pdf->k;
        $wCol1 = ($wInner * 46)/100;
        $wCol2 = (($wInner * 31)/100);
        $wCol3 = $wInner-$wCol1-$wCol2;
        $wColFooter = $wCol1+$wCol2;
        
        $this->writeHeader($xContent, $wInner);
        
        $yContent = $this->pdf->y;
        
        $this->writeContentDescription($xContent, $yContent, $wInner, $wCol1);
        $this->writeDeclarationOfContent($xContent, $wCol1);
        
        $yEndDeclarationOfContent = $this->pdf->y;
        $this->writeDeliveryAttempts($xContent+$wCol1, $yContent, $wInner, $wCol2, $yEndDeclarationOfContent);
        
        $this->writeSignature($xContent, $yEndDeclarationOfContent, $wColFooter);
        
        $yEnd = $this->pdf->y;
        
        $this->writeStamp($xContent+$wCol1+$wCol2, $yContent, $wCol3, $yEnd);
        
        $this->writeBorderPaste($yBorderPaste, $widthBorderPaste, $yEnd);
        
        
        if($dest == 'S'){
            return $this->pdf->Output($filename,$dest);
        }
        else{
            $this->pdf->Output($filename,$dest);
        }
    }
   
    /**
     * 
     * @param type $x
     * @param type $wInner
     */
    private function writeHeader($x, $wInner){
        
        $yHeaderRect = $this->pdf->y + 10 / $this->pdf->k;
        $this->pdf->SetY($yHeaderRect);
        $this->pdf->SetFont('', 'B', 13);
        $y = $this->pdf->y;
        
        //logo correios
        $logoCorreios = realpath(dirname(__FILE__) . '/logo-correios.jpg');
        $wLogo = ($wInner * 30) / 100;
        $wImage = ($wInner * 15) / 100;
        $this->pdf->Image($logoCorreios, $x, $y+1, $wImage);
        
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
        
        $this->pdf->Rect($x, $yHeaderRect, $wInner, $lastYBorder - $yHeaderRect, 'S');
        
        $this->pdf->SetY($lastYBorder);
        
    }
    
    private function writeContentDescription($x, $y, $wInner, $wContentInner){
        
        //destinatário
        $this->pdf->SetXY($x, $y+2);
        $this->pdf->SetFont('', 'B', 8);
        $this->pdf->CellXp($wContentInner, 'DESTINATÁRIO', 'L', 2, null, 0);
        $this->pdf->SetFont('', '', 7);
        //TODO pode quebrar linha usar MultiCellXp
        $this->pdf->CellXp($wContentInner, 'Fulando 2', 'L', 2, null, 0);
        $this->ln(1);
        $this->pdf->CellXp($wContentInner, 'Rua João Negão, 1251', 'L', 2, null, 0);
        $this->pdf->CellXp($wContentInner, 'Casa Branca Bela Vista', 'L', 2, null, 0);
        $this->pdf->CellXp($wContentInner, '80002900 Curitiba-PR', 'L', 2, null, 0);
        
        
        //rastreamento
        $this->pdf->SetFont('', 'B', 10);
        $this->ln(2);
        $wCode = ($wInner * 24)/100;
        $hCode = 10;

        $this->pdf->CellXp($wContentInner, 'PD739580968BR', 'C', 2, null, 0);

        $code128 = new \PhpSigep\Pdf\Script\BarCode128();
        $code128->draw($this->pdf, (($wContentInner-$wCode)/2)+$x, $this->pdf->y, 'PD739580968BR', $wCode, $hCode);
        $this->pdf->SetXY($x, $this->pdf->y+$hCode);
        $this->ln(2);
        
        //remetente
        $this->pdf->SetFont('', 'B', 8);
        $this->pdf->CellXp(19, 'REMETENTE: ', 'L', 0, null, 0);
        $this->pdf->SetFont('', '', 7);
        $this->pdf->CellXp($wContentInner, 'nome da empresa', 'L', 2, null, 0);
        $this->pdf->SetX($x);
        $this->pdf->SetFont('', 'B', 8);
        $this->ln(1);
        $this->pdf->CellXp($wContentInner, 'ENDEREÇO PARA DEVOLUÇÃO DO OBJETO:', 'L', 2, null, 0);
        $this->ln(1);
        $this->pdf->SetFont('', '', 7);
        $this->pdf->CellXp($wContentInner, 'SBN Quadra 1 Bloco A, 10', 'L', 2, null, 0);
        $this->pdf->CellXp($wContentInner, '14º Andar Asa Norte', 'L', 2, null, 0);
        $this->pdf->CellXp($wContentInner, '14º 70002900 Brasília-DF', 'L', 2, null, 0);
        $this->ln(2);
        
        $this->pdf->Rect($x, $y, $wContentInner, $this->pdf->y - $y, 'S');
        
    }
    
    private function writeDeclarationOfContent($x, $wContentInner){
       
        $yHeaderRect = $this->pdf->y;

        $this->pdf->SetXY($x, $yHeaderRect);
        $this->pdf->SetFont('', '', 5);
        $this->pdf->CellXp($wContentInner, 'DECLARAÇÃO DE CONTEÚDO', 'L', 2, null, 0);
        $this->ln(5);
        
        $this->pdf->Rect($x, $yHeaderRect, $wContentInner, $this->pdf->y - $yHeaderRect, 'S');
    }
    
    /**
     * 
     * @param type $x
     * @param type $y
     * @param type $wInner
     * @param type $wContentInner
     * @param type $yEnd
     */
    private function writeDeliveryAttempts($x, $y, $wInner ,$wContentInner, $yEnd){
        
        $this->pdf->SetXY($x, $y+2);
        $this->pdf->SetFont('', 'B', 8);
        $this->pdf->CellXp($x, 'TENTATIVAS DE ENTREGAS:', 'L', 2, null, 0);
        $this->ln(5);
        
        $this->pdf->SetFont('', 'B', 8);
        $this->pdf->CellXp($wContentInner, '1º ____/____/____       ____:____h', 'L', 2, null, 0);
        $this->ln(2);
        $this->pdf->CellXp($wContentInner, '2º ____/____/____       ____:____h', 'L', 2, null, 0);
        $this->ln(2);
        $this->pdf->CellXp($wContentInner, '3º ____/____/____       ____:____h', 'L', 2, null, 0);
        $this->ln(15);
        
        $this->pdf->SetFont('', '', 8);
        $this->pdf->CellXp($x, 'MOTIVO DE DEVOLUÇÃO:', 'L', 2, null, 0);
        
        $wItem = ($wInner*12)/100;
        
        $this->writeItemDevolution('1', 'Mudou-se', $x, $wItem, 0);
        $this->writeItemDevolution('5', 'Recusado', $x, $wItem, 2);
        
        $this->writeItemDevolution('2', 'Endereço Insuficiente', $x, $wItem, 0);
        $this->writeItemDevolution('6', 'Não Procurado', $x, $wItem, 2);
        
        $this->writeItemDevolution('3', 'Não Existe o Número', $x, $wItem, 0);
        $this->writeItemDevolution('7', 'Ausente', $x, $wItem, 2);
        
        $this->writeItemDevolution('4', 'Desconhecido', $x, $wItem, 0);
        $this->writeItemDevolution('8', 'Falecido', $x, $wItem, 2);
        $this->ln(2);
        $this->writeItemDevolution('9', 'Outros________________________________', $x, $wItem, 0);
        $this->ln(5);
        
        $this->pdf->Rect($x, $y, $wContentInner, $yEnd-$y, 'S');
    }
    
    /**
     * @param double $x
     * @param double $y
     * @param double $wContentInner
     */
    private function writeSignature($x, $y, $wContentInner){
        
        $col1 = ($wContentInner*70)/100;
        $col2 = $wContentInner-$col1;
        $hRect = 10;

        //Linha 1
        $this->pdf->SetXY($x, $y);
        $this->pdf->SetFont('', '', 5);
        $this->pdf->CellXp($col1, 'ASSINATURA DO RECEBEDOR', 'L', 0, null, 0);
        $this->pdf->Rect($x, $y, $col1, $hRect, 'S');
        
        $this->pdf->CellXp($col2, 'DATA DE ENTREGA', 'L', 2, null, 0);
        $this->pdf->Rect($x+$col1, $y, $col2, $hRect, 'S');
        
        //Linha 2
        $yLine2 = $y + $hRect;
        $this->pdf->SetXY($x, $yLine2);
        $this->pdf->CellXp($col1, 'NOME LEGÍVEL DO RECEBEDOR', 'L', 0, null, 0);
        $this->pdf->Rect($x, $yLine2, $col1, $hRect, 'S');
        
        $this->pdf->CellXp($col2, 'Nº DOC. DE IDENTIDADE', 'L', 2, null, 0);
        $this->pdf->Rect($x+$col1, $yLine2, $col2, $hRect, 'S');
        
        //final do quadro
        $yEnd = $yLine2 + $hRect;
        $this->pdf->SetXY($x, $yEnd);
        
    }
    
    /**
     * @param double $x
     * @param double $y
     * @param double $wContentInner
     */
    private function writeStamp($x, $y, $wContentInner, $yEnd){
        
        $hStamp = (($yEnd-$y)*60)/100;
        
        //bloco 1
        $this->pdf->SetXY($x, $y);
        $this->ln(1);
        $this->pdf->SetFont('', '', 5);
        $this->pdf->CellXp($wContentInner, 'CARIMBO', 'C', 2, null, 0);
        $this->pdf->CellXp($wContentInner, 'UNIDADE DE ENTREGA', 'C', 2, null, 0);
        
        $this->pdf->Rect($x, $y, $wContentInner, $hStamp, 'S');

        //bloco 2
        $yLast = $y+$hStamp;
        $this->pdf->SetXY($x, $yLast);
        $this->ln(1);
        $this->pdf->CellXp($wContentInner, 'RUBRICA E MATRÍCULA DO CARTEIRO', 'C', 2, null, 0);
        $this->pdf->Rect($x, $yLast, $wContentInner, $yEnd-$yLast, 'S');
    }
    
    /**
     * 
     * @param type $yBorderPaste
     * @param type $width
     * @param type $yEnd
     */
    private function writeBorderPaste($yBorderPaste, $width, $yEnd){
        
        $this->pdf->SetFont('', '', 9);
        
        $this->pdf->SetY($yBorderPaste+35);
        $this->pdf->Rotate(90);
        $this->pdf->CellXp($this->pdf->lMargin, 'Cole aqui', 'L', 0, null, 0);
        $this->pdf->Rotate(0);
        
        $this->pdf->SetY($yBorderPaste+75);
        $this->pdf->Rotate(90);
        $this->pdf->CellXp($this->pdf->lMargin, 'Cole aqui', 'L', 0, null, 0);
        $this->pdf->Rotate(0);
        
        $this->pdf->SetY($yBorderPaste);
        
        $this->pdf->SetDash(2,1);
        $this->pdf->Rect($this->pdf->lMargin, $yBorderPaste, $width, $yEnd - $yBorderPaste);
        $this->pdf->SetDash();
        
    }
    
    /**
     * @param string $number
     * @param string $text
     * @param double $x
     * @param double $w
     * @param integer $ln
     */
    private function writeItemDevolution($number, $text, $x, $w, $ln){

        $this->pdf->SetFont('', '', 6);
        
        if($ln === 0){
            $this->pdf->SetX($x+1);
        }else{
            $this->pdf->SetX($this->pdf->x+1);
        }
        
        $this->pdf->CellXp(5, $number, 'C', 0, null, 1);
        $this->pdf->CellXp($w, $text, 'L', $ln, null, 0);
        
        if($ln > 0){
            $this->pdf->SetY($this->pdf->y+1);
        }
    }

    private function ln($h=null){
        if($h===null){
            $this->pdf->y += $this->pdf->lasth;
        }else{
            $this->pdf->y += $h;
        }
    }

}

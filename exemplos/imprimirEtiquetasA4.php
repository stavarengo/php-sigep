<?php
require_once __DIR__ . '/bootstrap-exemplos.php';

$params = include __DIR__ . '/helper-criar-pre-lista-a4.php';

// Logo da empresa remetente
$logoFile = __DIR__ . '/logo-etiqueta-2016.png';

//Parametro opcional indica qual layout utilizar para a chancela. Ex.: CartaoDePostagem::TYPE_CHANCELA_CARTA, CartaoDePostagem::TYPE_CHANCELA_CARTA_2016
$layoutChancela = array(); //array(\PhpSigep\Pdf\CartaoDePostagem2016::TYPE_CHANCELA_SEDEX_2016);

$pdf = new \PhpSigep\Pdf\CartaoDePostagem2016($params, time(), $logoFile, $layoutChancela);

$fileName = tempnam(sys_get_temp_dir(), 'phpsigep') . '.pdf';
$pdf->render('F',$fileName);

unset($pdf);
$pdf = new \PhpSigep\PDF\ImprovedFPDF('P', 'mm', 'Letter' );
$pageCount = $pdf->setSourceFile($fileName);

$pdf->AddPage();
$pdf->SetFillColor(0,0,0);
$pdf->SetFont('Arial','B',16);

for($i=1;$i<=$pageCount;$i++) {
    $tplIdx = $pdf->importPage($i, '/MediaBox');

    $mod = $i % 4;

    switch ($mod) {
        case 0:
            //A4: 210(x) × 297(y)
            //Letter: 216 (x) × 279 (y)
            $pdf->useTemplate($tplIdx, 110, 145, 105, 138, true);

            if ($i !== $pageCount) {
                $pdf->AddPage();
                $pdf->SetFillColor(0,0,0);
                $pdf->SetFont('Arial','B',16);
            }
            break;
        case 1:
            $pdf->useTemplate($tplIdx, 10, 10, 105, 138, true);
            break;
        case 2:
            $pdf->useTemplate($tplIdx, 110, 10, 105, 138, true);
            break;
        case 3:
            $pdf->useTemplate($tplIdx, 10, 145, 105, 138, true);
            break;
    }

}


$pdf->Output('teste.pdf', 'F');

<?php
/**
 * Build a semacode from given URL parameters
 *
 * Parameters:
 *
 * text - the text to encode (limited charset)
 * type - the wanted outoput: svg, png or table
 * size - for png type only: width of the image
 */

//error_reporting(E_ALL);

require_once('Semacode.php');
//$text = $_REQUEST['text'];
//$type = $_REQUEST['type'];
//$size = (int) $_REQUEST['size'];
//if(!$size) $size = 160;

$type = "png";
$text = $argv[1];
// strip non-supported chars (is this correct?)
$text = preg_replace('/[^\w!\"#$%&\'()*+,\-\.\/:;<=>?@[\\_\]\[{\|}~\r*]+ /','', $text);
// encode
$semacode = new Semacode();
if($type == 'png'){
    $semacode->sendPNG($text);
}elseif($type == 'svg'){
    header('Content-Type: image/svg');
    echo $semacode->asSVG($text);
}else{
    echo '<html><body>';
    echo $semacode->asHTMLTable($text);
    echo '</body></html>';
}
?>

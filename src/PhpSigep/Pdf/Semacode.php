<?php
/**
 *
 * IEC16022 bar code generation library (aka. Semacode a DataMatrix code)
 *
 * This is a port of the JavaScript code used in the Semafox Firefox extension which
 * itself is a port of a C implementation.
 *
 * Copyright notices follow:
 *
 * Original C code:
 * @copyright (C) Adrian Kennard & Cliff Hones
 * @author    Adrian Kennard, Andrews & Arnold Ltd with help from Cliff Hones on the RS coding
 * @link      http://aa.gg/free/
 *
 * JavaScript port:
 * @copyright (C) 2005 Guido Sohne
 * @author    Guido Sohne
 * @link      http://sohne.net/projects/semafox/
 *
 * PHP port:
 * @copyright (C) 2007,2014 Andreas Gohr
 * @author    Andreas Gohr <andi@splitbrain.org>
 * @link      http://www.splitbrain.org/
 *
 * @license This code is licensed under the GNU GPL Version 2.0
 */

//error_reporting(E_ALL); // recommended for testing

class ReedSolomon {
    // galois field polynomial
    var $gfpoly = 0;
    // symbol size (in bits)
    var $symsize = 0;
    // 2**symsize - 1
    var $logmod = 0;
    // log table
    var $zlog = 0;
    // antilog table
    var $alog = 0;
    // Reed Solomon polynomial
    var $rspoly = 0;
    // number of symbols to be generated using the RS polynomial
    var $rslen = 0;


    function __construct($blockSize) {
        // galois field polynomial for ECC 200
        $this->initializeGaloisField(0x12d);
        // index is 1 for ECC 200
        $this->initializeEncodingParameters($blockSize, 1);
    }

    /**
     * initializeGaloisField(polynomial) initialises the parameters for the Galois Field.
     * The symbol size is determined from the highest bit set in poly
     * This implementation will support sizes up to 30 bits (though that
     * will result in very large log/antilog tables) - bit sizes of
     * 8 or 4 are typical
     *
     * The poly is the bit pattern representing the GF characteristic
     * polynomial.  e.g. for ECC200 (8-bit symbols) the polynomial is
     * a**8 + a**5 + a**3 + a**2 + 1, which translates to 0x12d.
     */
    function initializeGaloisField($polynomial) {
        // poly is an integer containing a bit pattern representing the polynomial
        $this->gfpoly = $polynomial;

        // Find the top bit, and hence the symbol size
        $m = 0;
        $b = 0;
        for ($b = 1, $m = 0; $b <= $polynomial; $b = $b << 1) {
            $m++;
        }
        $b = $b >> 1;
        $m--;
        $this->symsize = $m;

        // Calculate the log/alog tables
        $this->logmod = (1 << $m) - 1;
        $this->zlog = array_fill(0, $this->logmod + 1, 0);
        $this->alog = array_fill(0, $this->logmod, 0);

        // initialize the log/alog tables
        $p = $v = 0;
        for ($p = 1, $v = 0; $v < $this->logmod; $v++) {
            $this->alog[$v] = $p;
            $this->zlog[$p] = $v;
            $p <<= 1;
            if ($p & $b) {
                $p ^= $polynomial;
            }
        }
    }

    /**
     * initializeEncodingParameters(chunks, polynomialIndex)
     * initialises the Reed-Solomon encoder
     * chunks is the number of symbols to be generated for appending
     * to the input data.  polynomialIndex is usually 1 - it is the index of
     * the constant in the first term (i) of the RS generator polynomial:
     * (x + 2**i)*(x + 2**(i+1))*...   [chunks terms]
     * For ECC200, index is 1.
     */
    function initializeEncodingParameters($chunks, $polynomialIndex) {
        $this->rspoly = array_fill(0, $chunks + 1, 0);
        $this->rslen = $chunks;
        $this->rspoly[0] = 1;

        for ($i = 1; $i <= $chunks; $i++) {
            $this->rspoly[$i] = 1;
            for ($k = $i - 1; $k > 0; $k--) {
                if ($this->rspoly[$k]) {
                    $this->rspoly[$k] = $this->alog[($this->zlog[$this->rspoly[$k]] + $polynomialIndex) % $this->logmod];
                }
                $this->rspoly[$k] ^= $this->rspoly[$k - 1];
            }
            $this->rspoly[0] = $this->alog[($this->zlog[$this->rspoly[0]] + $polynomialIndex) % $this->logmod];
            $polynomialIndex++;
        }
    }

    /**
     * encodeArray returns a Reed-Solomon encoding of the
     * input data (an array of bytes or integers) as a
     *  array of bytes or integers.
     */
    function encodeArray($byteArray) {
        $out = array_fill(0, $this->rslen, 0);
        for ($i = 0; $i < $this->rslen; $i++) {
            $out[$i] = 0;
        }
        for ($i = 0; $i < count($byteArray); $i++) {
            $m = $out[$this->rslen - 1] ^ $byteArray[$i];
            for ($k = $this->rslen - 1; $k > 0; $k--) {
                if ($m && $this->rspoly[$k]) {
                    $out[$k] = $out[$k - 1] ^ $this->alog[($this->zlog[$m] + $this->zlog[$this->rspoly[$k]]) % $this->logmod];
                } else {
                    $out[$k] = $out[$k - 1];
                }
            }
            if ($m && $this->rspoly[0]) {
                $out[0] = $this->alog[($this->zlog[$m] + $this->zlog[$this->rspoly[0]]) % $this->logmod];
            } else {
                $out[0] = 0;
            }
        }

        return $out;
    }
}
// END of ReedSolomon

class Semacode {
    var $E_ASCII = 0;
    var $E_C40 = 1;
    var $E_TEXT = 2;
    var $E_X12 = 3;
    var $E_EDIFACT = 4;
    var $E_BINARY = 5;
    var $E_MAX = 6;
    var $MAXBARCODE = 3116;

    var $Encodings;
    var $SwitchCost;
    var $codings = "ACTXEB";
    var $debug = false;

    function __construct() {
        $this->Encodings = array(
            $this->makeEncoding(10, 10, 10, 10, 3, 3, 5),
            $this->makeEncoding(12, 12, 12, 12, 5, 5, 7),
            $this->makeEncoding(14, 14, 14, 14, 8, 8, 10),
            $this->makeEncoding(16, 16, 16, 16, 12, 12, 12),
            $this->makeEncoding(18, 18, 18, 18, 18, 18, 14),
            $this->makeEncoding(20, 20, 20, 20, 22, 22, 18),
            $this->makeEncoding(22, 22, 22, 22, 30, 30, 20),
            $this->makeEncoding(24, 24, 24, 24, 36, 36, 24),
            $this->makeEncoding(26, 26, 26, 26, 44, 44, 28),
            $this->makeEncoding(32, 32, 16, 16, 62, 62, 36),
            $this->makeEncoding(36, 36, 18, 18, 86, 86, 42),
            $this->makeEncoding(40, 40, 20, 20, 114, 114, 48),
            $this->makeEncoding(44, 44, 22, 22, 144, 144, 56),
            $this->makeEncoding(48, 48, 24, 24, 174, 174, 68),
            $this->makeEncoding(52, 52, 26, 26, 204, 102, 42),
            $this->makeEncoding(64, 64, 16, 16, 280, 140, 56),
            $this->makeEncoding(72, 72, 18, 18, 368, 92, 36),
            $this->makeEncoding(80, 80, 20, 20, 456, 114, 48),
            $this->makeEncoding(88, 88, 22, 22, 576, 144, 56),
            $this->makeEncoding(96, 96, 24, 24, 696, 174, 68),
            $this->makeEncoding(104, 104, 26, 26, 816, 136, 56),
            $this->makeEncoding(120, 120, 20, 20, 1050, 175, 68),
            $this->makeEncoding(132, 132, 22, 22, 1304, 163, 62),
            $this->makeEncoding(144, 144, 24, 24, 1558, 156, 62),
            $this->makeEncoding(0, 0, 0, 0, 0, 0, 0, 0, 0) // sentinel
        );

        $this->switchCost = array(
            array(0, 1, 1, 1, 1, 2), // A E_ASCII
            array(1, 0, 2, 2, 2, 3), // C E_C40
            array(1, 2, 0, 2, 2, 3), // T E_TEXT
            array(1, 2, 2, 0, 2, 3), // X E_X12
            array(1, 2, 2, 2, 0, 3), // E E_EDIFACT
            array(0, 1, 1, 1, 1, 0), // B E_BINARY
        );
    }

    function EncodingListItem($s, $t) {
        return array('s' => $s, 't' => $s);
    }

    // from Semafox.Encoding
    function makeEncoding($height = 0, $width = 0, $data = 0, $fw = 0, $bytes = 0, $datablocks = 0, $rsblocks = 0) {
        $t = array(
            'height' => $height,
            'width' => $width,
            'data' => $data,
            'fw' => $fw,
            'bytes' => $bytes,
            'datablocks' => $datablocks,
            'rsblocks' => $rsblocks
        );
        return $t;
    }


    // Annex M placement alogorithm low level
    function placeBit(&$array, $NR, $NC, $r, $c, $p, $b) {
        if ($r < 0) {
            $r += $NR;
            $c += 4 - (($NR + 4) % 8);
        }
        if ($c < 0) {
            $c += $NC;
            $r += 4 - (($NC + 4) % 8);
        }

        $array[$r * $NC + $c] = ($p << 3) + $b;
    }

    function placeBlock(&$array, $NR, $NC, $r, $c, $p) {
        $this->placeBit($array, $NR, $NC, $r - 2, $c - 2, $p, 7);
        $this->placeBit($array, $NR, $NC, $r - 2, $c - 1, $p, 6);
        $this->placeBit($array, $NR, $NC, $r - 1, $c - 2, $p, 5);
        $this->placeBit($array, $NR, $NC, $r - 1, $c - 1, $p, 4);
        $this->placeBit($array, $NR, $NC, $r - 1, $c - 0, $p, 3);
        $this->placeBit($array, $NR, $NC, $r - 0, $c - 2, $p, 2);
        $this->placeBit($array, $NR, $NC, $r - 0, $c - 1, $p, 1);
        $this->placeBit($array, $NR, $NC, $r - 0, $c - 0, $p, 0);
    }

    function placeCornerA(&$array, $NR, $NC, $p) {
        $this->placeBit($array, $NR, $NC, $NR - 1, 0, $p, 7);
        $this->placeBit($array, $NR, $NC, $NR - 1, 1, $p, 6);
        $this->placeBit($array, $NR, $NC, $NR - 1, 2, $p, 5);
        $this->placeBit($array, $NR, $NC, 0, $NC - 2, $p, 4);
        $this->placeBit($array, $NR, $NC, 0, $NC - 1, $p, 3);
        $this->placeBit($array, $NR, $NC, 1, $NC - 1, $p, 2);
        $this->placeBit($array, $NR, $NC, 2, $NC - 1, $p, 1);
        $this->placeBit($array, $NR, $NC, 3, $NC - 1, $p, 0);
    }

    function placeCornerB(&$array, $NR, $NC, $p) {
        $this->placeBit($array, $NR, $NC, $NR - 3, 0, $p, 7);
        $this->placeBit($array, $NR, $NC, $NR - 2, 0, $p, 6);
        $this->placeBit($array, $NR, $NC, $NR - 1, 0, $p, 5);
        $this->placeBit($array, $NR, $NC, 0, $NC - 4, $p, 4);
        $this->placeBit($array, $NR, $NC, 0, $NC - 3, $p, 3);
        $this->placeBit($array, $NR, $NC, 0, $NC - 2, $p, 2);
        $this->placeBit($array, $NR, $NC, 0, $NC - 1, $p, 1);
        $this->placeBit($array, $NR, $NC, 1, $NC - 1, $p, 0);
    }

    function placeCornerC(&$array, $NR, $NC, $p) {
        $this->placeBit($array, $NR, $NC, $NR - 3, 0, $p, 7);
        $this->placeBit($array, $NR, $NC, $NR - 2, 0, $p, 6);
        $this->placeBit($array, $NR, $NC, $NR - 1, 0, $p, 5);
        $this->placeBit($array, $NR, $NC, 0, $NC - 2, $p, 4);
        $this->placeBit($array, $NR, $NC, 0, $NC - 1, $p, 3);
        $this->placeBit($array, $NR, $NC, 1, $NC - 1, $p, 2);
        $this->placeBit($array, $NR, $NC, 2, $NC - 1, $p, 1);
        $this->placeBit($array, $NR, $NC, 3, $NC - 1, $p, 0);
    }

    function placeCornerD(&$array, $NR, $NC, $p) {
        $this->placeBit($array, $NR, $NC, $NR - 1, 0, $p, 7);
        $this->placeBit($array, $NR, $NC, $NR - 1, $NC - 1, $p, 6);
        $this->placeBit($array, $NR, $NC, 0, $NC - 3, $p, 5);
        $this->placeBit($array, $NR, $NC, 0, $NC - 2, $p, 4);
        $this->placeBit($array, $NR, $NC, 0, $NC - 1, $p, 3);
        $this->placeBit($array, $NR, $NC, 1, $NC - 3, $p, 2);
        $this->placeBit($array, $NR, $NC, 1, $NC - 2, $p, 1);
        $this->placeBit($array, $NR, $NC, 1, $NC - 1, $p, 0);
    }

    // Annex M $placement algorithm main function
    function place(&$array, $NR, $NC) {
        // invalidate
        for ($r = 0; $r < $NR; $r++)
            for ($c = 0; $c < $NC; $c++)
                $array[$r * $NC + $c] = 0;
        // start
        $p = 1;
        $r = 4;
        $c = 0;
        do {
            // check corner
            if ($r == $NR && !$c)
                $this->placeCornerA($array, $NR, $NC, $p++);
            if ($r == $NR - 2 && !$c && $NC % 4)
                $this->placeCornerB($array, $NR, $NC, $p++);
            if ($r == $NR - 2 && !$c && ($NC % 8) == 4)
                $this->placeCornerC($array, $NR, $NC, $p++);
            if ($r == $NR + 4 && $c == 2 && !($NC % 8))
                $this->placeCornerD($array, $NR, $NC, $p++);
            // up/right
            do {
                if ($r < $NR && $c >= 0 && !$array[$r * $NC + $c])
                    $this->placeBlock($array, $NR, $NC, $r, $c, $p++);
                $r -= 2;
                $c += 2;
            } while ($r >= 0 && $c < $NC);
            $r++;
            $c += 3;
            // down/left
            do {
                if ($r >= 0 && $c < $NC && !$array[$r * $NC + $c])
                    $this->placeBlock($array, $NR, $NC, $r, $c, $p++);
                $r += 2;
                $c -= 2;
            } while ($r < $NR && $c >= 0);
            $r += 3;
            $c++;
        } while ($r < $NR || $c < $NC);
        // unfilled corner
        if (!$array[$NR * $NC - 1])
            $array[$NR * $NC - 1] = $array[$NR * $NC - $NC - 2] = 1;
    }

    // calculate and append ecc code, and if necessary interleave
    function rs(&$binary, $bytes, $datablock, $rsblock) {
        $blocks = floor(($bytes + 2) / $datablock);
        $rs = new ReedSolomon($rsblock);

        for ($b = 0; $b < $blocks; $b++) {
            $buf = array();
            $p = 0;
            for ($n = $b; $n < $bytes; $n += $blocks)
                array_push($buf, $binary[$n]);

            $enc = $rs->encodeArray($buf);

            // comes back reversed
            $p = $rsblock - 1;
            for ($n = $b; $n < $rsblock * $blocks; $n += $blocks)
                $binary[$bytes + $n] = $enc[$p--];
        }
    }

    function check($character, $range) {
        if ($character == null || strlen($character) > 1)
            return false;
        if (strpos($range, $character) === false)
            return false;
        return true;
    }

    // check if number is a digit, returns true or false
    function isDigit($number) {
        return $this->check($number, "1234567890");
    }

    // check if letter is lowercase, returns true or false
    function isLower($letter) {
        return $this->check($letter, "abcdefghijklmnopqrstuvwxyz");
    }

    // check if letter is uppercase, returns true or false

    function isUpper($letter) {
        return $this->check($letter, "ABCDEFGHIJKLMNOPQRSTUVWXYZ");
    }

    // $perform encoding for ecc200, source s len sl, to target t len tl, using optional encoding control string e
    // return 1 if OK, 0 if failed. Does all necessary $padding to tl
    function ecc200encode(&$t, &$tl, $s, $sl, $e) {
        // start in ASCII encoding mode
        $enc = 'a';
        $tp = 0;
        $sp = 0;
        $p = 0;
        $v = 0;
        $w = 0;

        $oldErrorReporting = error_reporting(0);

        //does not work, but is said to be optional, doesn't work in the JS code either because of a typo
        //        if ($e['encodingLength'] < $sl)
        //        {
        //            die("Encoding string too short");
        //            return 0;
        //        }

        // do the encoding
        while ($sp < $sl && $tp < $tl) {
            // suggest new encoding
            $newenc = $enc;
            if ($tl - $tp <= 1 && ($enc == 'c' || $enc == 't') || $tl - $tp <= 2 && $enc == 'x')
                // auto revert to ASCII
                $enc = 'a';
            //$this->debug("encoding: " + e.encoding);
            //$this->debug("encoding[sp]: " + e.encoding[sp]);
            $newenc = strtolower($e['encoding'][$sp]);
            switch ($newenc) {
                // encode character
                case 'c':
                    // C40
                case 't':
                    // Text
                case 'x': {
                    // X12
                    $out = array_fill(0, 6, 0);
                    $p = 0;
                    $s2 = "!\"#$%&'()*+,-./:;<=>?@[\\]^_\232";
                    $s3 = "";
                    if ($newenc == 'c') {
                        $en = " 0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                        $s3 = "`abcdefghijklmnopqrstuvwxyz{|}~\177";
                    }
                    if ($newenc == 't') {
                        $en = " 0123456789abcdefghijklmnopqrstuvwxyz";
                        $s3 = "`ABCDEFGHIJKLMNOPQRSTUVWXYZ{|}~\177";
                    }
                    if ($newenc == 'x')
                        $en = " 0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ\r*>";
                    do {
                        $c = $s{$sp++};
                        if ($c & 0x80) {
                            if ($newenc == 'x') {
                                die("Cannot encode char in X12: " . $c);
                                return 0;
                            }
                            $c = $c & 0x7f;
                            $out[$p++] = 1;
                            $out[$p++] = 30;
                        }
                        $w = strpos($en, $c);
                        if ($w !== false)
                            $out[$p++] = ($w + 3) % 40;
                        else {
                            if ($newenc == 'x') {
                                die("Cannot encode char in X12: " . $c);
                                return 0;
                            }
                            if (ord($c) < 32) {
                                // shift 1 XXX
                                $out[$p++] = 0;
                                $out[$p++] = $c;
                            } else {
                                $w = strpos($s2, $c);
                                if ($w !== false) {
                                    // shift 2
                                    $out[$p++] = 1;
                                    $out[$p++] = $w;
                                } else {
                                    $w = strpos($s3, $c);
                                    if ($w !== false) {
                                        $out[$p++] = 2;
                                        $out[$p++] = $w;
                                    } else {
                                        die("Should not happen. Could not encode: " . $c);
                                        return 0;
                                    }
                                }
                            }
                        }
                        if ($p == 2 && $tp + 2 == $tl && $sp == $sl)
                            // shift 1 $pad at end
                            $out[$p++] = 0;
                        while ($p >= 3) {
                            $v = $out[0] * 1600 + $out[1] * 40 + $out[2] + 1;
                            if ($enc != $newenc) {
                                if ($enc == 'c' || $enc == 't' || $enc == 'x')
                                    // escape C40/text/X12
                                    $t[$tp++] = 254;
                                elseif ($enc == 'x')
                                    // escape EDIFACT
                                    $t[$tp++] = 0x7C;
                                if ($newenc == 'c')
                                    $t[$tp++] = 230;
                                if ($newenc == 't')
                                    $t[$tp++] = 239;
                                if ($newenc == 'x')
                                    $t[$tp++] = 238;
                                $enc = $newenc;
                            }
                            $t[$tp++] = ($v >> 8);
                            $t[$tp++] = ($v & 0xFF);
                            $p -= 3;
                            $out[0] = $out[3];
                            $out[1] = $out[4];
                            $out[2] = $out[5];
                        }
                    } while ($p && $sp < $sl);
                }
                break;
                case 'e': {
                    // EDIFACT
                    $out = array_fill(0, 4, 0);
                    $p = 0;
                    if ($enc != $newenc) {
                        // can only be from C40/Text/X12
                        $t[$tp++] = 254;
                        $enc = 'a';
                    }
                    while ($sp < $sl && strtolower($e['encoding'][$sp]) == 'e' && $p < 4)
                        $out[$p++] = ord($s{$sp++});
                    if ($p < 4) {
                        $out[$p++] = 0x1F;
                        $enc = 'a';
                    }
                    // termination
                    $t[$tp] = (($s{0} & 0x3F) << 2);
                    $ttp = $tp++;
                    $t[$ttp] = $t[$ttp] | (($s{1} & 0x30) >> 4);
                    $t[$tp] = (($s{1} & 0x0F) << 4);
                    if ($p == 2)
                        $tp++;
                    else {
                        $ttp = $tp++;
                        $t[$ttp] = $t[$ttp] | (($s[2] & 0x3C) >> 2);
                        $t[$tp] = (($s{2} & 0x03) << 6);
                        $ttp = $tp++;
                        $t[$ttp] = $t[$ttp] | ($s{3} & 0x3F);
                    }
                }
                break;
                case 'a':
                    // ASCII
                    if ($enc != $newenc) {
                        if ($enc == 'c' || $enc == 't' || $enc == 'x')
                            // escape C40/text/X12
                            $t[$tp++] = 254;
                        else
                            // escape EDIFACT
                            $t[$tp++] = 0x7C;
                    }
                    $enc = 'a';
                    if ($sl - $sp >= 2 && $this->isDigit($s{$sp}) && $this->isDigit($s{$sp + 1})) {
                        $t[$tp++] = (ord($s{$sp}) - ord('0')) * 10 + ord($s{$sp + 1}) - ord('0') + 130;
                        $sp += 2;
                    } elseif (ord($s{$sp}) > 127) {
                        $t[$tp++] = 235;
                        $t[$tp++] = ord($s{$sp++}) - 127;
                    } else
                        $t[$tp++] = ord($s{$sp++}) + 1;
                    break;
                case 'b': {
                    // Binary
                    // how much to encode
                    $l = 0;
                    if ($encoding) {
                        for ($p = $sp; $p < $sl && strtolower($e['encoding'][$p]) == 'b'; $p++)
                            $l++;
                    }
                    // base256
                    $t[$tp++] = 231;
                    if ($l < 250)
                        $t[$tp++] = $l;
                    else {
                        $t[$tp++] = 249 + floor(l / 250);
                        $t[$tp++] = ($l % 250);
                    }
                    while ($l-- && $tp < $tl) {
                        // see annex H
                        $t[$tp] = ord($s{$sp++}) + ((($tp + 1) * 149) % 255) + 1;
                        $tp++;
                    }
                    // reverse to ASCII at end
                    $enc = 'a';
                }
                break;
                default:
                    die("Unknown encoding: " . $newenc);
                    // failed
                    return 0;
            }
        }
        if ($tp < $tl && $enc != 'a') {
            if ($enc == 'c' || $enc == 'x' || $enc == 't')
                // escape X12/C40/Text
                $t[$tp++] = 254;
            else
                // escape EDIFACT
                $t[$tp++] = 0x7C;
        }
        if ($tp < $tl)
            // $pad
            $t[$tp++] = 129;
        while ($tp < $tl) {
            // more $padding
            // see Annex H
            $v = 129 + ((($tp + 1) * 149) % 253) + 1;
            if ($v > 254)
                $v -= 254;
            $t[$tp++] = $v;
        }

        error_reporting($oldErrorReporting);

        if ($tp > $tl || $sp < $sl) // did not fit
            return 0;
        // OK
        return $tp;
    }

    /**
     * Creates a encoding list (malloc)
     * returns encoding string
     * if lenp not null, target len stored
     * if error, null returned
     * if exact specified, then assumes shortcuts applicable for exact fit in target
     * 1. No unlatch to return to ASCII for last encoded byte after C40 or Text or X12
     * 2. No unlatch to return to ASCII for last 1 or 2 encoded bytes after EDIFACT
     * 3. Final C40 or text encoding exactly in last 2 bytes can have a shift 0 to $pad to make a tripple
     * Only use the encoding from an exact request if the len matches the target, otherwise try again with exact=0
     */
    function encodingList($l, $s, $exact) {
        if (!$l)
            // no length
            return null;
        if ($l > $this->MAXBARCODE)
            // not valid
            return null;

        // Turn off all error reporting
        $oldErrorReporting = error_reporting(0);

        //$this->debug("l: " . $l);
        //$this->debug("s: " . $s);
        $enc = array_fill(0, $this->MAXBARCODE, 0);
        for ($m = 0; $m < $this->MAXBARCODE; $m++) {
            $enc[$m] = array_fill(0, $this->E_MAX, 0);
            for ($n = 0; $n < $this->E_MAX; $n++) {
                //enc[m][n] = new $this->EncodingListItem(0, 0);
                $enc[$m][$n] = array('s' => 0, 't' => 0);
            }
        }

        $p = $l;
        while ($p--) {
            //$this->debug("p: " . $p);
            $b = 0;
            // consider each encoding from this $point
            // ASCII
            $sl = $tl = 1;
            if ($this->isDigit($s{$p}) && $p + 1 < $l && $this->isDigit($s{$p + 1})) {
                //$this->debug("double digit");
                // double digit
                $sl = 2;
            } elseif ($s{$p} & 0x80) {
                //$this->debug("high shifted");
                // high shifted
                $tl = 2;
            }
            $bl = 0;
            if ($p + $sl < $l)
                for ($e = 0; $e < $this->E_MAX; $e++)
                    if ($enc[$p + $sl][$e]['t'] && (($t = $enc[$p + $sl][$e]['t'] + $this->switchCost[$this->E_ASCII][$e]) < $bl || !$bl)) {
                        //$this->debug("1 bl: " . $bl . "b: " . $b);
                        $bl = $t;
                        $b = $e;
                    }
            $enc[$p][$this->E_ASCII]['t'] = $tl + $bl;
            $enc[$p][$this->E_ASCII]['s'] = $sl;
            //$this->debug("eascii t: " . $enc[$p][$this->E_ASCII]['t'] . " s: " . $enc[$p][$this->E_ASCII]['s']);
            if ($bl && $b == $this->E_ASCII)
                $enc[$p][$b]['s'] += $enc[$p + $sl][$b]['s'];
            // C40
            $sub = $tl = $sl = 0;
            do {
                $psl = $p + $sl++;
                $c = $s{$psl};
                //$this->debug("s: " + s);
                //$this->debug("psl: " + $psl);
                //$this->debug("c40: " + c);
                if ($c & 0x80) {
                    // shift + upper
                    $sub += 2;
                    $c &= 0x7F;
                    //$this->debug("shift + upper: " + c);
                }
                if ($c != ' ' && !$this->isDigit($c) && !$this->isUpper($c)) {
                    // shift
                    $sub++;
                    //$this->debug("shift ".$c);
                }
                $sub++;
                while ($sub >= 3) {
                    $sub -= 3;
                    $tl += 2;
                }
            } while ($sub && $p + $sl < $l);
            if ($exact && $sub == 2 && $p + $sl == $l) {
                // special case, can encode last block with shift 0 at end (Is this valid when not end of target buffer?)
                $sub = 0;
                $tl += 2;
                //$this->debug("special case");
            }
            if (!$sub) {
                // can encode C40
                //$this->debug("c40");
                $bl = 0;
                if ($p + $sl < $l) {
                    for ($e = 0; $e < $this->E_MAX; $e++) {
                        if ($enc[$p + $sl][$e]['t'] && (($t = $enc[$p + $sl][$e]['t'] + $this->switchCost[$this->E_C40][$e]) < $bl || !$bl)) {
                            $bl = $t;
                            $b = $e;
                            //$this->debug("2 bl: " + bl + " b: " + b);
                        }
                    }
                }
                if ($exact && $enc[$p + $sl][$this->E_ASCII]['t'] == 1 && 1 < $bl) {
                    // special case, switch to ASCII for last bytes
                    $bl = 1;
                    $b = $this->E_ASCII;
                    //$this->debug("ascii last bytes");
                }
                $enc[$p][$this->E_C40]['t'] = $tl + $bl;
                $enc[$p][$this->E_C40]['s'] = $sl;
                if ($bl && $b == $this->E_C40)
                    $enc[$p][$b]['s'] += $enc[$p + $sl][$b]['s'];
                //$this->debug("c40: b: " + b + " s: " + enc[p][b].s + " t: " + enc[p][b].t);
            }
            // Text
            $sub = 0;
            $tl = 0;
            $sl = 0;
            do {
                $c = $s{$p + $sl++};
                //$this->debug("text: " . $c);
                if ($c & 0x80) {
                    // shift + upper
                    $sub += 2;
                    $c &= 0x7F;
                    //$this->debug("shift+upper: " + c);
                }
                if ($c != ' ' && !$this->isDigit($c) && !$this->isLower($c)) {
                    // shift
                    $sub++;
                    //$this->debug("shift: " . $sub);
                }
                $sub++;
                while ($sub >= 3) {
                    $sub -= 3;
                    $tl += 2;
                }
            } while ($sub && $p + $sl < $l);
            if ($exact && $sub == 2 && $p + $sl == $l) {
                // special case, can encode last block with shift 0 at end (Is this valid when not end of target buffer?)
                $sub = 0;
                $tl += 2;
                //$this->debug("shift 0 at end");
            }
            if (!$sub && $sl) {
                // can encode Text
                //$this->debug("encode text");
                $bl = 0;
                if ($p + $sl < $l) {
                    for ($e = 0; $e < $this->E_MAX; $e++) {
                        if ($enc[$p + $sl][$e]['t'] && (($t = $enc[$p + $sl][$e]['t'] + $this->switchCost[$this->E_TEXT][$e]) < $bl || !$bl)) {
                            $bl = $t;
                            $b = $e;
                            //$this->debug("3 bl: " + bl + " b: " + b);
                        }
                    }
                }
                if ($exact && $enc[$p + $sl][$this->E_ASCII]['t'] == 1 && 1 < $bl) {
                    // special case, switch to ASCII for last bytes
                    $bl = 1;
                    $b = $this->E_ASCII;
                    //$this->debug("ascii last bytes");
                }
                $enc[$p][$this->E_TEXT]['t'] = $tl + $bl;
                $enc[$p][$this->E_TEXT]['s'] = $sl;
                if ($bl && $b == $this->E_TEXT)
                    $enc[$p][$b]['s'] += $enc[$p + $sl][$b]['s'];
                //$this->debug("text: b: " + b + " s: " + enc[p][b].s + " t: " + enc[p][b].t);
            }
            // X12
            $sub = $tl = $sl = 0;
            do {
                $c = $s{$p + $sl++};
                //$this->debug("x12: " . $c);
                if ($c != 13 && $c != '*' && $c != '>' && $c != ' ' && !$this->isDigit($c) && !$this->isUpper($c)) {
                    $sl = 0;
                    //$this->debug("lowercase");
                    break;
                }
                $sub++;
                while ($sub >= 3) {
                    $sub -= 3;
                    $tl += 2;
                }
            } while ($sub && $p + $sl < $l);
            if (!$sub && $sl) {
                // can encode X12
                //$this->debug("can encode x12");
                $bl = 0;
                if ($p + $sl < $l)
                    for ($e = 0; $e < $this->E_MAX; $e++)
                        if ($enc[$p + $sl][$e]['t'] && (($t = $enc[$p + $sl][$e]['t'] + $this->switchCost[$this->E_X12][$e]) < $bl || !$bl)) {
                            $bl = $t;
                            $b = $e;
                            //$this->debug("4 bl: " + bl + " b: " + b);
                        }
                if ($exact && $enc[$p + $sl][$this->E_ASCII]['t'] == 1 && 1 < $bl) {
                    // special case, switch to ASCII for last bytes
                    $bl = 1;
                    $b = $this->E_ASCII;
                    //$this->debug("ascii last bytes");
                }
                $enc[$p][$this->E_X12]['t'] = $tl + $bl;
                $enc[$p][$this->E_X12]['s'] = $sl;
                if ($bl && $b == $this->E_X12)
                    $enc[$p][$b]['s'] += $enc[$p + $sl][$b]['s'];
                //$this->debug("x12: b: " . $b . " s: " . $enc[$p][$b]['s'] . " t: " . $enc[$p][$b]['t']);
            }
            // EDIFACT
            //$this->debug("edifact");
            $sl = $bl = 0;
            if ($s{$p} >= 32 && $s{$p} <= 94) {
                // can encode 1
                //$this->debug("can encode 1");
                $bs = 0;
                if ($p + 1 == $l && (!$bl || $bl < 2)) {
                    $bl = 2;
                    $bs = 1;
                } else {
                    for ($e = 0; $e < $this->E_MAX; $e++) {
                        if ($e != $this->E_EDIFACT && $enc[$p + 1][$e]['t'] && (($t = 2 + $enc[$p + 1][$e]['t'] + $this->switchCost[$this->E_ASCII][$e]) < $bl || !$bl)) {
                            // E_ASCII as allowed for unlatch
                            $bs = 1;
                            $bl = $t;
                            $b = $e;
                            //$this->debug("5 bl: " + bl + " b: " + b);
                        }
                    }
                }
                if ($p + 1 < $l && $ss{$p + 1} >= 32 && $s{$p + 1} <= 94) {
                    // can encode 2
                    //$this->debug("can encode 2");
                    if ($p + 2 == $l && (!$bl || $bl < 2)) {
                        $bl = 3;
                        $bs = 2;
                    } else {
                        for ($e = 0; $e < $this->E_MAX; $e++) {
                            if ($e != $this->E_EDIFACT && $enc[$p + 2][$e]['t'] && (($t = 3 + $enc[$p + 2][$e]['t'] + $this->switchCost[$this->E_ASCII][$e]) < $bl || !$bl)) {
                                // E_ASCII as allowed for unlatch
                                $bs = 2;
                                $bl = $t;
                                $b = $e;
                                //$this->debug("6 bl: " + bl + " b: " + b);
                            }
                        }
                    }
                    if ($p + 2 < $l && $s{p + 2} >= 32 && $s{p + 2} <= 94) {
                        // can encode 3
                        //$this->debug("can encode 3");
                        if ($p + 3 == $l && (!$bl || $bl < 3)) {
                            $bl = 3;
                            $bs = 3;
                        } else
                            for ($e = 0; $e < $this->E_MAX; $e++)
                                if ($e != $this->E_EDIFACT && $enc[$p + 3][$e]['t'] && (($t = 3 + $enc[$p + 3][$e]['t'] + $this->switchCost[$this->E_ASCII][$e]) < $bl || !$bl)) {
                                    // E_ASCII as allowed for unlatch
                                    $bs = 3;
                                    $bl = $t;
                                    $b = $e;
                                    //$this->debug("7 bl: " + bl + " b: " + b);
                                }
                        if ($p + 4 < $l && $s{$p + 3} >= 32 && $s{$p + 3} <= 94) {
                            // can encode 4
                            //$this->debug("can encode 4");
                            if ($p + 4 == $l && (!$bl || $bl < 3)) {
                                $bl = 3;
                                $bs = 4;
                            } else {
                                for ($e = 0; $e < $this->E_MAX; $e++)
                                    if ($enc[$p + 4][$e]['t'] && (($t = 3 + $enc[$p + 4][$e]['t'] + $this->switchCost[$this->E_EDIFACT][$e]) < $bl || !$bl)) {
                                        $bs = 4;
                                        $bl = $t;
                                        $b = $e;
                                        //$this->debug("8 bl: " + bl + " b: " + b);
                                    }
                                if ($exact && $enc[$p + 4][$this->E_ASCII]['t'] && $enc[$p + 4][$this->E_ASCII]['t'] <= 2 && ($t = 3 + $enc[$p + 4][$this->E_ASCII]['t']) < $bl) {
                                    // special case, switch to ASCII for last 1 ot two bytes
                                    $bs = 4;
                                    $bl = $t;
                                    $b = $this->E_ASCII;
                                    //$this->debug("9 bl: " + bl + " b: " + b);
                                }
                            }
                        }
                    }
                }
                $enc[$p][$this->E_EDIFACT]['t'] = $bl;
                $enc[$p][$this->E_EDIFACT]['s'] = $bs;
                if ($bl && $b == $this->E_EDIFACT)
                    $enc[$p][$b]['s'] += $enc[$p + $bs][$b]['t'];
                //$this->debug("edifact: b: " + b + " s: " + enc[p][b].s + " t: " + enc[p][b].t);
            }
            // Binary
            //$this->debug("binary");
            $bl = 0;
            for ($e = 0; $e < $this->E_MAX; $e++) {
                if ($enc[$p + 1][$e]['t'] && (($t = $enc[$p + 1][$e]['t'] + $this->switchCost[$this->E_BINARY][$e] + (($e == $this->E_BINARY && $enc[$p + 1][$e]['t'] == 249) ? 1 : 0)) < $bl || !$bl)) {
                    $bl = $t;
                    $b = $e;
                    //$this->debug("10 bl: " + bl + " b: " + b);
                }
            }
            $enc[$p][$this->E_BINARY]['t'] = 1 + $bl;
            $enc[$p][$this->E_BINARY]['s'] = 1;
            if ($bl && $b == $this->E_BINARY)
                $enc[$p][$b]['s'] += $enc[$p + 1][$b]['s'];
            //$this->debug("binary: b: " + b + " s: " + enc[p][b].s + " t: " + enc[p][b].t);
        }

        $encoder = array();
        $encoding = array_fill(0, $l + 1, 0);
        $p = 0;
        {
            $cur = $this->E_ASCII;
            while ($p < $l) {
                $m = 0;
                $b = 0;
                for ($e = 0; $e < $this->E_MAX; $e++) {
                    //$this->debug("et: " + enc[p][e].t + " m: " + enc[p][b].s + " sc: " + $this->switchCost[cur][e]);
                    if ($enc[$p][$e]['t'] && (($t = $enc[$p][$e]['t'] + $this->switchCost[$cur][$e]) < $m || $t == $m && $e == $cur || !$m)) {
                        $b = $e;
                        $m = $t;
                        //$this->debug("b: " + b + " m: " + m);
                    }
                }
                $cur = $b;
                //$this->debug("m: " . $enc[$p][$b]['s']);
                $m = $enc[$p][$b]['s'];
                if (!$p)
                    $encoder['encodingLength'] = $enc[$p][$b]['t'];
                while ($p < $l && $m--) {
                    //$this->debug("e: " . $this->codings{$b} . " b: " . $b);
                    $encoding[$p++] = $this->codings{$b};
                }
            }
        }

        $encoding[$p] = 0;
        $encoder['encoding'] = $encoding;

        error_reporting($oldErrorReporting);

        return $encoder;
    }

    // Main encoding function
    // Returns the grid containing the matrix. L corner at 0,0.
    // Takes the string to be encoded
    function encode($barcode) {
	$encoded = $this->makeEncoding();
        $W = 0;
        $H = 0;
        $grid = "";
        $binary = array_fill(0, 4096, 0);
        $matrix = $this->makeEncoding();
        $encoding = $this->encodingList(strlen($barcode), (int) $barcode, false);

        $encoded['valid'] = false;

        if (!$encoding) // there's nothing to encode ...
            return $encoded;

        //$this->debug("encoding: " . $encoding);
        //$this->debug("encodingLength: " . $encoding['encodingLength']);
        for ($i = 0; $i < count($this->Encodings); $i++) {
            $matrix = $this->Encodings[$i];
            if ($matrix['bytes'] > $encoding['encodingLength'])
                break;
        }

        if ($matrix['width'] == 0) {
            die("Cannot find suitable size, url is too long!");
            return $encoded;
        }

        $W = $matrix['width'];
        $H = $matrix['height'];

        if ($this->ecc200encode($binary, $matrix['bytes'], $barcode, strlen($barcode), $encoding) == 0) {
            die("URL too long for " + $W + "x" + $H);
            return $encoded;
        }

        // add error checking using ReedSolomon
        $this->rs($binary, $matrix['bytes'], $matrix['datablocks'], $matrix['rsblocks']);


        // block $placement
        {
            $NC = $W - 2 * floor($W / $matrix['fw']);
            $NR = $H - 2 * floor($H / $matrix['data']);
            $places = array_fill(0, $NC * $NR, 0);
            $this->place($places, $NR, $NC);
            $grid = array_fill(0, $W * $H, 0);
            for ($y = 0; $y < $H; $y += $matrix['data']) {
                for ($x = 0; $x < $W; $x++)
                    $grid[$y * $W + $x] = 1;
                for ($x = 0; $x < $W; $x += 2)
                    $grid[($y + $matrix['data'] - 1) * $W + $x] = 1;
            }
            for ($x = 0; $x < $W; $x += $matrix['fw']) {
                for ($y = 0; $y < $H; $y++)
                    $grid[$y * $W + $x] = 1;
                for ($y = 0; $y < $H; $y += 2)
                    $grid[$y * $W + $x + $matrix['fw'] - 1] = 1;
            }
            for ($y = 0; $y < $NR; $y++) {
                for ($x = 0; $x < $NC; $x++) {
                    $v = $places[($NR - $y - 1) * $NC + $x];
                    if ($v == 1 || $v > 7 && ($binary[($v >> 3) - 1] & (1 << ($v & 7))))
                        $grid[(1 + $y + 2 * floor($y / ($matrix['data'] - 2))) * $W + 1 + $x + 2 * floor($x / ($matrix['fw'] - 2))] = 1;
                }
            }
        }

        $encoded['width'] = $W;
        $encoded['height'] = $H;
        $encoded['data'] = $grid;
        $encoded['encoding'] = $encoding;
        $encoded['bytes'] = $matrix['bytes'];
        $encoded['ecc'] = floor(($matrix['bytes'] + 2) / $matrix['datablocks']) * $matrix['rsblocks']; //not used?

        return $encoded;
    }

    function debug($msg) {
        if (!$this->debug)
            return;
        echo $msg . "<br />\n";
    }

    function asHTMLTable($text, $blockSize = 18) {
        $barcode = $this->encode($text);
        $width = $barcode['width'];
        $height = $barcode['height'];
        $out = '';

        $out .= '<table cellspacing="0" cellpadding="0" border="0" width="' . ($width * $blockSize) . '" height="' . ($height * $blockSize) . '">';

        for ($i = $height; $i > 0; $i--) {
            $out .= '<tr height="' . $blockSize . '">';
            for ($j = 0; $j < $width; $j++) {
                if ($barcode['data'][($i - 1) * $height + $j]) {
                    $out .= '<td bgcolor="black" width="' . $blockSize . '" height="' . $blockSize . '">&nbsp;</td>';
                } else {

                    $out .= '<td bgcolor="white" width="' . $blockSize . '" height="' . $blockSize . '">&nbsp;</td>';
                }
            }
            $out .= '</tr>';
        }
        $out .= '</table>';
        return $out;
    }

    function asSVG($text) {
        $barcode = $this->encode($text);
        $width   = $barcode['width'];
        $height  = $barcode['height'];
        $bs      = 8;
        $size    = $bs * $width;

        $repr = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1"'
                . ' viewBox="0 0 ' . $size . ' ' . $size . '"'
                . ' preserveAspectRatio="meet">'."\n";

        for($j=0; $j < $height; $j++){
            for($i=0; $i < $width; $i++){
                if($barcode['data'][$j * $width + $i]){
                    $repr .= '<rect x="' . ($i * $bs) . '" y="' . ($size - (($j + 1) * $bs)) . '" width="' . $bs . '" height="' . $bs . '" style="fill: black; stroke: black;" />'."\n";
                } else {
                    $repr .= '<rect x="' . ($i * $bs) . '" y="' . ($size - (($j + 1) * $bs)) . '" width="' . $bs . '" height="' . $bs . '" style="fill: white; stroke: white;" />'."\n";
                }
            }
        }
        $repr .= '</svg>'."\n";

        return $repr;
    }

    /**
     * returns a libGD image ressource
     *
     * You need to call imagedestroy on it your self!
     */
    function asGDImage($text,$resize=160){
        $barcode = $this->encode($text);
        $w  = $barcode['width'];
        $h  = $barcode['height'];
        $bs = 10;
        $size  = $bs * $w;

        $img   = imagecreatetruecolor($size + 2 * $bs * 2 ,$size + 2 * $bs * 2);
        $white = imagecolorallocate($img,255,255,255);
        $black = imagecolorallocate($img,0,0,0);
        imagefill($img,0,0,$white);

        for($j=0; $j < $h; $j++){
            for($i=0; $i < $w; $i++){
                $x1 = ($i * $bs) + $bs * 2;
                $y1 = ($size - (($j + 1) * $bs)) + $bs * 2;
                $x2 = $x1 + $bs;
                $y2 = $y1 + $bs;

                if($barcode['data'][$j * $w + $i]){
                    imagefilledrectangle($img,$x1,$y1,$x2,$y2,$black);
                } else {
                    imagefilledrectangle($img,$x1,$y1,$x2,$y2,$white);
                }
            }
        }

        if($resize == $size){
            return $img;
        }

        $new  = imagecreatetruecolor($resize,$resize);
        imagecopyresized($new, $img, 0, 0, 0, 0, $resize, $resize, $size + 2 * $bs * 2, $size + 2 * $bs * 2);
        imagedestroy($img);
        return $new;
    }

    function sendPNG($text,$width=160){
        $img  = $this->asGDImage($text,$width);
        header('Content-Type: image/png');
        imagepng($img, './sema.png');
        imagedestroy($img);
    }
}

?>

<?php

namespace PhpSigep\Test\PhpSigep\Pdf;

use PhpSigep\Pdf\ImprovedFPDF;
use PHPUnit\Framework\TestCase;

class ImprovedFPDFTest extends TestCase
{
    public function test_(): void
    {
        $improvedFPDF = new ImprovedFPDF();
        $this->assertSame("", $improvedFPDF->_(null));
        $this->assertSame("Lorem ipsum dolor sit amet", $improvedFPDF->_("Lorem ipsum dolor sit amet"));
        $this->assertSame(utf8_decode("áéíóúç"), $improvedFPDF->_("áéíóúç"));
    }
}

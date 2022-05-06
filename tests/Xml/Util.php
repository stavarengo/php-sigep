<?php

namespace PhpSigep\Test\Xml;

class Util
{
    public static function normalizeXmlForTest(string $xml): string
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        return $dom->saveXML();
    }
}
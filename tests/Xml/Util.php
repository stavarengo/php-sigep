<?php

namespace PhpSigep\Test\Xml;

class Util
{
    /**
     * @param string $xml
     * @return string
     */
    public static function removeWhiteSpaces(string $xml)
    {
        $obj = simplexml_load_string($xml, null, LIBXML_NOBLANKS);

        return str_replace("\n", "", $obj->asXML());
    }

    /**
     * @param string $path
     * @return string
     */
    public static function removeWhiteSpacesFromPath(string $path)
    {
        return self::removeWhiteSpaces(file_get_contents($path));
    }

    public static function normalizeXmlForTest(string $xml): string
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        return $dom->saveXML();
    }
}
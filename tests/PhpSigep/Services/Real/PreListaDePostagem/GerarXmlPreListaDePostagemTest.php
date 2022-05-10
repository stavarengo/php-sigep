<?php

namespace PhpSigep\Test\PhpSigep\Services\Real\PreListaDePostagem;

use PhpSigep\Services\Real\PreListaDePostagem\GerarXmlPreListaDePostagem;
use PhpSigep\Test\BootstrapTrait;
use PhpSigep\Test\Builders\PreListaDePostagemBuilder;
use PhpSigep\Test\Xml\Util;
use PHPUnit\Framework\TestCase;

class GerarXmlPreListaDePostagemTest extends TestCase
{
    use BootstrapTrait;

    public function setUp(): void
    {
        $this->setUpSIGEP();
    }

    public function test_gerar_daos_obrigatorios(): void
    {
        $plp = PreListaDePostagemBuilder::buildDadosObrigatorios();
        $gerarXml = new GerarXmlPreListaDePostagem();
        $xml = Util::normalizeXmlForTest($gerarXml->gerar($plp));
        $xmlEsperado = Util::normalizeXmlForTest(file_get_contents(dirname(__DIR__, 4) . '/resources/xml/modelo_plp1-utf8.xml'));
        $this->assertEquals($xmlEsperado, $xml);
    }
}

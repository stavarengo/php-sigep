<?php

namespace PhpSigep\Test\PhpSigep\Services\Real;

use PhpSigep\Services\Real\FecharPreListaDePostagem;
use PhpSigep\Services\Real\SoapClientFactory;
use PhpSigep\Test\BootstrapTrait;
use PhpSigep\Test\Builders\PreListaDePostagemBuilder;
use PhpSigep\Test\Fakes\SoapClientFake;
use PHPUnit\Framework\TestCase;

class FecharPreListaDePostagemTest extends TestCase
{
    use BootstrapTrait;
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpSIGEP();
    }

    public function test_execute(): void
    {
        $client = new SoapClientFake();
        $std = new \stdClass();
        $std->return = '123456';
        $client->setRetornoFechaPlpVariosServicos($std);
        SoapClientFactory::setSoapClient($client);

        $fechar = new FecharPreListaDePostagem();
        $retorno = $fechar->execute(PreListaDePostagemBuilder::buildDadosObrigatorios());

        /** @var \PhpSigep\Model\FechaPlpVariosServicosRetorno $result */
        $result = $retorno->getResult();
        $this->assertEquals('123456', $result->getIdPlp());
    }
}

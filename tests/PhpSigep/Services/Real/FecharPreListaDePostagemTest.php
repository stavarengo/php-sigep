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
        $plp = PreListaDePostagemBuilder::buildDadosObrigatorios();
        $retorno = $fechar->execute($plp);

        /** @var \PhpSigep\Model\FechaPlpVariosServicosRetorno $result */
        $result = $retorno->getResult();
        $this->assertEquals('123456', $result->getIdPlp());
        $args = $client->getSoapArgs();
        $this->assertEquals(['xml', 'idPlpCliente', 'cartaoPostagem', 'listaEtiquetas', 'usuario', 'senha'], array_keys($args));
        $this->assertIsString($args['xml']);
        $this->assertEquals('', $args['idPlpCliente']);
        $this->assertEquals($plp->getAccessData()->getCartaoPostagem(), $args['cartaoPostagem']);
        $this->assertEquals(['PD73958096BR'], $args['listaEtiquetas']);
        $this->assertEquals($plp->getAccessData()->getUsuario(), $args['usuario']);
        $this->assertEquals($plp->getAccessData()->getSenha(), $args['senha']);
    }
}

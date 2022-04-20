<?php

namespace PhpSigep\Services\Real;

use PhpSigep\Config;
use PhpSigep\Model\AccessDataHomologacao;
use PhpSigep\Test\BootstrapTrait;
use PhpSigep\Test\Fakes\SoapClientFake;
use PHPUnit\Framework\TestCase;

class BuscaClienteTest extends TestCase
{
    use BootstrapTrait;

    public function setUp(): void
    {
        $this->setUpSIGEP();
    }

    public function testExecute(): void
    {
        SoapClientFactory::setSoapClient(new SoapClientFake());
        $busca = new BuscaCliente();
        $result = $busca->execute(new AccessDataHomologacao());
        $this->assertFalse($result->getIsSoapFault());
        $this->assertInstanceOf(\PhpSigep\Model\BuscaClienteResult::class, $result->getResult());
    }
}

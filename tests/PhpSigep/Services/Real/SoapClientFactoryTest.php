<?php

namespace PhpSigep\Services\Real;

use PhpSigep\Test\BootstrapTrait;
use PhpSigep\Test\Fakes\SoapClientFake;
use PHPUnit\Framework\TestCase;

class SoapClientFactoryTest extends TestCase
{
    use BootstrapTrait;

    public function setUp(): void
    {
        $this->setUpSIGEP();
    }

    public function test_get_soap_client(): void
    {
        $this->assertInstanceOf(\SoapClient::class, SoapClientFactory::getSoapClient());
    }

    public function test_get_soap_client_test(): void
    {
        SoapClientFactory::setSoapClient(new SoapClientFake());
        $this->assertInstanceOf(SoapClientFake::class, SoapClientFactory::getSoapClient());
    }
}

<?php

namespace PhpSigep\Services\Real;

use PhpSigep\Config;
use PhpSigep\Model\AccessDataHomologacao;
use PHPUnit\Framework\TestCase;

class BuscaClienteTest extends TestCase
{

    public function setUp(): void
    {
        $config = new Config();
        $config->setAccessData(new AccessDataHomologacao());
        $config->setEnv(Config::ENV_DEVELOPMENT);
        $config->setCacheOptions([
            'storageOptions' => [
                'enabled' => false,
                'ttl' => 10,
                'cacheDir' => sys_get_temp_dir(),
            ],
        ]);

        \PhpSigep\Bootstrap::start($config);

    }

    public function testExecute(): void
    {
        $busca = new BuscaCliente();
        $result = $busca->execute(new AccessDataHomologacao());
        $this->assertFalse($result->getIsSoapFault());
    }
}

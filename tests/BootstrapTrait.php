<?php

namespace PhpSigep\Test;

trait BootstrapTrait
{
    public function setUpSIGEP(): void
    {
        $config = new \PhpSigep\Config();

        $config->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());

        $config->setEnv(\PhpSigep\Config::ENV_DEVELOPMENT);


        $config->setCacheOptions(array(
            'storageOptions' => array(
                'enabled' => false,
                'ttl' => 10,
                'cacheDir' => sys_get_temp_dir()
            )
        ));

        \PhpSigep\Bootstrap::start($config);
    }
}
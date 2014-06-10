<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */

namespace PhpSigep;

interface FactoryInterface
{
    /**
     * Create service
     *
     * @param Config $config
     * @return mixed
     */
    public function createService(Config $config);
}
<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */
 
namespace PhpSigep\Services\Real;

use PhpSigep\Model\AbstractModel;
use PhpSigep\Services\Result;

interface RealServiceInterface
{
    /**
     * @param AbstractModel $params
     * @return Result
     */
    public function execute(AbstractModel $params);
} 
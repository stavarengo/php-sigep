<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */

namespace PhpSigep\Cache;

use PhpSigep\Cache\Storage\Adapter\AdapterOptions;
use PhpSigep\DefaultStdClass;

class Options extends DefaultStdClass
{
    /**
     * @var string|StorageInterface
     */
    protected $storageAdapter = 'PhpSigep\Cache\Storage\Adapter\FileSystem';

    /**
     * @var array|\Traversable|AdapterOptions
     */
    protected $storageOptions;

    /**
     * @param string|\PhpSigep\Cache\StorageInterface $storageAdapter
     */
    public function setStorageAdapter($storageAdapter)
    {
        $this->storageAdapter = $storageAdapter;
    }

    /**
     * @return \PhpSigep\Cache\StorageInterface
     */
    public function getStorageAdapter()
    {
        return $this->storageAdapter;
    }

    /**
     * @param array|\Traversable|AdapterOptions $storageOptions
     * @return $this
     */
    public function setStorageOptions($storageOptions)
    {
        $this->storageOptions = $storageOptions;

        return $this;
    }

    /**
     * @return \PhpSigep\Cache\Storage\Adapter\AdapterOptions
     */
    public function getStorageOptions()
    {
        return $this->storageOptions;
    }
} 
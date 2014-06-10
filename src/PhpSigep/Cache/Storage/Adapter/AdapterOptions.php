<?php

namespace PhpSigep\Cache\Storage\Adapter;

use PhpSigep\Cache\Storage\Adapter\Exception\InvalidArgumentException;
use PhpSigep\Cache\StorageInterface;
use PhpSigep\DefaultStdClass;
use PhpSigep\ErrorHandler;

/**
 * Unless otherwise marked, all options in this class affect all adapters.
 */
class AdapterOptions extends DefaultStdClass
{

    protected $enabled = false;

    /**
     * The adapter using these options
     *
     * @var StorageInterface
     */
    protected $adapter;

    /**
     * Namespace option
     *
     * @var string
     */
    protected $namespace = 'phpsigepcache';

    /**
     * TTL option (in seconds)
     *
     * @var int|float 0 means infinite or maximum of adapter
     */
    protected $ttl = 0;

    /**
     * Validate key against pattern
     *
     * @var string
     */
    protected $keyPattern = '';

    /**
     * Adapter using this instance
     *
     * @param  StorageInterface|null $adapter
     * @return AdapterOptions
     */
    public function setAdapter(StorageInterface $adapter = null)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Set namespace.
     *
     * @param  string $namespace
     * @return AdapterOptions
     */
    public function setNamespace($namespace)
    {
        $this->namespace = (string)$namespace;

        return $this;
    }

    /**
     * Get namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set time to live.
     *
     * @param  int|float $ttl
     * @return AdapterOptions
     */
    public function setTtl($ttl)
    {
        $this->normalizeTtl($ttl);
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Get time to live.
     *
     * @return float
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Set key pattern
     *
     * @param  null|string $keyPattern
     * @throws Exception\InvalidArgumentException
     * @return AdapterOptions
     */
    public function setKeyPattern($keyPattern)
    {
        $keyPattern = (string)$keyPattern;
        if ($this->keyPattern !== $keyPattern) {
            // validate pattern
            if ($keyPattern !== '') {
                ErrorHandler::start(E_WARNING);
                $result = preg_match($keyPattern, '');
                $error  = ErrorHandler::stop();
                if ($result === false) {
                    throw new Exception\InvalidArgumentException(
                        sprintf(
                            'Invalid pattern "%s"%s',
                            $keyPattern,
                            ($error ? ': ' . $error->getMessage() : '')
                        ),
                        0,
                        $error
                    );
                }
            }

            $this->keyPattern = $keyPattern;
        }

        return $this;
    }

    /**
     * Get key pattern
     *
     * @return string
     */
    public function getKeyPattern()
    {
        return $this->keyPattern;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Validates and normalize a TTL.
     *
     * @param  int|float $ttl
     * @throws InvalidArgumentException
     * @return void
     */
    protected function normalizeTtl(&$ttl)
    {
        if (!is_int($ttl)) {
            $ttl = (float)$ttl;

            // convert to int if possible
            if ($ttl === (float)(int)$ttl) {
                $ttl = (int)$ttl;
            }
        }

        if ($ttl < 0) {
            throw new InvalidArgumentException("TTL can't be negative");
        }
    }


}

<?php

namespace PhpSigep\Cache\Storage\Adapter;

use PhpSigep\Cache\Storage\Adapter\Exception\InvalidArgumentException;
use PhpSigep\Cache\StorageInterface;

abstract class AbstractAdapter implements StorageInterface
{

    /**
     * options
     *
     * @var mixed
     */
    protected $options;

    /**
     * Constructor
     *
     * @param  null|array|Traversable|AdapterOptions $options
     * @throws InvalidArgumentException
     */
    public function __construct($options = null)
    {
        if ($options) {
            $this->setOptions($options);
        }
    }

    /* configuration */

    /**
     * Set options.
     *
     * @param  array|\Traversable|AdapterOptions $options
     * @return AbstractAdapter
     * @see    getOptions()
     */
    public function setOptions($options)
    {
        if ($this->options !== $options) {
            if (!$options instanceof AdapterOptions) {
                $options = new AdapterOptions($options);
            }

            if ($this->options) {
                $this->options->setAdapter(null);
            }
            $options->setAdapter($this);
            $this->options = $options;
        }

        return $this;
    }

    /**
     * Get options.
     *
     * @return AdapterOptions
     * @see setOptions()
     */
    public function getOptions()
    {
        if (!$this->options) {
            $this->setOptions(new AdapterOptions());
        }

        return $this->options;
    }

    /**
     * Get an item.
     *
     * @param  string $key
     * @return mixed Data on success, null on failure
     *
     * @triggers getItem.pre(PreEvent)
     * @triggers getItem.post(PostEvent)
     * @triggers getItem.exception(ExceptionEvent)
     */
    public function getItem($key)
    {
        if (!$this->getOptions()->getEnabled()) {
            return null;
        }

        $this->normalizeKey($key);

        $result = null;
        try {
            $result = $this->internalGetItem($key);
        } catch (\Exception $e) {
        }

        return $result;
    }

    /**
     * Internal method to get an item.
     *
     * @param  string $normalizedKey
     * @param  bool $success
     * @return mixed Data on success, null on failure
     */
    protected abstract function internalGetItem(& $normalizedKey, & $success = null);

    /**
     * Store an item.
     *
     * @param  string $key
     * @param  mixed $value
     * @return bool
     *
     * @triggers setItem.pre(PreEvent)
     * @triggers setItem.post(PostEvent)
     * @triggers setItem.exception(ExceptionEvent)
     */
    public function setItem($key, $value)
    {
        if (!$this->getOptions()->getEnabled()) {
            return false;
        }

        $this->normalizeKey($key);

        $result = false;
        try {
            $result = $this->internalSetItem($key, $value);
        } catch (\Exception $e) {
        }

        return $result;
    }

    /**
     * Internal method to store an item.
     *
     * @param  string $normalizedKey
     * @param  mixed $value
     * @return bool
     */
    abstract protected function internalSetItem(&$normalizedKey, &$value);

    /**
     * Remove an item.
     *
     * @param  string $key
     * @return bool
     *
     * @triggers removeItem.pre(PreEvent)
     * @triggers removeItem.post(PostEvent)
     * @triggers removeItem.exception(ExceptionEvent)
     */
    public function removeItem($key)
    {
        if (!$this->getOptions()->getEnabled()) {
            return false;
        }

        $this->normalizeKey($key);

        $result = false;
        try {
            $result = $this->internalRemoveItem($key);
        } catch (\Exception $e) {
        }

        return $result;
    }

    /**
     * Internal method to remove an item.
     *
     * @param  string $normalizedKey
     * @return bool
     */
    abstract protected function internalRemoveItem(&$normalizedKey);

    /**
     * Test if an item exists.
     *
     * @param  string $key
     * @return bool
     *
     * @triggers hasItem.pre(PreEvent)
     * @triggers hasItem.post(PostEvent)
     * @triggers hasItem.exception(ExceptionEvent)
     */
    public function hasItem($key)
    {
        if (!$this->getOptions()->getEnabled()) {
            return false;
        }

        $this->normalizeKey($key);

        $result = false;
        try {
            $result = $this->internalHasItem($key);
        } catch (\Exception $e) {
        }

        return $result;
    }

    /**
     * Internal method to test if an item exists.
     *
     * @param  string $normalizedKey
     * @return bool
     */
    protected function internalHasItem(& $normalizedKey)
    {
        $success = null;
        $this->internalGetItem($normalizedKey, $success);

        return $success;
    }

    /**
     * Validates and normalizes a key
     *
     * @param  string $key
     * @throws InvalidArgumentException
     * @return void
     */
    protected function normalizeKey(&$key)
    {
        $key = (string)$key;

        if ($key === '') {
            throw new InvalidArgumentException("An empty key isn't allowed");
        } elseif (($p = $this->getOptions()->getKeyPattern()) && !preg_match($p, $key)) {
            throw new InvalidArgumentException(
                "The key '{$key}' doesn't match against pattern '{$p}'"
            );
        }
    }
}

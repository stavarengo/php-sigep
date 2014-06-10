<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */

namespace PhpSigep\Cache;

interface StorageInterface
{
    /**
     * Get an item.
     *
     * @param  string $key
     * @return mixed Data on success, null on failure
     */
    public function getItem($key);

    /**
     * Test if an item exists.
     *
     * @param  string $key
     * @return bool
     */
    public function hasItem($key);

    /**
     * Add an item.
     *
     * @param  string $key
     * @param  mixed $value
     * @return bool
     */
    public function setItem($key, $value);

    /**
     * Remove an item.
     *
     * @param  string $key
     * @return bool
     */
    public function removeItem($key);
} 
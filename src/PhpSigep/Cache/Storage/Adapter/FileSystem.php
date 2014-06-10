<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */

namespace PhpSigep\Cache\Storage\Adapter;

use PhpSigep\ErrorHandler;

class FileSystem extends AbstractAdapter
{

    /**
     * An identity for the last filespec
     * (cache directory + namespace prefix + key + directory level)
     *
     * @var string
     */
    protected $lastFileSpecId = '';

    /**
     * The last used filespec
     *
     * @var string
     */
    protected $lastFileSpec = '';

    public function setOptions($options)
    {
        if (!$options instanceof FileSystemOptions) {
            $options = new FileSystemOptions(($options instanceof AdapterOptions ? $options->toArray(
            ) : (array)$options));
        }

        return parent::setOptions($options);
    }

    public function getItem($key)
    {
        $options = $this->getOptions();
        if ($options->getEnabled() && $options->getClearStatCache()) {
            clearstatcache();
        }

        return parent::getItem($key);
    }

    public function setItem($key, $value)
    {
        $options = $this->getOptions();
        if ($options->getEnabled() && $options->getClearStatCache()) {
            clearstatcache();
        }

        return parent::setItem($key, $value);
    }

    public function removeItem($key)
    {
        $options = $this->getOptions();
        if ($options->getEnabled() && $options->getClearStatCache()) {
            clearstatcache();
        }

        return parent::removeItem($key);
    }

    public function hasItem($key)
    {
        $options = $this->getOptions();
        if ($options->getEnabled() && $options->getClearStatCache()) {
            clearstatcache();
        }

        return parent::hasItem($key);
    }


    /**
     * Internal method to test if an item exists.
     *
     * @param  string $normalizedKey
     * @throws Exception\RuntimeException
     * @return bool
     */
    protected function internalHasItem(& $normalizedKey)
    {
        $file = $this->getFileSpec($normalizedKey) . '.dat';
        if (!file_exists($file)) {
            return false;
        }

        $ttl = $this->getOptions()->getTtl();
        if ($ttl) {
            ErrorHandler::start();
            $mtime = filemtime($file);
            $error = ErrorHandler::stop();
            if (!$mtime) {
                throw new Exception\RuntimeException(
                    "Error getting mtime of file '{$file}'", 0, $error
                );
            }

            if (time() >= ($mtime + $ttl)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Internal method to get an item.
     *
     * @param  string $normalizedKey
     * @param  bool $success
     * @throws \Exception
     * @return mixed Data on success, null on failure
     */
    protected function internalGetItem(& $normalizedKey, & $success = null)
    {
        if (!$this->internalHasItem($normalizedKey)) {
            $success = false;

            return null;
        }

        try {
            $filespec = $this->getFileSpec($normalizedKey);
            $data     = $this->getFileContent($filespec . '.dat');
            $success  = true;

            return $data;
        } catch (\Exception $e) {
            $success = false;
            throw $e;
        }
    }

    /**
     * Internal method to store an item.
     *
     * @param  string $normalizedKey
     * @param  mixed $value
     * @return bool
     */
    protected function internalSetItem(&$normalizedKey, &$value)
    {
        $filespec = $this->getFileSpec($normalizedKey);
        $this->prepareDirectoryStructure($filespec);

        // write data in non-blocking mode
        $wouldblock = null;
        $this->putFileContent($filespec . '.dat', $value, true, $wouldblock);

        // Retry writing data in blocking mode if it was blocked before
        if ($wouldblock) {
            $this->putFileContent($filespec . '.dat', $value);
        }

        return true;
    }

    /**
     * Internal method to remove an item.
     *
     * @param  string $normalizedKey
     * @return bool
     */
    protected function internalRemoveItem(&$normalizedKey)
    {
        $filespec = $this->getFileSpec($normalizedKey);
        if (!file_exists($filespec . '.dat')) {
            return false;
        } else {
            $this->unlink($filespec . '.dat');
        }

        return true;
    }

    /**
     * Get file spec of the given key and namespace
     *
     * @param  string $normalizedKey
     * @return string
     */
    protected function getFileSpec($normalizedKey)
    {
        $options   = $this->getOptions();
        $namespace = $options->getNamespace();
        $prefix    = ($namespace === '') ? '' : $namespace . $options->getNamespaceSeparator();
        $path      = $options->getCacheDir() . DIRECTORY_SEPARATOR;
        $level     = $options->getDirLevel();

        $fileSpecId = $path . $prefix . $normalizedKey . '/' . $level;
        if ($this->lastFileSpecId !== $fileSpecId) {
            if ($level > 0) {
                // create up to 256 directories per directory level
                $hash = md5($normalizedKey);
                for ($i = 0, $max = ($level * 2); $i < $max; $i += 2) {
                    $path .= $prefix . $hash[$i] . $hash[$i + 1] . DIRECTORY_SEPARATOR;
                }
            }

            $this->lastFileSpecId = $fileSpecId;
            $this->lastFileSpec   = $path . $prefix . $normalizedKey;
        }

        return $this->lastFileSpec;
    }

    /**
     * Read a complete file
     *
     * @param  string $file File complete path
     * @param  bool $nonBlocking Don't block script if file is locked
     * @param  bool $wouldblock The optional argument is set to TRUE if the lock would block
     * @return string
     * @throws Exception\RuntimeException
     */
    protected function getFileContent($file, $nonBlocking = false, & $wouldblock = null)
    {
        $locking    = $this->getOptions()->getFileLocking();
        $wouldblock = null;

        ErrorHandler::start();

        // if file locking enabled -> file_get_contents can't be used
        if ($locking) {
            $fp = fopen($file, 'rb');
            if ($fp === false) {
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException(
                    "Error opening file '{$file}'", 0, $err
                );
            }

            if ($nonBlocking) {
                $lock = flock($fp, LOCK_SH | LOCK_NB, $wouldblock);
                if ($wouldblock) {
                    fclose($fp);
                    ErrorHandler::stop();

                    return;
                }
            } else {
                $lock = flock($fp, LOCK_SH);
            }

            if (!$lock) {
                fclose($fp);
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException(
                    "Error locking file '{$file}'", 0, $err
                );
            }

            $res = stream_get_contents($fp);
            if ($res === false) {
                flock($fp, LOCK_UN);
                fclose($fp);
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException(
                    'Error getting stream contents', 0, $err
                );
            }

            flock($fp, LOCK_UN);
            fclose($fp);

            // if file locking disabled -> file_get_contents can be used
        } else {
            $res = file_get_contents($file, false);
            if ($res === false) {
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException(
                    "Error getting file contents for file '{$file}'", 0, $err
                );
            }
        }

        ErrorHandler::stop();

        return $res;
    }

    /**
     * Prepares a directory structure for the given file(spec)
     * using the configured directory level.
     *
     * @param string $file
     * @return void
     * @throws Exception\RuntimeException
     */
    protected function prepareDirectoryStructure($file)
    {
        $options = $this->getOptions();
        $level   = $options->getDirLevel();

        // Directory structure is required only if directory level > 0
        if (!$level) {
            return;
        }

        // Directory structure already exists
        $pathname = dirname($file);
        if (file_exists($pathname)) {
            return;
        }

        $perm  = $options->getDirPermission();
        $umask = $options->getUmask();
        if ($umask !== false && $perm !== false) {
            $perm = $perm & ~$umask;
        }

        ErrorHandler::start();

        if ($perm === false || $level == 1) {
            // build-in mkdir function is enough

            $umask = ($umask !== false) ? umask($umask) : false;
            $res   = mkdir($pathname, ($perm !== false) ? $perm : 0777, true);

            if ($umask !== false) {
                umask($umask);
            }

            if (!$res) {
                $oct = ($perm === false) ? '777' : decoct($perm);
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException(
                    "mkdir('{$pathname}', 0{$oct}, true) failed", 0, $err
                );
            }

            if ($perm !== false && !chmod($pathname, $perm)) {
                $oct = decoct($perm);
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException(
                    "chmod('{$pathname}', 0{$oct}) failed", 0, $err
                );
            }

        } else {
            // build-in mkdir function sets permission together with current umask
            // which doesn't work well on multo threaded webservers
            // -> create directories one by one and set permissions

            // find existing path and missing path parts
            $parts = array();
            $path  = $pathname;
            while (!file_exists($path)) {
                array_unshift($parts, basename($path));
                $nextPath = dirname($path);
                if ($nextPath === $path) {
                    break;
                }
                $path = $nextPath;
            }

            // make all missing path parts
            foreach ($parts as $part) {
                $path .= DIRECTORY_SEPARATOR . $part;

                // create a single directory, set and reset umask immediately
                $umask = ($umask !== false) ? umask($umask) : false;
                $res   = mkdir($path, ($perm === false) ? 0777 : $perm, false);
                if ($umask !== false) {
                    umask($umask);
                }

                if (!$res) {
                    $oct = ($perm === false) ? '777' : decoct($perm);
                    ErrorHandler::stop();
                    throw new Exception\RuntimeException(
                        "mkdir('{$path}', 0{$oct}, false) failed"
                    );
                }

                if ($perm !== false && !chmod($path, $perm)) {
                    $oct = decoct($perm);
                    ErrorHandler::stop();
                    throw new Exception\RuntimeException(
                        "chmod('{$path}', 0{$oct}) failed"
                    );
                }
            }
        }

        ErrorHandler::stop();
    }

    /**
     * Write content to a file
     *
     * @param  string $file File complete path
     * @param  string $data Data to write
     * @param  bool $nonBlocking Don't block script if file is locked
     * @param  bool $wouldblock The optional argument is set to TRUE if the lock would block
     * @return void
     * @throws Exception\RuntimeException
     */
    protected function putFileContent($file, $data, $nonBlocking = false, & $wouldblock = null)
    {
        $options     = $this->getOptions();
        $locking     = $options->getFileLocking();
        $nonBlocking = $locking && $nonBlocking;
        $wouldblock  = null;

        $umask = $options->getUmask();
        $perm  = $options->getFilePermission();
        if ($umask !== false && $perm !== false) {
            $perm = $perm & ~$umask;
        }

        ErrorHandler::start();

        // if locking and non blocking is enabled -> file_put_contents can't used
        if ($locking && $nonBlocking) {

            $umask = ($umask !== false) ? umask($umask) : false;

            $fp = fopen($file, 'cb');

            if ($umask) {
                umask($umask);
            }

            if (!$fp) {
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException(
                    "Error opening file '{$file}'", 0, $err
                );
            }

            if ($perm !== false && !chmod($file, $perm)) {
                fclose($fp);
                $oct = decoct($perm);
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException("chmod('{$file}', 0{$oct}) failed", 0, $err);
            }

            if (!flock($fp, LOCK_EX | LOCK_NB, $wouldblock)) {
                fclose($fp);
                $err = ErrorHandler::stop();
                if ($wouldblock) {
                    return;
                } else {
                    throw new Exception\RuntimeException("Error locking file '{$file}'", 0, $err);
                }
            }

            if (fwrite($fp, $data) === false) {
                flock($fp, LOCK_UN);
                fclose($fp);
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException("Error writing file '{$file}'", 0, $err);
            }

            if (!ftruncate($fp, strlen($data))) {
                flock($fp, LOCK_UN);
                fclose($fp);
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException("Error truncating file '{$file}'", 0, $err);
            }

            flock($fp, LOCK_UN);
            fclose($fp);

            // else -> file_put_contents can be used
        } else {
            $flags = 0;
            if ($locking) {
                $flags = $flags | LOCK_EX;
            }

            $umask = ($umask !== false) ? umask($umask) : false;

            $rs = file_put_contents($file, $data, $flags);

            if ($umask) {
                umask($umask);
            }

            if ($rs === false) {
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException(
                    "Error writing file '{$file}'", 0, $err
                );
            }

            if ($perm !== false && !chmod($file, $perm)) {
                $oct = decoct($perm);
                $err = ErrorHandler::stop();
                throw new Exception\RuntimeException("chmod('{$file}', 0{$oct}) failed", 0, $err);
            }
        }

        ErrorHandler::stop();
    }

    /**
     * Unlink a file
     *
     * @param string $file
     * @return void
     * @throws Exception\RuntimeException
     */
    protected function unlink($file)
    {
        ErrorHandler::start();
        $res = unlink($file);
        $err = ErrorHandler::stop();

        // only throw exception if file still exists after deleting
        if (!$res && file_exists($file)) {
            throw new Exception\RuntimeException(
                "Error unlinking file '{$file}'; file still exists", 0, $err
            );
        }
    }

} 
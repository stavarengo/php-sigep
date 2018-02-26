<?php
namespace Send4\PhpSigep\Facades;

use Illuminate\Support\Facades\Facade;

class PhpSigep extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'php_sigep';
    }

}

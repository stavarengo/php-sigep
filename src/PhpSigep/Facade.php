<?php
namespace Send4\PhpSigep;

use Illuminate\Support\Facades\Facade;

class Facade extends Facade {

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

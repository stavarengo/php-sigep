<?php
namespace Send4\PhpSigep;

use Illuminate\Support\ServiceProvider;
use Send4\PhpSigep\PhpSigep;

class ServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->alias('php_sigep', PhpSigep::class);
        $this->app->bind('phps_sigep', function ($app) {
            return new PhpSigep($app['request']->server->all());
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('php_sigep');
    }

}

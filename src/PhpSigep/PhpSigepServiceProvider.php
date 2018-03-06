<?php
namespace Send4\PhpSigep;

use Illuminate\Support\ServiceProvider;
use Send4\PhpSigep\LogisticaReversa;

class PhpSigepServiceProvider extends ServiceProvider {

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
        $this->app->alias('PhpSigep', PhpSigep::class);
        $this->app->bind('PhpSigep', function ($app) {
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
        return array('PhpSigep', 'LogisticaReversa');
    }

}

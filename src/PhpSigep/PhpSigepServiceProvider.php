<?php
namespace Send4\PhpSigep;

use Illuminate\Support\ServiceProvider;

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
        $this->app['php_sigep'] = $this->app->share(function ($app)
        {
            return new PhpSigep($app['request']->server->all());
        });
    }

}

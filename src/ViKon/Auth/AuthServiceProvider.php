<?php namespace ViKon\Auth;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthServiceProvider
 *
 * @package ViKon\Auth
 */
class AuthServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('vi-kon/auth');

        $this->app['auth-role'] = $this->app->share(function ($app)
            {
                return new AuthRole();
            }
        );

        $this->app->booting(function ()
            {
                $loader = AliasLoader::getInstance();
                $loader->alias('AuthRole', '\ViKon\Auth\Facades\AuthRole');
            }
        );

        include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'filters.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}

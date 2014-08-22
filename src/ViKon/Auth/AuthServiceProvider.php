<?php namespace ViKon\Auth;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthServiceProvider
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
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

        $this->app['auth-user'] = $this->app->share(function ()
            {
                return new AuthUser();
            }
        );

        $this->app['auth-route'] = $this->app->share(function ()
            {
                return new AuthRoute();
            }
        );

        $this->app->booting(function ()
            {
                $loader = AliasLoader::getInstance();
                $loader->alias('AuthUser', '\ViKon\Auth\Facades\AuthUser');
                $loader->alias('AuthRoute', '\ViKon\Auth\Facades\AuthRoute');
            }
        );

        \Event::listen('smarty-view.init', function ($config)
        {
            $config->set('smarty-view::plugins_path', array_merge(
                $config->get('smarty-view::plugins_path'),
                array(__DIR__ . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'plugins')));
        });

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

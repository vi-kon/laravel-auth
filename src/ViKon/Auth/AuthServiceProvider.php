<?php namespace ViKon\Auth;

use Illuminate\Auth\EloquentUserProvider;
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
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('auth-role.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => base_path('/database/migrations'),
        ], 'migrations');

        app('router')->middleware('auth.role', 'ViKon\Auth\Middleware\HasAccess');

        app('auth')->extend('eloquent', function ($app) {
            $model    = $app['config']['auth.model'];
            $provider = new EloquentUserProvider($this->app['hash'], $model);

            return new Guard($provider, $app['session.store']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['auth.role.user', 'auth.role.route'];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('auth.role.user', 'ViKon\Auth\AuthUser');
        $this->app->singleton('auth.role.route', 'ViKon\Auth\AuthRoute');

        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'auth-role');

        \Event::listen('smarty-view.init', function ($config) {
            $config->set('smarty-view::plugins_path', array_merge($config->get('smarty-view::plugins_path'), [
                implode(DIRECTORY_SEPARATOR, [__DIR__, 'smarty', 'plugins']),
            ]));
        });
    }
}

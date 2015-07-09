<?php namespace ViKon\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use ViKon\Auth\Middleware\HasAccessMiddleware;
use ViKon\Auth\Middleware\PermissionMiddleware;

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
     * {@inheritDoc}
     */
    public function __construct($app)
    {
        $this->defer = false;

        parent::__construct($app);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../config/config.php' => config_path('vi-kon/auth.php'),], 'config');
        $this->publishes([__DIR__ . '/../../database/migrations/' => base_path('/database/migrations'),], 'migrations');

        $this->app->make('router')->middleware('auth.has_access', HasAccessMiddleware::class);
        $this->app->make('router')->middleware('auth.permission', PermissionMiddleware::class);

        $this->app->make('auth')->extend('eloquent', function (Application $app) {
            $model    = $app->make('config')->get('auth.model');
            $provider = new EloquentUserProvider($app->make('hash'), $model);

            return new Guard($provider, $app->make('session.store'));
        });
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return ['auth.role.user', 'auth.role.route'];
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->app->singleton('auth.role.user', 'ViKon\Auth\AuthUser');
        $this->app->singleton('auth.role.route', 'ViKon\Auth\AuthRoute');

        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'auth-role');
    }
}

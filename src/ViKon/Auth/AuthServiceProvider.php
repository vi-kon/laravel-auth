<?php namespace ViKon\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\ServiceProvider;
use ViKon\Auth\Middleware\HasAccess;

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
        $this->publishes([__DIR__ . '/../../config/config.php' => config_path('auth-role.php'),], 'config');
        $this->publishes([__DIR__ . '/../../database/migrations/' => base_path('/database/migrations'),], 'migrations');

        $this->app->make('router')->middleware('auth.role', HasAccess::class);

        $this->app->make('auth')->extend('eloquent', function ($app) {
            $model    = $app['config']['auth.model'];
            $provider = new EloquentUserProvider($this->app['hash'], $model);

            return new Guard($provider, $app['session.store']);
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

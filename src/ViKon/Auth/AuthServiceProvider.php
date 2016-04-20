<?php namespace ViKon\Auth;

use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Support\AggregateServiceProvider;
use ViKon\Auth\Contracts\Keeper;
use ViKon\Auth\Exception\InvalidKeeperGuardException;
use ViKon\Auth\Middleware\HasAccessMiddleware;
use ViKon\Auth\Middleware\LoginRedirectorMiddleware;
use ViKon\Auth\Middleware\PermissionMiddleware;
use ViKon\Support\SupportServiceProvider;

/**
 * Class AuthServiceProvider
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth
 */
class AuthServiceProvider extends AggregateServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function __construct($app)
    {
        $this->defer = false;

        $this->providers = [
            SupportServiceProvider::class,
        ];

        parent::__construct($app);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../../config/config.php' => config_path('vi-kon/auth.php')], 'config');
        $this->publishes([__DIR__ . '/../../database/migrations/' => base_path('database/migrations')], 'migrations');

        $this->app->make('router')->middleware('auth.has-access', HasAccessMiddleware::class);
        $this->app->make('router')->middleware('auth.login-redirector', LoginRedirectorMiddleware::class);
        $this->app->make('router')->middleware('auth.permission', PermissionMiddleware::class);

        $this->app->make('auth')->extend('vi-kon.session', function (Container $app, $name, array $config) {
            $provider = $app->make('auth')->createUserProvider($config['provider']);

            return new Guard($name, $provider, $app->make('session.store'), $app->make('request'));
        });

        $this->app->bind(Keeper::class, function (Container $app) {
            $guard = $app->make('auth')->guard();

            if (!$guard instanceof Keeper) {
                throw new InvalidKeeperGuardException();
            }

            return $guard;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            RouterAuth::class,
            Guard::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->app->singleton(RouterAuth::class, RouterAuth::class);
        $this->app->singleton(Guard::class, function (Application $app) {
            return $app->make('auth')->guard();
        });

        $this->mergeConfigFrom(__DIR__ . '/../../config/config.php', 'vi-kon.auth');
    }
}

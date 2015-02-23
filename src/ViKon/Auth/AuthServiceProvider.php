<?php namespace ViKon\Auth;

use Illuminate\Support\ServiceProvider;

/**
 * Class AuthServiceProvider
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\Auth
 */
class AuthServiceProvider extends ServiceProvider {
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('ViKon\Auth\AuthUser', 'ViKon\Auth\AuthUser');
        $this->app->singleton('ViKon\Auth\AuthRoute', 'ViKon\Auth\AuthRoute');

        $this->app->alias('ViKon\Auth\AuthUser', 'auth-user');
        $this->app->alias('ViKon\Auth\AuthRoute', 'auth-route');

        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('auth_role.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => base_path('/database/migrations'),
        ], 'migrations');

        \Event::listen('smarty-view.init', function ($config) {
            $config->set('smarty-view::plugins_path', array_merge($config->get('smarty-view::plugins_path'), [
                implode(DIRECTORY_SEPARATOR, [__DIR__, 'smarty', 'plugins'])
            ]));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return ['ViKon\Auth\AuthUser', 'ViKon\Auth\AuthRoute', 'AuthUser', 'AuthRoute'];
    }
}

<?php

namespace ViKon\Auth\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Routing\Router;
use ViKon\Auth\AuthUser;

class HasAccess implements Middleware
{
    /** @var \Illuminate\Routing\Router */
    protected $router;

    /** @var \ViKon\Auth\AuthUser */
    protected $authUser;

    public function __construct(Router $router, AuthUser $authUser)
    {
        $this->router   = $router;
        $this->authUser = $authUser;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $action = $this->router->getCurrentRoute()->getAction();

        if (isset($action['roles']) && !$this->authUser->hasRoles($action['roles']))
        {
            return redirect()->route(config('auth::error.403.route'));
        }

        return $next($request);
    }
}
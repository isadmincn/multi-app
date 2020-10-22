<?php
namespace isadmin\middleware;

use think\event\RouteLoaded;

class LoadRoute
{
    public function handle($request, \Closure $next)
    {
        app()->route->dispatch($request);

        return $next($request);
    }
}

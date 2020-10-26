<?php
namespace isadmin\multiapp\middleware;

use think\event\RouteLoaded;

class LoadRoute
{
    public function handle($request, \Closure $next)
    {
        $withRoute = app()->config->get('app.with_route', true) ? function () {
            $this->loadRoutes();
        } : null;

        app()->route->dispatch($request, $withRoute);

        return $next($request);
    }

    /**
     * 加载路由
     * @access protected
     * @return void
     */
    protected function loadRoutes(): void
    {
        // 加载路由定义
        $routePath = app()->http->getRoutePath();

        if (is_dir($routePath)) {
            $files = glob($routePath . '*.php');
            foreach ($files as $file) {
                include $file;
            }
        }

        app()->event->trigger(RouteLoaded::class);
    }

}

<?php
namespace isadmin\hookcore;

use think\Request;
use isadmin\middleware\LoadRoute;
use think\event\HttpRun;

class Http extends \think\Http
{
    /**
     * 执行应用程序
     * @param Request $request
     * @return mixed
     */
    protected function runWithRequest(Request $request)
    {
        // 监听HttpRun
        $this->app->event->trigger(HttpRun::class);

        // 加载到全局路由，适用于单应用
        if (empty($request->is_multi_app)) {
            $this->app->middleware->add(LoadRoute::class);
        }
        
        // 加载全局中间件
        $this->loadMiddleware();

        return $this->app->middleware->pipeline()
            ->send($request)
            ->then(function ($request) {
                return $this->app->route->dispatchRun($request);
            });
    }
}

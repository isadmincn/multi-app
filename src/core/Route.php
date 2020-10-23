<?php
namespace isadmin\multiapp\core;

use think\Request;

class Route extends \think\Route
{
    /**
     * 路由调度初始化
     * @param Request $request
     * @param Closure|bool $withRoute
     * @return void
     */
    public function dispatch(Request $request, $withRoute = false)
    {
        $this->request = $request;
        $this->host    = $this->request->host(true);
        $this->init();

        if ($withRoute) {
            //加载路由
            if ($withRoute instanceof \Closure) {
                $withRoute();
            }
            $this->dispatch = $this->check();
        } else {
            $this->dispatch = $this->url($this->path());
        }

        $this->dispatch->init($this->app);
    }

    /**
     * 路由调度
     *
     * @param Request $request
     * @return Response
     */
    public function dispatchRun(Request $request)
    {
        return $this->app->middleware->pipeline('route')
            ->send($request)
            ->then(function () {
                return $this->dispatch->run();
            });
    }
}

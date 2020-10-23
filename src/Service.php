<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace isadmin\multiapp;

use think\Service as BaseService;
use isadmin\multiapp\middleware\HandleMultiApp;
use isadmin\multiapp\middleware\LoadRoute;

class Service extends BaseService
{
    public function boot()
    {
        $this->app->event->listen('HttpRun', function () {
            $app = new MultiApp($this->app);

            // 加载多应用处理中间件
            $this->app->middleware->add(HandleMultiApp::class);

            // 如果是多应用请求，则把LoadRoute加入到应用中间件列表
            // 如果是单应用请求，则把LoadRoute加入到全局中间件列表
            // 系统执行全局中间件到HandleMultiApp中间件时，如果是多应用场景，则会将pipeline切换到应用中间件
            // 等应用中间件执行完毕之后，会再切入到HandleMultiApp之后的全局中间件
            $this->app->middleware->add(LoadRoute::class, $app->isMultiApp() ? 'app' : 'global');

            // 多应用时，加载应用中间件
            if ($app->isMultiApp()) {
                $app->addAppMiddlewares();
            }
        });

        $this->commands([
            'build' => command\Build::class,
            'clear' => command\Clear::class,
        ]);

        $this->app->bind([
            'think\route\Url' => Url::class,
        ]);
    }
}

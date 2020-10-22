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
namespace isadmin;

use think\Service as BaseService;
use isadmin\middleware\HandleMultiApp;
use isadmin\middleware\LoadRoute;

class Service extends BaseService
{
    public function boot()
    {
        $this->app->event->listen('HttpRun', function () {
            $app = new MultiApp($this->app);

            // 加载多应用处理中间件
            $this->app->middleware->add(HandleMultiApp::class);
            $this->app->middleware->add(LoadRoute::class);

            // 多应用时，加载应用中间件
            if ($app->is_multi_app()) {
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

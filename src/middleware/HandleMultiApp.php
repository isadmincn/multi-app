<?php
namespace isadmin\middleware;

use Closure;
use think\App;
use think\Request;

class HandleMultiApp
{
    /**
     * @var App 
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app  = $app;
    }

    /**
     * 多应用解析
     * @access public
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->is_multi_app) {
            return $next($request);
        }

        return $this->app->middleware->pipeline('app')
            ->send($request)
            ->then(function ($request) use ($next) {
                return $next($request);
            });
    }
}

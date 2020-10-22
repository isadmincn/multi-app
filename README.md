# multi-app

基于[think-multi-app](https://github.com/top-think/think-multi-app)开发，在原多应用基础上增加：
1. 路由提前加载
2. 是否Request增加是否多应用请求字段

该版本可以实现在中间件中获取请求的控制器等信息

## 安装
~~~
composer require isadmincn/multi-app
~~~

## 使用

项目安装该包之后，在```app/provider.php```添加如下
```php
<?php

return [
    'think\Http' => isadmin\hookcore\Http::class,
    'think\Route' => isadmin\hookcore\Route::class,

    // 其他依赖注入配置
];
```

即使是单应用，在```config/app.php```中的```app_express```配置为```false```的情况，也可以使用该库来实现在中间件获取控制器等信息。

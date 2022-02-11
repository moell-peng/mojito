# Mojito

Mojito 是基于 Laravel 开发的 [Mojito Admin](https://github.com/moell-peng/mojito-admin) 的服务端。

3.0 版本开始， 已将原来的项目分离为两个代码库，分别为以Vue3、Element Plus、Vite 开发的前端模板 [mojito -admin](https://github.com/moell-peng/mojito-admin)  和服务端 [mojito](https://github.com/moell-peng/mojito) 。如果是需要使用 vue2 版本，请访问 [2.0](https://github.com/moell-peng/mojito/tree/2.0) 分支。

## Mojito Admin 截图

![mojito.png](http://ww1.sinaimg.cn/large/7a679ca1gy1gtu09c4avej21590kstdb.jpg)

## 特性

* 前后端分离，提供 [Mojito Admin](https://github.com/moell-peng/mojito-admin) 前端模板
* 基于 laravel-permission 权限管理
* 基于 sanctum 鉴权 
* 提供角色，权限，用户，菜单管理等功能的API
* 多个后台支持统一管理权限，菜单和角色
* 完善的PHPUnit测试

## 要求

- Laravel  >= 7.0.0
- PHP >= 7.2.0

## 安装

首先安装laravel,并且确保你配置了正确的数据库连接。

```
composer require moell/mojito
```

然后运行下面的命令来发布资源:

```
php artisan mojito:install
```

命令执行成功会生成配置文件，数据迁移和构建SPA的文件。

修改 `app/Http/Kernel.php` ：

```
class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        ...
        'mojito.permission' => \Moell\Mojito\Http\Middleware\Authenticate::class,
    ];

    protected $middlewareGroups = [
            ...
            'api' => [
                ...
                \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            ],
        ];
}
```

执行数据迁移，数据填充

```
php artisan migrate

php artisan db:seed --class="Moell\Mojito\Database\MojitoTableSeeder"
```

后台登录的账号 `admin` , 密码 `secret`

## 路由中间件

* auth:sanctum 用于鉴权
* mojito.permission 权限验证

## mojito.php 可选配置

```php
return [
    'guards' => [
        // laravel-permission 相对应的 guard
        'admin' => [
            'model' => \Moell\Mojito\Models\AdminUser::class, //登录鉴权的模型
            'login_fields' => [	// 登录验证的字段，支持多个
                'username',
            ],
            'conditions' => [ // 登录验证的额外条件
                ['status', '=', 1]
            ]
        ]
    ],
    'route_prefix' => "api", //路由前缀
    
    'middleware' => [
        'basic' => 'api', //基础中间件

        'auth' => ['auth:sanctum'], //鉴权中间件

        'permission' => ['auth:sanctum', 'mojito.permission'] //包含权限检测的中间件
    ]
];
```

## 依赖扩展包

* spatie/laravel-permission
* laravel/sanctum

## 常见错误

* csrf token missing or incorrect ， 请修改 sanctum.php 中的 `stateful` , 如 vite 使用的 `localhost:3000 `去除即可。更多详细请访问`laravel/sanctum`文档。


## 打赏

<p>
  <img src="http://ww1.sinaimg.cn/mw690/7a679ca1ly1fvxrfnvxa4j20dw0dwdic.jpg" width="250" />
  <img src="http://ww1.sinaimg.cn/mw690/7a679ca1ly1fvxrfnr0dhj20dw0dwgp0.jpg" width="250" />
</p>

## License

Apache License Version 2.0 see http://www.apache.org/licenses/LICENSE-2.0.html

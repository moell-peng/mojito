# Mojito

Mojito 是一个基于 Laravel, Vue, Element构建的后台管理系统。

## 截图

![](http://blog-image.moell.cn/mojito-admin.jpg)

## 特征

* 可快速衍生多个后台系统
* 内置角色，权限，用户，菜单管理
* OAuth 2.0，并支持多表鉴权
* 完善的PHPUnit测试
* API 权限精确至路由，页面权限精取到按钮或链接
* 前后端分离
* 多标签页
* 前端支持多语言配置
* 简洁的布局

## 要求

- Laravel  >= 5.5.0
- Vue >= 2.5.17
- Element >= 2.4.6

## 兼容性

| Laravel  | Mojito |
| -------- | ------ |
| 5.5, 5.6 | 1.0.*  |
| 5.7,5.8      | 1.1.*        |
| 6.x       | 1.2.*        |
| 7.x       | 1.3.*        |

## 安装

首先安装laravel,并且确保你配置了正确的数据库连接。

```
composer require moell/mojito
```
> 如果出现 `random_compat` 版本造成的冲突，请手动将 `moell/mojito` 加入 composer.json ，然后执行 `composer update`  进行安装。

然后运行下面的命令来发布资源:

```
php artisan mojito:install
```

命令执行成功会生成配置文件，数据迁移和构建SPA的文件。

在`config/auth.php`中添加相应的 guards 和 providers，如下： 

```
'guards' => [
        ...
        'admin' => [
            'driver' => 'passport',
            'provider' => 'admin'
        ]
    ],

'providers' => [
        ...
        'admin' => [
            'driver' => 'eloquent',
            'model' => \Moell\Mojito\Models\AdminUser::class,
        ]
    ],
```

在 `app/Http/Kernel.php` 中 $routeMiddleware 属性添加路由中间 `oauth.providers` 和 `mojito.permission`，并将`auth`中间件替换为如下：

```
class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        // 'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth' => \SMartins\PassportMultiauth\Http\Middleware\MultiAuthenticate::class,
        'oauth.providers' => \SMartins\PassportMultiauth\Http\Middleware\AddCustomProvider::class,
        'mojito.permission' => \Moell\Mojito\Http\Middleware\Authenticate::class,
    ];
}
```

执行数据迁移，数据填充

```
php artisan migrate

php artisan db:seed --class="Moell\Mojito\Database\MojitoTableSeeder"
```

Passport 安装和配置

```
php artisan passport:install
```

执行成功后获取到相应的密码授予客户端的 ID 和 secret 并且配置到相对应的 `resources/config/index.js` :

```
export default {
  admin: {
    authorize: {
      clientId: ID,
      clientSecret: secret
    }
}
```

安装 Javscript 依赖

```shell
npm install
npm install -D vue@^2.6.6 vuex@^3.0.1 vue-router@^3.0.1 vue-i18n@^8.1.0 localforage@^1.7.2 element-ui@^2.9.1
```

将 admin.js  添加到 webpack.mix.js 

```
mix.js('resources/js/admin.js', 'public/js');
```

运行 Mix

```
#npm run watch
npm run production
```

登录



url: http://localhost/mojito#/admin/login

email: admin@gmail.com

password: secret

## 依赖开源软件

* Laravel
* Vue
* Element UI
* laravel/passport
* smartins/passport-multiauth
* spatie/laravel-permission
* orchestra/testbench

## 打赏

<p>
  <img src="http://blog-image.moell.cn/alipay.jpg" width="250" />
  <img src="http://blog-image.moell.cn/wepay.jpg" width="250" />
</p>

## License

Apache License Version 2.0 see http://www.apache.org/licenses/LICENSE-2.0.html

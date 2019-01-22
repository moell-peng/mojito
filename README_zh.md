# Mojito

Mojito 是一个基于 Laravel, Vue, Element构建的后台管理系统。

## 截图

![](http://ww1.sinaimg.cn/large/7a679ca1gy1fvrktohzfaj213x0ieq3j.jpg)

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
| 5.7      | 1.1.*  |

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

执行成功后获取到客户端的 Client ID 为 2 的 Client secret 配置到 `resources/js/config/index.js` :

```
export default {
  admin: {
    authorize: {
      clientId: 2,
      clientSecret: 'your_Client_secret'
    }
}
```

安装 Javscript 依赖

```shell
npm install
npm install -D vuex@^3.0.1 vue-router@^3.0.1 vue-i18n@^8.1.0 localforage@^1.7.2 element-ui@^2.4.6
```

将 admin.js  添加到 webpack.mix.js 

```
mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    //.js('resources/js/admin.js', 'public/js') laravel5.7+
    .js('resources/assets/js/admin.js', 'public/js')
```

运行 Mix

```
#npm run watch
npm run production
```

登录



url: http://localhost/mojito#/admin/login

email: admin@gmail.com

password: 123456

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
  <img src="http://ww1.sinaimg.cn/mw690/7a679ca1ly1fvxrfnvxa4j20dw0dwdic.jpg" width="250" />
  <img src="http://ww1.sinaimg.cn/mw690/7a679ca1ly1fvxrfnr0dhj20dw0dwgp0.jpg" width="250" />
</p>

## License

Apache License Version 2.0 see http://www.apache.org/licenses/LICENSE-2.0.html

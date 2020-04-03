# Mojito

Mojito is a backend management system based on Laravel, Vue, Element.

## Simplified Chinese

[中文README](./README_zh.md)

## Screenshot

![](http://blog-image.moell.cn/mojito-admin.jpg)

## Feature

* Quickly derive multiple backend systems
* Built-in role, permissions, user, menu management
* OAuth 2.0 and support multi-table authentication
* Complete PHPUnit test
* The minimum unit of API privilege is routing, and the minimum unit of page privilege is button or link.
* SPA
* Multi-tab page
* Support for multi-language configuration
* Simple page layout

## Requirements

- Laravel  >= 5.5.0
- Vue >= 2.5.17
- Element >= 2.4.6

## Compatibility

| Laravel  | Mojito admin |
| -------- | ------------ |
| 5.5, 5.6 | 1.0.*        |
| 5.7,5.8      | 1.1.*        |
| 6.x       | 1.2.*        |
| 7.x       | 1.3.*        |

## Installation

First install laravel and make sure you have the correct database connection configured.

```
composer require moell/mojito
```
> If there is a conflict caused by the `random_compat` version, manually add `moell/mojito` to composer.json and then `composer update` to install.

Then run the following command to publish the resource:

```
php artisan mojito:install
```

Successful execution of the command will generate configuration files, data migration, and building SPA files.

Add the appropriate guards and providers to `config/auth.php` as follows: 

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

Add `oauth.providers` and `mojito.permission` in the middle of the $routeMiddleware property in `app/Http/Kernel.php` and replace the `auth` middleware with the following:

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

Perform data migration, data filling

```
php artisan migrate

php artisan db:seed --class="Moell\Mojito\Database\MojitoTableSeeder"
```

Passport installation and configuration

```
php artisan passport:install
```

After successful execution, obtain the corresponding password to grant the client's ID and secret and configure it to the corresponding `resources/config/index.js` :

```
export default {
  admin: {
    authorize: {
      clientId: ID,
      clientSecret: secret
    }
}
```

Install JavaScript Dependencies

```shell
npm install
npm install -D vue@^2.6.6 vuex@^3.0.1 vue-router@^3.0.1 vue-i18n@^8.1.0 localforage@^1.7.2 element-ui@^2.9.1
```

Add admin.js to webpack.mix.js

```
mix.js('resources/js/admin.js', 'public/js');
```

Run Mix

```
#npm run watch
npm run production
```

Log in

url: http://localhost/mojito#/admin/login

email: admin@gmail.com

password: secret

## Dependent on open source software

* Laravel
* Vue
* Element UI
* laravel/passport
* smartins/passport-multiauth
* spatie/laravel-permission
* orchestra/testbench

## Reward

<p>
  <img src="http://blog-image.moell.cn/alipay.jpg" width="250" />
  <img src="http://blog-image.moell.cn/wepay.jpg" width="250" />
</p>

## License

Apache License Version 2.0 see http://www.apache.org/licenses/LICENSE-2.0.html

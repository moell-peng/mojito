# Mojito

Mojito is a backend management system based on Laravel, Vue, Element.

## Simplified Chinese

[中文README](./README_zh.md)

## Screenshot

![](http://ww1.sinaimg.cn/large/7a679ca1gy1fvrktohzfaj213x0ieq3j.jpg)

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
| 5.7      | 1.1.*        |

## Installation

First install laravel and make sure you have the correct database connection configured.

```
composer require moell/mojito
```

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

Install Javscript Dependencies

```shell
npm install
npm install -D vuex@^3.0.1 vue-router@^3.0.1 vue-i18n@^8.1.0 localforage@^1.7.2 element-ui@^2.4.6
```

Mix introduced admin.js

```
mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    //.js('resources/js/admin.js', 'public/js') laravel5.7+
    .js('resources/assets/js/admin.js', 'public/js')
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
	<img src="http://ww1.sinaimg.cn/mw690/7a679ca1ly1fvxrfnvxa4j20dw0dwdic.jpg" width="250" />
  <img src="http://ww1.sinaimg.cn/mw690/7a679ca1ly1fvxrfnr0dhj20dw0dwgp0.jpg" width="250" />
</p>

## License

Apache License Version 2.0 see http://www.apache.org/licenses/LICENSE-2.0.html

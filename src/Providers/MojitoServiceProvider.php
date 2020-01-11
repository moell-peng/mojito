<?php

namespace Moell\Mojito\Providers;


use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Moell\Mojito\Console\InstallCommand;
use Route;

class MojitoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerMigrations();

            $this->commands([
                InstallCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../../config/mojito.php' => config_path('mojito.php'),
            ], 'config');

            $path = version_compare(app()->version(), '5.7.0', '>=')
                ? base_path('resources/js')
                : base_path('resources/assets/js');

            $this->publishes([
                __DIR__.'/../../resources' => $path
            ], 'views');

            $this->publishes([
                __DIR__.'/../../views' => base_path('resources/views')
            ], 'views');
        }

        $this->registerRouter();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    private function registerMigrations()
    {
        $migrationsPath = __DIR__ . '/../../database/migrations/';

        $items = [
            'create_admin_table.php',
            'add_custom_field_permission_tables.php',
            'create_menu_table.php',
            'create_permission_group_table.php'
        ];

        $paths = [];
        foreach ($items as $key => $name) {
            $paths[$migrationsPath . $name] = database_path('migrations') . "/". $this->formatTimestamp($key+1) . '_' . $name;
        }

        $this->publishes($paths, 'migrations');
    }

    /**
     * @param $addition
     * @return false|string
     */
    private function formatTimestamp($addition)
    {
        return date('Y_m_d_His', time() + $addition);
    }

    /**
     * 注册路由
     *
     * @author moell
     */
    private function registerRouter()
    {
        if (strpos($this->app->version(), 'Lumen') === false && !$this->app->routesAreCached()) {
            app('router')->middleware('api')->group(__DIR__.'/../routes.php');
        } else {
            require __DIR__.'/../routes.php';
        }

        Passport::routes();

        Route::group(['middleware' => 'oauth.providers'], function () {
            Passport::routes(function ($router) {
                return $router->forAccessTokens();
            });
        });

        Passport::tokensExpireIn(Carbon::now()->addDays(config('mojito.passport_token_ttl')));

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(config('mojito.passport_refresh_token_ttl')));
    }
}
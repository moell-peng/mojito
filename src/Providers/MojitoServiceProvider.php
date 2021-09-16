<?php

namespace Moell\Mojito\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Moell\Mojito\Console\InstallCommand;

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
            $this->commands([
                InstallCommand::class,
            ]);
            if (config('permission.models.role') !== 'Moell\\Mojito\\Models\\Role') {
                // 避免每次都渲染
                $this->registerMigrations();
                $this->publishes([
                    __DIR__ . '/../../config/mojito.php' => config_path('mojito.php'),
                ], 'config');
                // 修改permission的role 确保能角色能得到菜单数据
                $this->setConfig(config_path('permission.php'), 'models.role', 'Moell\\Mojito\\Models\\Role');
            }
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
            'create_permission_group_table.php',
            'create_role_menu_table.php',
        ];

        $paths = [];
        foreach ($items as $key => $name) {
            $paths[$migrationsPath . $name] = database_path('migrations') . "/" . $this->formatTimestamp($key + 1) . '_' . $name;
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
            app('router')->middleware('api')->group(__DIR__ . '/../routes.php');
        } else {
            require __DIR__ . '/../routes.php';
        }
    }
    private function getConfig()
    {
        // 使用laravel读取这个位置文件
        $config = config('permission');
        return $config;
    }

    private function setConfig($path, $name, $value)
    {
        $config = $this->getConfig();
        Arr::set($config, $name, $value);
        // dd($path);
        // dd($config);
        file_put_contents($path, "<?php \n return " . var_export($config, true) . ";");

    }

}

<?php
namespace Stario\Icenter;

use Carbon\Carbon;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Stario\Icenter\Commands\CreateAdmin;
use Stario\Icenter\Commands\CreatePermission;
use Stario\Icenter\Commands\CreateRole;
use Stario\Icenter\Commands\IcenterSetup;
use Stario\Icenter\Contracts\Permission as PermissionContract;
use Stario\Icenter\Contracts\Role as RoleContract;
use Stario\Icenter\Services\UEditor\StorageManager;

class IcenterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(PermissionRegistrar $permissionLoader, Router $router)
    {
        // Publish the migration
        // $this->publishes([
        //     __DIR__ . '/publications/migrations/create_permission_tables.php.stub' => $this->app->databasePath() . '/migrations/1949_07_15_000000_create_permission_tables.php',
        // ], 'icenter');
        $this->publishes([
            __DIR__ . '/publications/migrations/create_icenter_tables.php.stub' => $this->app->databasePath() . '/migrations/1949_07_15_100000_create_icenter_tables.php',
        ], 'icenter');

        $this->publishes([
            __DIR__ . '/publications/seeds/IcenterSeeder.php.stub' => $this->app->databasePath() . '/seeds/IcenterSeeder.php',
        ], 'icenter');

        // $this->publishes([
        //     __DIR__ . '/publications/config/permission.php' => $this->app->configPath() . '/permission.php',
        // ], 'icenter');

        $this->publishes([
            __DIR__ . '/menu.json' => $this->app->storagePath() . '/icenter/menus/icenter.json',
        ], 'icenter');

        $this->publishes([
            __DIR__ . '/publications/static' => public_path() . '/static',
        ], 'icenter');

        $this->registerModelBindings();
        $permissionLoader->registerPermissions();

        // TODO:尝试将wesiteSeeder等所有Seeder写在一个逻辑中，php artisan ic:setup 一次播种即可
        if ($this->app->runningInConsole()) {
            $this->commands([
                IcenterSetup::class,
                CreateAdmin::class,
                CreateRole::class,
                CreatePermission::class,
            ]);
        }

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/publications/views', 'icenter');
        $this->registerRoute($router);

        Passport::routes(function ($router) {
            $router->forAccessTokens();
            $router->forPersonalAccessTokens();
            $router->forTransientTokens();
        });

        Passport::tokensExpireIn(Carbon::now()->addHours(8));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(90));
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/publications/config/permission.php',
            'permission'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/publications/config/ueditor.php',
            'ueditor'
        );
        $this->app->singleton('ueditor.storage', function ($app) {
            return new StorageManager(Storage::disk($app['config']->get('ueditor.disk', 'public')));
        });
    }

    protected function registerModelBindings()
    {
        $this->app->bind(PermissionContract::class, Models\Permission::class);
        $this->app->bind(RoleContract::class, Models\Permission::class);
    }
    protected function registerRoute($router)
    {
        if (!$this->app->routesAreCached()) {
            $router->group(array_merge(['namespace' => __NAMESPACE__], config('ueditor.route.options', [])), function ($router) {
                $router->any(config('ueditor.route.name', '/api/v1/ueditor/server'), 'Services\UEditor\UEditorController@serve');
            });
        }
    }
}

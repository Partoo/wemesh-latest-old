<?php
namespace Stario\Ihealth;

use Illuminate\Support\ServiceProvider;

class IhealthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the migration
        $this->publishes([
            __DIR__ . '/publications/migrations/1949_07_15_100001_create_patients_tables.php' => $this->app->databasePath() . '/migrations/1949_07_15_100001_create_patients_tables.php',
        ], 'icenter');
        $this->publishes([
            __DIR__ . '/publications/migrations/1949_07_15_200001_create_archives_tables.php' => $this->app->databasePath() . '/migrations/1949_07_15_200001_create_archives_tables.php',
        ], 'icenter');
        // $this->publishes([
        //     __DIR__ . '/publications/factories/WesiteFactory.php' => $this->app->databasePath() . '/factories/WesiteFactory.php',
        // ], 'icenter');
        // $this->publishes([
        //     __DIR__ . '/publications/seeds/WesiteSeeder.php' => $this->app->databasePath() . '/seeds/WesiteSeeder.php',
        // ], 'icenter');

        $this->publishes([
            __DIR__ . '/menu.json' => $this->app->storagePath() . '/icenter/menus/health.json',
        ], 'icenter');

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        // $this->loadViewsFrom(__DIR__ . '/publications/views', 'icenter');
    }

    // public function register()
    // {
    //     // $this->mergeConfigFrom(
    //     //     __DIR__ . '/publications/config/permission.php', 'permission'
    //     // );
    // }
}

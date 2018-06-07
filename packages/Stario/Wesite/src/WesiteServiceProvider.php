<?php
namespace Stario\Wesite;

use Illuminate\Support\ServiceProvider;

class WesiteServiceProvider extends ServiceProvider
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
            __DIR__ . '/publications/migrations/2017_01_01_000000_create_wesite_tables.php' => $this->app->databasePath() . '/migrations/2017_01_01_000000_create_wesite_tables.php',
        ], 'icenter');
        $this->publishes([
            __DIR__ . '/publications/factories/WesiteFactory.php' => $this->app->databasePath() . '/factories/WesiteFactory.php',
        ], 'icenter');
        $this->publishes([
            __DIR__ . '/publications/seeds/WesiteSeeder.php' => $this->app->databasePath() . '/seeds/WesiteSeeder.php',
        ], 'icenter');

        $this->publishes([
            __DIR__ . '/menu.json' => $this->app->storagePath() . '/icenter/menus/wesite.json',
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

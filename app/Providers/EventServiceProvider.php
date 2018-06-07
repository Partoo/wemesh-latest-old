<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Stario\Wesite\Models\Category;
use Stario\Wesite\Models\Post;
use Stario\Wesite\Observers\CategoryObserver;
use Stario\Wesite\Observers\PostObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Laravel\Passport\Events\AccessTokenCreated' => [
            'Stario\Icenter\Listeners\RevokeOldToken',
        ],

        'Laravel\Passport\Events\RefreshTokenCreated' => [
            'Stario\Icenter\Listeners\PruneOldToken',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Post::observe(PostObserver::class);
        Category::observe(CategoryObserver::class);
        //
    }
}

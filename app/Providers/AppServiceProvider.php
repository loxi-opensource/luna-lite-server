<?php

namespace App\Providers;

use App\Common\Events\NoticeEvent;
use App\Common\Listeners\NoticeListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            NoticeEvent::class,
            NoticeListener::class,
        );
    }
}

<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $view->with('global_setting', \Illuminate\Support\Facades\Cache::remember('global_setting', 3600, function () {
                    return \App\Models\Setting::first();
                }));
            }
        });
    }
}

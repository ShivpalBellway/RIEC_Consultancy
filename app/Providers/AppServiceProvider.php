<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\View;
use App\Models\Partner;
use App\Models\Service;
use Illuminate\Support\Facades\Schema;


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
        View::share('siteSetting', Schema::hasTable('site_settings') ? SiteSetting::first() : null);
        View::share('partners', Schema::hasTable('partners')
            ? Partner::where('status', 1)->orderBy('sort_order')->get()
            : collect());
        View::share('headerServices', Schema::hasTable('services')
            ? Service::where('status', 1)->orderBy('sort_order')->get(['id', 'title', 'slug'])
            : collect());
    }
}

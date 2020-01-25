<?php

namespace App\Providers;

use App\Services\AlertsService;
use App\Services\CustomersService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Mix manifest hiba miatt kell egy ilyen
        if (array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
            $this->app->bind('path.public', function () {
                return base_path() . '/../public_html';
            });
        }

        Carbon::setLocale(config('app.locale'));
        setlocale(LC_ALL, 'hu_HU');

        $this->app->bind('CustomersService', CustomersService::class);
        $this->app->bind('AlertsService', AlertsService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}

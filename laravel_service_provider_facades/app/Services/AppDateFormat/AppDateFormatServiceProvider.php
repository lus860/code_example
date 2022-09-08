<?php


namespace App\Services\AppDateFormat;

use Illuminate\Support\ServiceProvider;
use App\Services\AppDateFormat\AppDateFormat;

class AppDateFormatServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton("AppDateFormat", function($app) {
            return new AppDateFormat();
        });
    }
}

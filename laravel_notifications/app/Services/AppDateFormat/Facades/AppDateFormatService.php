<?php


namespace App\Services\AppDateFormat\Facades;


use Illuminate\Support\Facades\Facade;

class AppDateFormatService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "AppDateFormat";
    }
}

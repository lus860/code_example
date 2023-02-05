<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Api Metric
Route::post('api/custom_units/add', 'Api\MetricController@store');
Route::get('api/custom_units', 'Api\MetricController@index');
Route::put('api/custom_units/edit', 'Api\MetricController@update');
Route::delete('api/custom_units/remove', 'Api\MetricController@destroy');

// Api Token
Route::get('api_toke', 'CompanyController@getApiToken')->name('api_toke')->middleware('settings.access');
Route::get('api_token_generate', 'CompanyController@apiTokenGenerate')->name('api_token_generate')->middleware('settings.access');
Route::get('api_token_regenerate', 'CompanyController@apiTokenRegenerate')->name('api_token_regenerate')->middleware('settings.access');

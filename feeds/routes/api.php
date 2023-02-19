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
// Feeds
Route::get('feeds_count', 'FeedController@feedsCount');
Route::get('feeds', 'FeedController@index');
Route::get('feeds_change_status/{id}', 'FeedController@changeStatus');


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
// Google
Route::get('check_google_calendar_connection', 'GoogleController@checkCalendarConnection')->name('google.checkCalendarConnection');
Route::post('connect_google_calendar', 'GoogleController@authUrl')->name('google.connectGoogleCalendar');
Route::post('add_google_calendar_token', 'GoogleController@addCalendarToken')->name('google.addCalendarToken');
Route::post('disconnect_google_calendar', 'GoogleController@removeCalendarToken')->name('google.removeCalendarToken');



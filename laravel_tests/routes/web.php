<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users_store');
Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users_update');
Route::get('/test_session_cookie', [App\Http\Controllers\UserController::class, 'forTestSessionCookie'])->name('test_session_cookie');


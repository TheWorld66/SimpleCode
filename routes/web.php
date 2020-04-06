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
    return Redirect::to("login");
});

Route::get('login', 'LoginController@index');
Route::any('dashboard', 'LoginController@dashboardIndex');
Route::put('handleServer', 'LoginController@handleServer')->name('handle.server');
Route::post('handleServer', 'LoginController@handleServer');
Route::delete('handleServerDelete', 'LoginController@handleServerDelete')->name('handle.server.delete');
Route::get('logout', 'LoginController@logout');

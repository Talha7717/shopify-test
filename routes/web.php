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

Auth::routes();
Route::get('/', [App\Http\Controllers\ViewController::class, 'welcome'])->name('welcome');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');
Route::post('/product_store', [App\Http\Controllers\HomeController::class, 'product_store'])->name('product_store');
Route::get('/order_list', [App\Http\Controllers\HomeController::class, 'order_list'])->name('order_list');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

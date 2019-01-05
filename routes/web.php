<?php

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

Route::get('/', 'HomeController@index');
Route::get('/fingervault/login/{token}', 'Auth\FingervaultController@checkLogin')->name('fingervault.login');
Route::get('/fingervault/token/generate', 'Auth\FingervaultController@generateUserToken')->name('fingervault.token.generate');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'ProfileController@index')->name('profile');

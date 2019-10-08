<?php

use Illuminate\Support\Facades\Route;

// Authentication Routes...
Route::prefix('auth')->namespace('Auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register');
});

//Auth::routes(['register' => false]);
Route::get('/', function () {
    return view('welcome');
});

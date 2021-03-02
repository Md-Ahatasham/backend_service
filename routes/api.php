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




    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group(['middleware' => ['jwt.verify']], function() {
        Route::get('products', 'ProductController@index');
        Route::get('products/{id}', 'ProductController@edit');
        Route::post('products', 'ProductController@store');
        Route::put('products/{id}', 'ProductController@update');
        Route::delete('products/{id}', 'ProductController@destroy');
        Route::post('logout', 'AuthController@logout');
    });

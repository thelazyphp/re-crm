<?php

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

Route::prefix('/v1')->namespace('v1')->group(function () {

    Route::prefix('/auth')->group(function () {

        Route::post('/register', 'AuthController@register');
        Route::post('/login', 'AuthController@login');
        Route::post('/refresh-token', 'AuthController@refreshToken');
        Route::match(['get', 'post'], '/logout', 'AuthController@logout');

    });

    Route::resource('/users', 'UserController')->only(['show', 'update', 'destroy']);
    Route::match(['get', 'post'], '/search-group', 'GroupController@search');
    Route::get('/groups/total', 'GroupController@total');
    Route::resource('/groups', 'GroupController');
    Route::get('/favorites/total', 'PostController@favoritesTotal');
    Route::get('/favorites', 'PostController@favorites');
    Route::post('/favorites/{post}', 'PostController@favorite');
    Route::delete('/favorites/{post}', 'PostController@unfavorite');
    Route::resource('/posts', 'PostController')->only(['index', 'show']);
    Route::resource('/tags', 'TagController')->only(['index', 'store', 'destroy']);
    Route::match(['get', 'post'], '/start-parser', 'ParserController@start');

});

<?php

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

Route::prefix('v1')->group(function () {

    // Logs routes

    Route::resource(
        'logs', 'Api\V1\LogController'
    )->only(['index']);



    // Users routes

    Route::resource(
        'users', 'Api\V1\UserController'
    );



    // Bots routes

    Route::get(
        '/bots/{bot}/run', 'Api\V1\BotController@run'
    );

    Route::post(
        '/bots/{bot}/run', 'Api\V1\BotController@run'
    );

    Route::resource(
        'bots', 'Api\V1\BotController'
    )->only(['index', 'show', 'update']);



    // Auth routes

    Route::post(
        '/auth/login', 'Api\V1\AuthController@login'
    );

    Route::post(
        '/auth/logout', 'Api\V1\AuthController@logout'
    );

    Route::post(
        '/auth/register', 'Api\V1\AuthController@register'
    );

    Route::post(
        '/auth/refresh-token', 'Api\V1\AuthController@refreshToken'
    );



    // CRM routes

    Route::post(
        '/crm/update', 'Api\V1\CrmController@update'
    );

    Route::get(
        '/crm/update-statuses/', 'Api\V1\CrmUpdateStatusController@index'
    );

    Route::get(
        '/crm/update-statuses/{crmUpdateStatus}', 'Api\V1\CrmUpdateStatusController@show'
    );



    // Feeds routes

    Route::post(
        '/feeds/{feed}/generate', 'Api\V1\FeedController@generate'
    );

    Route::resource(
        '/feeds', 'Api\V1\FeedController'
    )->only(['index', 'show']);

    Route::get(
        '/feeds/{feed}/generate-statuses/', 'Api\V1\FeedGenerateStatusController@index'
    );

    Route::get(
        '/feeds/{feed}/generate-statuses/{feedGenerateStatus}', 'Api\V1\FeedGenerateStatusController@show'
    );

});

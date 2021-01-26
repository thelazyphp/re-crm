<?php

use App\Http\Controllers\API\CurrentUserController;
use App\Http\Controllers\API\ImageController;
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

Route::apiResource('images', ImageController::class);

Route::middleware('auth:sanctum')->get('/user', [CurrentUserController::class, 'show']);
Route::middleware('auth:sanctum')->patch('/user', [CurrentUserController::class, 'update']);
Route::middleware('auth:sanctum')->patch('/user/profile-photo', [CurrentUserController::class, 'updateProfilePhoto']);
Route::middleware('auth:sanctum')->delete('/user/profile-photo', [CurrentUserController::class, 'destroyProfilePhoto']);

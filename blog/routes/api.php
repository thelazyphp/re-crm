<?php

use App\Http\Controllers\API\Admin\ResourceController;
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

Route::prefix('admin')->group(function () {
    Route::get('/{resource}', [ResourceController::class, 'index']);
    Route::post('/{resource}', [ResourceController::class, 'store']);
    Route::get('/{resource}/{resourceId}', [ResourceController::class, 'show']);
    Route::put('/{resource}/{resourceId}', [ResourceController::class, 'update']);
    Route::delete('/{resource}/{resourceId}', [ResourceController::class, 'delete']);
});

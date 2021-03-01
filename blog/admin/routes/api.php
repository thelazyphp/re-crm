<?php

use Admin\Admin;
use Admin\Http\Controllers\CreateFieldController;
use Admin\Http\Controllers\ResourceController;
use Admin\Http\Controllers\UpdateFieldController;
use Illuminate\Support\Facades\Route;

Route::prefix(Admin::path().'/api')
    ->middleware('api')
    ->group(function () {
        Route::get('/resources/{resourceName}/create-fields', [CreateFieldController::class, 'index']);
        Route::get('/resources/{resourceName}/{resourceId}/update-fields', [UpdateFieldController::class, 'index']);
        Route::get('/resources/{resourceName}', [ResourceController::class, 'index']);
        Route::post('/resources/{resourceName}', [ResourceController::class, 'store']);
        Route::get('/resources/{resourceName}/{resourceId}', [ResourceController::class, 'show']);
        Route::put('/resources/{resourceName}/{resourceId}', [ResourceController::class, 'update']);
        Route::delete('/resources/{resourceName}/{resourceId}', [ResourceController::class, 'destroy']);
    });

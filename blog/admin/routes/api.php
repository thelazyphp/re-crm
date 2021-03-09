<?php

use Admin\Admin;
use Admin\Http\Controllers\CreateFieldController;
use Admin\Http\Controllers\ResourceController;
use Admin\Http\Controllers\UpdateFieldController;
use Illuminate\Support\Facades\Route;

Route::prefix(Admin::path().'/api')
    ->middleware('api')
    ->group(function () {
        Route::get('/resources/{resourceKey}/create-fields', [CreateFieldController::class, 'index']);
        Route::get('/resources/{resourceKey}/{resourceId}/update-fields', [UpdateFieldController::class, 'index']);
        Route::get('/resources/{resourceKey}', [ResourceController::class, 'index']);
        Route::post('/resources/{resourceKey}', [ResourceController::class, 'store']);
        Route::get('/resources/{resourceKey}/{resourceId}', [ResourceController::class, 'show']);
        Route::put('/resources/{resourceKey}/{resourceId}', [ResourceController::class, 'update']);
        Route::delete('/resources/{resourceKey}/{resourceId}', [ResourceController::class, 'destroy']);
    });

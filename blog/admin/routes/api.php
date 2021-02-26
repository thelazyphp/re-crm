<?php

use Admin\Admin;
use Admin\Http\Controllers\CreateFieldController;
use Admin\Http\Controllers\UpdateFieldController;
use Illuminate\Support\Facades\Route;

Route::prefix(Admin::path().'/api')
    ->middleware('api')
    ->group(function () {
        Route::get('/resources/{resourceName}/create-fields', [CreateFieldController::class, 'index']);
        Route::get('/resources/{resourceName}/{resourceId}/update-fields', [UpdateFieldController::class, 'index']);
    });

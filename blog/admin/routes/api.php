<?php

use Illuminate\Support\Facades\Route;
use Admin\Admin;
use Admin\Http\Controllers\CreateFieldsController;

Route::prefix(Admin::path().'/api')
    ->middleware('api')
    ->group(function () {
        Route::get('/resources/{resource}/create-fields', CreateFieldsController::class);
    });

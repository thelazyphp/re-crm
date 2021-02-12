<?php

use Illuminate\Support\Facades\Route;
use Admin\Admin;
use Admin\Http\Controllers\AppController;

Route::prefix(Admin::path())
    ->middleware('web')
    ->group(function () {
        Route::get('/{path?}', AppController::class)->where('path', '.*');
    });

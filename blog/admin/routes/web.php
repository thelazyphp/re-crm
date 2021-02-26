<?php

use Admin\Admin;
use Illuminate\Support\Facades\Route;

Route::prefix(Admin::path())
    ->middleware('web')
    ->group(function () {
        Route::view('/{path?}', 'admin::app')->where('path', '.*');
    });

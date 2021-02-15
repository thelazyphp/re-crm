<?php

namespace Admin\Http\Controllers;

use Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return view('admin::app', [
            'config' => Admin::toJSON(),
        ]);
    }
}

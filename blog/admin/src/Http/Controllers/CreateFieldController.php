<?php

namespace Admin\Http\Controllers;

use Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateFieldController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $resource)
    {
        $resource = Admin::resourceByName($resource);

        if (! $resource) {
            abort(404);
        }

        return response()->json(
            (new $resource($resource::newModel()))->createFields($request)
        );
    }
}

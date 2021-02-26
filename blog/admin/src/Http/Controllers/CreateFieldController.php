<?php

namespace Admin\Http\Controllers;

use Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateFieldController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceName
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $resourceName)
    {
        $resource = Admin::findResourceByName($resourceName);

        if (! $resource) {
            abort(404);
        }

        $resource = new $resource;

        return response()->json([
            'fields' => $resource->getCreateFields($request)->values()->all(),
        ]);
    }
}

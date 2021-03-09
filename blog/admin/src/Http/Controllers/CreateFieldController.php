<?php

namespace Admin\Http\Controllers;

use Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateFieldController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceKey
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $resourceKey)
    {
        $resource = Admin::findResourceByKey($resourceKey);

        if (! $resource) {
            abort(404);
        }

        $resource = new $resource;

        return response()->json([
            'fields' => $resource->getCreateFields($request)->values()->all(),
        ]);
    }
}

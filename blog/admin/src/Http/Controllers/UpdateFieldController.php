<?php

namespace Admin\Http\Controllers;

use Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateFieldController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceKey
     * @param  mixed  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $resourceKey, $resourceId)
    {
        $resource = Admin::findResourceByKey($resourceKey);

        if (! $resource) {
            abort(404);
        }

        return response()->json([
            'fields' => $resource::forModel($resource::$model::findOrFail($resourceId))->getUpdateFields($request)->values()->all(),
        ]);
    }
}

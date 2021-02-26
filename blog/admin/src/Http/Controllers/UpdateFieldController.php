<?php

namespace Admin\Http\Controllers;

use Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpdateFieldController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceName
     * @param  mixed  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $resourceName, $resourceId)
    {
        $resource = Admin::findResourceByName($resourceName);

        if (! $resource) {
            abort(404);
        }

        $model = $resource::$model::findOrFail($resourceId);

        $resource = $resource::forModel($model);

        return response()->json([
            'fields' => $resource->getUpdateFields($request)->values()->all(),
        ]);
    }
}

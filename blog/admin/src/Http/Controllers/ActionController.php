<?php

namespace Admin\Http\Controllers;

use Admin\ActionFields;
use Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActionController extends Controller
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

        return response()->json([
            'actions' => (new $resource)->actions($request),
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceKey
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, $resourceKey)
    {
        $resource = Admin::findResourceByKey($resourceKey);

        if (! $resource) {
            abort(404);
        }

        $resource = new $resource;

        $fields = new ActionFields;

        $models = $resource->model()
            ->query()
            ->where($resource->model()->getKeyName(), explode(',', $request->query('resources')))
            ->get();
    }
}

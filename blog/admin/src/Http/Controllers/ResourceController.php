<?php

namespace Admin\Http\Controllers;

use Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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

        $models = $resource::$model::all();

        return response()->json([
            'resources' => $models->mapInto($resource)->map->serializeForIndex($request),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceName
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $resourceName)
    {
        $resource = Admin::findResourceByName($resourceName);

        if (! $resource) {
            abort(404);
        }

        $resource = new $resource;

        $resource->fill($request);

        $resource->model()->save();

        return response()->json([
            'resource' => $resource->model(),
            'redirectTo' => "/resources/{$resource::name()}",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceName
     * @param  mixed  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $resourceName, $resourceId)
    {
        $resource = Admin::findResourceByName($resourceName);

        if (! $resource) {
            abort(404);
        }

        $model = $resource::$model::findOrFail($resourceId);

        $resource = $resource::forModel($model);

        return response()->json([
            'resource' => $resource->serializeForDetail($request),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceName
     * @param  mixed  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resourceName, $resourceId)
    {
        $resource = Admin::findResourceByName($resourceName);

        if (! $resource) {
            abort(404);
        }

        $model = $resource::$model::findOrFail($resourceId);

        $resource = $resource::forModel($model);

        $resource->fill($request, true);

        $resource->model()->save();

        return response()->json([
            'resource' => $resource->model(),
            'redirectTo' => "/resources/{$resource::name()}",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceName
     * @param  mixed  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $resourceName, $resourceId)
    {
        $resource = Admin::findResourceByName($resourceName);

        if (! $resource) {
            abort(404);
        }

        $model = $resource::$model::findOrFail($resourceId);

        $model->delete();

        return response()->json([
            'redirectTo' => "/resources/{$resource::name()}",
        ], 204);
    }
}

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
     * @param  string  $resourceKey
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $resourceKey)
    {
        $resource = Admin::findResourceByKey($resourceKey);

        if (! $resource) {
            abort(404);
        }

        $query = $resource::$model::query();

        if ($request->filled('sort')) {
            $query->orderBy(
                $request->sort,
                $request->input('order', 'asc')
            );
        }

        return response()->json([
            'resources' => $query->get()->mapInto($resource)->map->serializeForIndex($request),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceKey
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $resourceKey)
    {
        $resource = Admin::findResourceByKey($resourceKey);

        if (! $resource) {
            abort(404);
        }

        $resource = new $resource;

        $resource->fill($request);

        $resource->model()->save();

        return response()->json([
            'resource' => $resource->model(),
            'redirectTo' => "/resources/{$resource::key()}",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceKey
     * @param  mixed  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $resourceKey, $resourceId)
    {
        $resource = Admin::findResourceByKey($resourceKey);

        if (! $resource) {
            abort(404);
        }

        return response()->json([
            'resource' => $resource::forModel($resource::$model::findOrFail($resourceId))->serializeForShow($request),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceKey
     * @param  mixed  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resourceKey, $resourceId)
    {
        $resource = Admin::findResourceByKey($resourceKey);

        if (! $resource) {
            abort(404);
        }

        $model = $resource::$model::findOrFail($resourceId);

        $resource = $resource::forModel($model);

        $resource->fill($request, true);

        $resource->model()->save();

        return response()->json([
            'resource' => $resource->model(),
            'redirectTo' => "/resources/{$resource::key()}",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resourceKey
     * @param  mixed  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $resourceKey, $resourceId)
    {
        $resource = Admin::findResourceByKey($resourceKey);

        if (! $resource) {
            abort(404);
        }

        $model = $resource::$model::findOrFail($resourceId);

        $model->delete();

        return response()->json([
            'redirectTo' => "/resources/{$resource::key()}",
        ], 204);
    }
}

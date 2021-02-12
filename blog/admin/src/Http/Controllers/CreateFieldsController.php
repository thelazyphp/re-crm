<?php

namespace Admin\Http\Controllers;

use Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateFieldsController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, $resource)
    {
        $resource = Admin::resourceByUriKey($resource);

        if (! $resource) {
            abort(404);
        }

        $resource = new $resource($resource::newModel());

        $fields = $resource->createFields($request)->map(function ($field) use ($request) {
            return $field->serializeToJSON($request);
        });

        return response()->json([
            'resource' => $resource->serializeToJSON($request),
            'fields' => $fields,
        ]);
    }
}

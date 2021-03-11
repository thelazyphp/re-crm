<?php

namespace Admin\Http\Controllers;

use Admin\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DestroyResourceController extends Controller
{
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

        $resource::$model::destroy(
            explode(
                ',', $request->query('resources')
            )
        );

        return response(null, 204);
    }
}

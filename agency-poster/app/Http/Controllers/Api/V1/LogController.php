<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'type' => [
                'string',
                Rule::in(['bots', 'jandex_geolocations'])
            ]
        ]);

        $type = $request->has('type') ? $request->type : 'bots';
        $logs = [];

        if ($type == 'jandex_geolocations') {
            foreach (Storage::disk('logs')->files('jandex_geolocations') as $path) {
                $logs[basename($path)] = str_replace("\n", "\r\n", Storage::disk('logs')->get($path));
            }
        } else {
            foreach (Storage::disk('logs')->directories('bots') as $dir) {
                foreach (Storage::disk('logs')->files($dir) as $path) {
                    $logs[basename($dir)][basename($path)] = str_replace("\n", "\r\n", Storage::disk('logs')->get($path));
                }
            }
        }

        return $logs;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Import as ImportResource;
use App\Models\Import;
use App\Services\PropsService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ImportResource::collection(
            Import::paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'props' => ['required', 'file', 'mimes:xlsx', 'max:51200'],
        ]);

        $import = Import::create([
            'user_id' => $request->user()->id,
            'file_name' => $request->file('props')->getClientOriginalName(),
            'file_size' => $request->file('props')->getSize(),
        ]);

        ignore_user_abort(true);
        ob_start();

        $url = route(
            'imports.show',
            ['import' => $import]
        );

        header("Connection: close", true, 202);
        header("Content-Location: {$url}");

        ob_end_flush();
        ob_flush();
        flush();

        if (session_id()) {
            session_write_close();
        }

        (new PropsService())->import(
            $request->file('props')
        );

        $import->update(['status' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function show(Import $import)
    {
        return new ImportResource($import);
    }
}

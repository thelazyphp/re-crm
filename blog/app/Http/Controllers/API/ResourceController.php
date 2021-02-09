<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resource
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $resource)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resource
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $resource)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resource
     * @param  int  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $resource, $resourceId)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resource
     * @param  int  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $resource, $resourceId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $resource
     * @param  int  $resourceId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $resource, $resourceId)
    {
        //
    }
}

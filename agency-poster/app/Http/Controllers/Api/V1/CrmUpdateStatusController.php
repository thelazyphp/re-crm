<?php

namespace App\Http\Controllers\Api\V1;

use App\CrmUpdateStatus;
use App\Http\Controllers\Controller;

class CrmUpdateStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CrmUpdateStatus::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CrmUpdateStatus  $crmUpdateStatus
     * @return \Illuminate\Http\Response
     */
    public function show(CrmUpdateStatus $crmUpdateStatus)
    {
        return response()
            ->json($crmUpdateStatus)
            ->header('Retry-After', '5');
    }
}

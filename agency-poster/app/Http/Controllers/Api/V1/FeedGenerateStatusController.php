<?php

namespace App\Http\Controllers\Api\V1;

use App\FeedGenerateStatus;
use App\Http\Controllers\Controller;

class FeedGenerateStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $feedId
     * @return \Illuminate\Http\Response
     */
    public function index($feedId)
    {
        return FeedGenerateStatus::where('feed_id', $feedId)->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $feedId
     * @param  int  $feedGenerateStatusId
     * @return \Illuminate\Http\Response
     */
    public function show($feedId, $feedGenerateStatusId)
    {
        $feedGenerateStatus = FeedGenerateStatus::where('feed_id', $feedId)
            ->where('id', $feedGenerateStatusId)
            ->first();

        return response()
            ->json($feedGenerateStatus)
            ->header('Retry-After', '5');
    }
}

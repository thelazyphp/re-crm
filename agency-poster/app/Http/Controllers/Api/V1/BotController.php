<?php

namespace App\Http\Controllers\Api\V1;

use App\Bot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('run');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Bot::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bot  $bot
     * @return \Illuminate\Http\Response
     */
    public function show(Bot $bot)
    {
        return $bot;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bot  $bot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bot $bot)
    {
        $this->validate($request, [
            'active' => [
                'required',
                'boolean',
            ],
        ]);

        $bot->active = $request->active;
        $bot->save();

        return $bot;
    }

    /**
     * @param  \App\Bot  $bot
     * @return \Illuminate\Http\Response
     */
    public function run(Bot $bot)
    {
        $bot->run();

        // return response()->json(['status' => true], 202);
    }
}

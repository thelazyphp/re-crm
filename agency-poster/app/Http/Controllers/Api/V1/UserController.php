<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller
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
        return User::all();
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
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::findOrFail(
            $id == 'current'
                ? Auth::id()
                : $id
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($id != 'current') {
            return response()->json(['status' => false], 403);
        }

        $user = $request->user();

        $this->validate($request, [
            'first_name' => 'string|max:191',
            'last_name' => 'string|max:191',
            'email' => [
                'string',
                'max:191',
                'email',
                Rule::unique('users')->ignore($user),
            ],
            'password' => 'required_with:new_password|string|password:api',
            'new_password' => 'string|min:8|confirmed',
        ]);

        if ($request->has('first_name')) {
            $user->first_name = $request->first_name;
        }

        if ($request->has('last_name')) {
            $user->last_name = $request->last_name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id != 'current') {
            return response()->json(['status' => false], 403);
        }

        auth()->user()->delete();

        return response('', 204);
    }
}

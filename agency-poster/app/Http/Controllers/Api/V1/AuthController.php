<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Laravel\Passport\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api'])->only('logout');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $client = Client::where('password_client', true)
            ->first();

        if (! $client) {
            $data = [
                'status' => false,
                'message' => 'Password grant client is not found!',
            ];

            return response()->json($data, 500);
        }

        return app()->handle(
            Request::create(
                config('app.url').'/api/v1/oauth/token', 'POST', [
                    'grant_type' => 'password',
                    'scope' => '*',
                    'username' => $request->username,
                    'password' => $request->password,
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                ]
            )
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->json(['status' => true]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refreshToken(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required|string',
        ]);

        $client = Client::where('password_client', true)
            ->first();

        if (! $client) {
            $data = [
                'status' => false,
                'message' => 'Password grant client is not found!',
            ];

            return response()->json($data, 500);
        }

        return app()->handle(
            Request::create(
                config('app.url').'/api/v1/oauth/token', 'POST', [
                    'grant_type' => 'refresh_token',
                    'scope' => '*',
                    'refresh_token' => $request->refresh_token,
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                ]
            )
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|string|max:191|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['status' => true]);
    }
}

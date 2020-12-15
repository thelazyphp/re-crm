<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (
            ! $user
            || ! Hash::check($request->password, $user->password)
        ) {
            throw ValidationException::withMessages([
                'email' => [
                    __('api.errors.auth'),
                ],
            ]);
        }

        return response()->json([
            'access_token' => $user->createToken($user->email)->accessToken,
        ]);
    }
}

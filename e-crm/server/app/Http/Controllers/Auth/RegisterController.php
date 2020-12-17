<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $team = new Team();

        $team->owner()->associate($user)->members()->save($user);

        if ($user instanceof MustVerifyEmail) {
            //
        }

        return response()->json([
            'access_token' => $user->createToken($user->email)->accessToken,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\RegisterUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];

        $request->validate(
            $rules,
            trans('api.errors.validation')
        );

        $user = User::where('email', $request->email)->first();

        if (! $user
            || ! Hash::check($request->password, $user->password)
        ) {
            ValidationException::withMessages([
                'email' => [
                    trans('api.errors.credentials'),
                ],
            ]);
        }

        return response()->json([
            'access_token' => $user->createToken(config('app.name'))->accessToken,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()
            ->token()
            ->revoke();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actions\RegisterUser  $registrator
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request, RegisterUser $registrator)
    {
        $user = $registrator->register($request->all());

        return response()->json([
            'access_token' => $user->createToken(config('app.name'))->accessToken,
        ]);
    }
}

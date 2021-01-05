<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($request->only('username', 'password'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $user = $this->createUser($request->input());

        Auth::login($user);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function acceptInvitation(Request $request, $token)
    {
        Validator::make([
            'token' => $token,
        ], [
            'token' => 'required|string|exists:invitations',
        ])->validate();

        $invitation = Invitation::where('token', $token)->first();

        $user = $this->createUser($request->input());

        $user->orgs()->attach($invitation->org);

        $invitation->delete();

        Auth::login($user);
    }

    /**
     * @param  array  $input
     * @return \App\Models\User
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function createUser(array $input)
    {
        Validator::make($input, [
            'name' => 'required|string|min:2|max:255',
            'username' => 'required|string|regex:/[a-z0-9._-]{2,255}/i|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ])->validate();

        $input['password'] = Hash::make($input['password']);

        return User::create($input);
    }
}

<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only(
            'email',
            'password',
        );

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
        } else {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }
}

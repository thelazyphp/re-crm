<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserApiController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showCurrent(Request $request)
    {
        return $request->user();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCurrent(Request $request)
    {
        $user = $request->user();

        $input = $request->validate([
            'name'     => 'string|min:2|max:255',
            'username' => ['string', 'regex:/[a-z0-9._-]{2,255}/i', Rule::unique('users')->ignore($user)],
        ]);

        $user->update($input);

        return $user;
    }
}

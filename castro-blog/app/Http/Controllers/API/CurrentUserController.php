<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;

class CurrentUserController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(Request $request)
    {
        $user = $request->user();

        //

        return new UserResource($user);
    }

    public function updateProfilePhoto(Request $request)
    {
        $user = $request->user();

        //

        return new UserResource($user);
    }

    public function destroyProfilePhoto(Request $request)
    {
        $user = $request->user();

        //

        return new UserResource($user);
    }
}

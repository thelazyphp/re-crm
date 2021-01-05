<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Org;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrgInvitationApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Org $org)
    {
        $request->validate([
            'page'     => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
        ]);

        return $org->invitations()->paginate($request->input('per_page', 25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Org $org)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        do {
            $token = Str::random(64);
        } while (Invitation::where('token', $token)->exists());

        return Invitation::create([
            'org_id' => $org->id,
            'inviter_id' => auth()->id(),
            'email' => $request->email,
            'token' => $request->token,
        ]);
    }
}

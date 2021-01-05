<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Org;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrgApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Validator::make($request->query(), [
            'page'      => 'integer|min:1',
            'per_page'  => 'integer|min:1|max:100',
        ])->validate();

        return Org::paginate($request->query('per_page', 25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
        ]);

        $org = Org::create([
            'owner_id'  => auth()->id(),
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
        ]);

        auth()->user()
            ->orgs()
            ->attach($org);

        return $org->load('owner');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function show(Org $org)
    {
        return $org->load('owner');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Org $org)
    {
        $input = $request->validate([
            'name' => 'string|min:2|max:255',
        ]);

        $org->update($input);

        return $org->load('owner');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function destroy(Org $org)
    {
        $org->members()->detach();

        $org->delete();

        return response('', 204);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function invitations(Request $request, Org $org)
    {
        Validator::make($request->query(), [
            'page'      => 'integer|min:1',
            'per_page'  => 'integer|min:1|max:100',
        ])->validate();

        return $org->invitations()->paginate($request->query('per_page', 25));
    }

    /**
     * @param  \App\Models\Org  $org
     * @param  \App\Models\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function showInvitation(Org $org, Invitation $invitation)
    {
        return $invitation->load(['org', 'inviter']);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function createInvitation(Request $request, Org $org)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        do {
            $token = Str::random(34);
        } while (Invitation::where('token', $token)->first());

        return Invitation::create([
            'org_id' => $org->id,
            'inviter_id' => auth()->id(),
            'email' => $request->email,
            'token' => $request->token,
        ]);
    }

    /**
     * @param  \App\Models\Org  $org
     * @param  \App\Models\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function destroyInvitation(Org $org, Invitation $invitation)
    {
        $invitation->delete();

        return response('', 204);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function members(Request $request, Org $org)
    {
        Validator::make($request->query(), [
            'role'      => 'string|in:owner,admin,memeber',
            'page'      => 'integer|min:1',
            'per_page'  => 'integer|min:1|max:100',
        ])->validate();

        $members = $org->members();

        if ($request->query('role')) {
            $members->wherePivot('role', $request->query('role'));
        }

        return $members->paginate($request->query('per_page', 25));
    }

    /**
     * @param  \App\Models\Org  $org
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function checkMembership(Org $org, User $user)
    {
        return $org->members->contains($user)
            ? response('', 204)
            : response('', 404);
    }

    /**
     * @param  \App\Models\Org  $org
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function showMembership(Org $org, User $user)
    {
        if (! $org->members->contains($user)) {
            abort(404);
        }

        return $org->members()
            ->where('id', $user->id)
            ->get()
            ->membership;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateMembership(Request $request, Org $org, User $user)
    {
        if (! $org->members->contains($user)) {
            abort(404);
        }

        $input = $request->validate([
            'role' => 'string|in:admin,member',
        ]);

        $org->members()->updateExistingPivot($user, $input);

        return response('', 204);
    }

    /**
     * @param  \App\Models\Org  $org
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroyMembership(Org $org, User $user)
    {
        if (! $org->members->contains($user)) {
            abort(404);
        }

        $org->members()->detach($user);

        return response('', 204);
    }
}

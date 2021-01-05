<?php

namespace App\Http\Controllers;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Validator::make(request()->query(), [
            'page'      => 'integer|min:1',
            'per_page'  => 'integer|min:1|max:100',
        ])->validate();

        return Org::paginate(
            request()->query('per_page', 25)
        );
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
     * @param  \App\Models\Org  $org
     * @return \Illuminate\Http\Response
     */
    public function members(Org $org)
    {
        Validator::make(request()->query(), [
            'page'      => 'integer|min:1',
            'per_page'  => 'integer|min:1|max:100',
        ])->validate();

        return $org->members()->paginate(
            request()->query('per_page', 25)
        );
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateMembership(Request $request, Org $org, User $user)
    {
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
        $org->members()->detach($user);

        return response('', 204);
    }
}

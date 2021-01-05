<?php

namespace App\Http\Controllers;

use App\Models\Org;
use App\Models\User;
use Illuminate\Http\Request;

class OrgMemberApiController extends Controller
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
            'role'     => 'string|in:owner|admin|member',
            'page'     => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
        ]);

        $members = $org->members();

        if ($request->has('role')) {
            $members->wherePivot('role', $request->role);
        }

        return $members->paginate($request->input('per_page', 25));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Org  $org
     * @param  \App\Models\User  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Org $org, User $member)
    {
        return response('', 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Org  $org
     * @param  \App\Models\User  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Org $org, User $member)
    {
        $org->members()->detach($member);

        return response('', 204);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Org  $org
     * @param  \App\Models\User  $member
     * @return \Illuminate\Http\Response
     */
    public function showMembership(Org $org, User $member)
    {
        return $org->members()
            ->where('id', $member->id)
            ->get()
            ->membership;
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Org  $org
     * @param  \App\Models\User  $member
     * @return \Illuminate\Http\Response
     */
    public function updateMembership(Request $request, Org $org, User $member)
    {
        $input = $request->validate([
            'role' => 'string|in:admin|member',
        ]);

        $org->members()->updateExistingPivot($member, $input);

        return response('', 204);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Org;
use Illuminate\Http\Request;
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
        $request->validate([
            'page'     => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
        ]);

        return Org::paginate($request->input('per_page', 25));
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
            'owner_id' => auth()->id(),
            'name'     => $request->name,
            'slug'     => Str::slug($request->name),
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
}

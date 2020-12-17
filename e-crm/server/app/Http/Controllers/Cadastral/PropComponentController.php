<?php

namespace App\Http\Controllers\Cadastral;

use App\Http\Controllers\Controller;
use App\Http\Filters\QueryFilter;
use App\Http\Resources\Cadastral\PropComponent as PropComponentResource;
use App\Http\Resources\Cadastral\PropComponentCollection;
use App\Models\Cadastral\PropComponent;
use Illuminate\Http\Request;

class PropComponentController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
        // $this->authorizeResource(PropComponent::class, 'propComponent');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = (new QueryFilter())->withAllowedFilters([
            'kind',
            'type_id',
        ]);

        return new PropComponentCollection(
            $filter->apply(PropComponent::query(), $request)->orderBy('name')->get()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cadastral\PropComponent  $propComponent
     * @return \Illuminate\Http\Response
     */
    public function show(PropComponent $propComponent)
    {
        return new PropComponentResource($propComponent);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cadastral\PropComponent  $propComponent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PropComponent $propComponent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cadastral\PropComponent  $propComponent
     * @return \Illuminate\Http\Response
     */
    public function destroy(PropComponent $propComponent)
    {
        //
    }
}

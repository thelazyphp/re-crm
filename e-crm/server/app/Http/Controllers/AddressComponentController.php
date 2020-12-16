<?php

namespace App\Http\Controllers;

use App\Http\Filters\QueryFilter;
use App\Http\Resources\AddressComponent as AddressComponentResource;
use App\Http\Resources\AddressComponentCollection;
use App\Models\AddressComponent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AddressComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new AddressComponentCollection(
            (new QueryFilter())
                ->withAllowedFilters([
                    'kind',
                    'country_id',
                    'province_id',
                    'area_id',
                    'locality_id',
                    'district_id',
                    'route_id',
                    'metro_id',
                    'street_id',
                    'house_id',
                    'entrance_id',
                ])
                ->filter(AddressComponent::query(), $request)->get()
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
     * @param  \App\Models\AddressComponent  $addressComponent
     * @return \Illuminate\Http\Response
     */
    public function show(AddressComponent $addressComponent)
    {
        return new AddressComponentResource($addressComponent);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AddressComponent  $addressComponent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AddressComponent $addressComponent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AddressComponent  $addressComponent
     * @return \Illuminate\Http\Response
     */
    public function destroy(AddressComponent $addressComponent)
    {
        //
    }
}

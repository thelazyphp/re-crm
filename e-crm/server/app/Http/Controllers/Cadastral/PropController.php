<?php

namespace App\Http\Controllers\Cadastral;

use App\Actions\Cadastral\UploadProps;
use App\Http\Controllers\Controller;
use App\Http\Filters\QueryFilter;
use App\Http\Resources\Cadastral\Prop as PropResource;
use App\Http\Resources\Cadastral\PropCollection;
use App\Models\Cadastral\Prop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PropController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
        // $this->authorizeResource(Prop::class, 'prop');
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
            'type_id',
            'address.province_id',
            'address.area_id',
            'address.locality_id',
            'function_id',
            'entry_date',
            'transaction_date',
            'rooms',
            'floor',
            'capital_floors',
            'size',
            'land_size',
            'pieces_after_transaction',
            'price_usd',
            'price_sqm_usd',
        ])->withAllowedSorts([
            'inventory_number',
            'type_id',
            'function_id',
            'entry_date',
            'transaction_date',
            'rooms',
            'floor',
            'capital_floors',
            'size',
            'land_size',
            'pieces_after_transaction',
            'price_usd',
            'price_sqm_usd',
        ])->withSearchCallback(function (Builder $query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('type', function (Builder $query) use ($search) {
                    $query->where(
                        'name',
                        'like',
                        '%'.$search.'%'
                    );
                });

                $query->orWhereHas('address', function (Builder $query) use ($search) {
                    $query->where(
                        'full_address',
                        'like',
                        '%'.$search.'%'
                    );
                });

                $query->orWhereHas('function', function (Builder $query) use ($search) {
                    $query->where(
                        'name',
                        'like',
                        '%'.$search.'%'
                    );
                });
            });
        });

        return new PropCollection(
            $filter->apply(Prop::query(), $request)->paginate($request->query('per_page', 20))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actions\Cadastral\UploadProps  $uploader
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UploadProps $uploader)
    {
        $input = $request->all();
        $uploader->upload($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cadastral\Prop  $prop
     * @return \Illuminate\Http\Response
     */
    public function show(Prop $prop)
    {
        return new PropResource($prop);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cadastral\Prop  $prop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prop $prop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cadastral\Prop  $prop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prop $prop)
    {
        //
    }
}

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
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new PropCollection(
            (new QueryFilter())
                ->withAllowedFilters([
                    //
                ])
                ->withAllowedSorts([
                    //
                ])
                ->withFilterCallback('', function (Builder $query, $column, $value) {
                    //
                })
                ->filter(Prop::query(), $request)->paginate($request->query('per_page'))
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

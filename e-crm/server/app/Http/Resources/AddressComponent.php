<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressComponent extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'kind' => $this->kind,
            'name' => $this->name,
            $this->mergeWhen(! is_null($this->country_id), ['country_id' => $this->country_id]),
            $this->mergeWhen(! is_null($this->province_id), ['province_id' => $this->province_id]),
            $this->mergeWhen(! is_null($this->area_id), ['area_id' => $this->area_id]),
            $this->mergeWhen(! is_null($this->locality_id), ['locality_id' => $this->locality_id]),
            $this->mergeWhen(! is_null($this->district_id), ['district_id' => $this->district_id]),
            $this->mergeWhen(! is_null($this->route_id), ['route_id' => $this->route_id]),
            $this->mergeWhen(! is_null($this->metro_id), ['metro_id' => $this->metro_id]),
            $this->mergeWhen(! is_null($this->street_id), ['street_id' => $this->street_id]),
            $this->mergeWhen(! is_null($this->house_id), ['house_id' => $this->house_id]),
            $this->mergeWhen(! is_null($this->entrance_id), ['entrance_id' => $this->entrance_id]),
        ];
    }
}

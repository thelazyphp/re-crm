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

            $this->mergeWhen($this->kind == 'province', [
                'country_id' => $this->country_id,
            ]),

            $this->mergeWhen($this->kind == 'area', [
                'country_id' => $this->country_id,
                'province_id' => $this->province_id,
            ]),

            $this->mergeWhen($this->kind == 'locality', [
                'country_id' => $this->country_id,
                'province_id' => $this->province_id,
                'area_id' => $this->area_id,
            ]),

            $this->mergeWhen($this->kind == 'district', [
                'country_id' => $this->country_id,
                'province_id' => $this->province_id,
                'area_id' => $this->area_id,
                'locality_id' => $this->locality_id,
            ]),

            $this->mergeWhen($this->kind == 'route', [
                'country_id' => $this->country_id,
                'province_id' => $this->province_id,
                'area_id' => $this->area_id,
                'locality_id' => $this->locality_id,
                'district_id' => $this->district_id,
            ]),

            $this->mergeWhen($this->kind == 'metro', [
                'country_id' => $this->country_id,
                'province_id' => $this->province_id,
                'area_id' => $this->area_id,
                'locality_id' => $this->locality_id,
                'district_id' => $this->district_id,
                'route_id' => $this->route_id,
            ]),

            $this->mergeWhen($this->kind == 'street', [
                'country_id' => $this->country_id,
                'province_id' => $this->province_id,
                'area_id' => $this->area_id,
                'locality_id' => $this->locality_id,
                'district_id' => $this->district_id,
                'route_id' => $this->route_id,
                'metro_id' => $this->metro_id,
            ]),

            $this->mergeWhen($this->kind == 'house', [
                'country_id' => $this->country_id,
                'province_id' => $this->province_id,
                'area_id' => $this->area_id,
                'locality_id' => $this->locality_id,
                'district_id' => $this->district_id,
                'route_id' => $this->route_id,
                'metro_id' => $this->metro_id,
                'street_id' => $this->street_id,
            ]),

            $this->mergeWhen($this->kind == 'entrance', [
                'country_id' => $this->country_id,
                'province_id' => $this->province_id,
                'area_id' => $this->area_id,
                'locality_id' => $this->locality_id,
                'district_id' => $this->district_id,
                'route_id' => $this->route_id,
                'metro_id' => $this->metro_id,
                'street_id' => $this->street_id,
                'house_id' => $this->house_id,
            ]),
        ];
    }
}

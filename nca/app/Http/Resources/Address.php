<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Address extends JsonResource
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
            'full_address' => $this->full_address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'country' => new AddressComponent($this->country),
            'province' => new AddressComponent($this->province),
            'area' => new AddressComponent($this->area),
            'locality' => new AddressComponent($this->locality),
            'district' => new AddressComponent($this->district),
            'route' => new AddressComponent($this->route),
            'metro' => new AddressComponent($this->metro),
            'street' => new AddressComponent($this->street),
            'house' => new AddressComponent($this->house),
            'entrance' => new AddressComponent($this->entrance),
        ];
    }
}

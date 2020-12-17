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
            'country' => new AddressComponent($this->whenLoaded('country')),
            'province' => new AddressComponent($this->whenLoaded('province')),
            'area' => new AddressComponent($this->whenLoaded('area')),
            'locality' => new AddressComponent($this->whenLoaded('locality')),
            'district' => new AddressComponent($this->whenLoaded('district')),
            'route' => new AddressComponent($this->whenLoaded('route')),
            'metro' => new AddressComponent($this->whenLoaded('metro')),
            'street' => new AddressComponent($this->whenLoaded('street')),
            'house' => new AddressComponent($this->whenLoaded('house')),
            'entrance' => new AddressComponent($this->whenLoaded('entrance')),
        ];
    }
}

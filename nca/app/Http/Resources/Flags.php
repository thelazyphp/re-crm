<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Flags extends JsonResource
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
            'skip_count' => $this->skip_count,
            'skip_price' => $this->skip_price,
            'proportion' => $this->proportion,
            'new_building' => $this->new_building,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

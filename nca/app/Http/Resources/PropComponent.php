<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropComponent extends JsonResource
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
            $this->mergeWhen($this->type_id, ['type_id' => $this->type_id]),
        ];
    }
}

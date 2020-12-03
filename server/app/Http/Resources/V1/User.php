<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'org' => new Org($this->whenLoaded('org')),
            'profile_photo_url' => $this->profile_photo_url,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'owner' => $this->owner,
            'scopes' => $this->scopes,
            'disabled' => $this->disabled,
            'email' => $this->email,
            'email_verified' => ! is_null($this->email_verified_at),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

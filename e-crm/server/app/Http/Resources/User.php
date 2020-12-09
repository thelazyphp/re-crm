<?php

namespace App\Http\Resources;

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
            'current_team_id' => $this->current_team_id,
            'profile_photo_url' => $this->profile_photo_url,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'email_verified' => $this->email_verified,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

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
        if ($request->user()) {
            return [
                'id' => $this->id,
                'profile_photo_url' => $this->profile_photo_url,
                'name' => $this->name,
                'email' => $this->email,
                'email_verified' => (bool) $this->email_verified_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        } else {
            return [
                'id' => $this->id,
                'profile_photo_url' => $this->profile_photo_url,
                'name' => $this->name,
            ];
        }
    }
}

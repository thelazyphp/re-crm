<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'is_favorite' => $this->isFavorite(),
            'group' => $this->group,
            'url' => $this->url,

            'user' => [
                'id' => $this->user_id,
                'url' => $this->user_url,
                'image' => $this->user_image,
                'name' => $this->user_name,
            ],

            'message' => $this->message,
            'images' => $this->images,
            'videos' => $this->videos,
            'likes' => $this->likes,
            'comments' => $this->comments,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'published_at' => $this->published_at,
        ];
    }
}

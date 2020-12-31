<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PostResource;

class GroupResource extends JsonResource
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
            'url' => $this->url,
            'is_public' => $this->is_public,
            'is_visible' => $this->is_visible,
            'image' => $this->image,
            'name' => $this->name,
            'description' => $this->description,
            'latest_post' => new PostResource($this->posts()->latest('published_at')->first()),
            'is_parsing' => $this->is_parsing,
            'get_posts' => $this->get_posts,
            'posts_limit' => $this->posts_limit,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'likes' => 0,
        'comments' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'group_id',
        'url',
        'user_id',
        'user_url',
        'user_image',
        'user_name',
        'message',
        'images',
        'videos',
        'likes',
        'comments',
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'likes' => 'integer',
        'comments' => 'integer',
        'published_at' => 'datetime',
    ];

    /**
     * @return bool
     */
    public function isFavorite()
    {
        return Favorite::where('user_id', auth()->id())
            ->where('post_id', $this->id)
            ->exists();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}

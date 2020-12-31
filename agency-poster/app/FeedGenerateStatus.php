<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedGenerateStatus extends Model
{
    const GENERATED_STATUS = 'generated';
    const GENERATING_STATUS = 'generating';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::GENERATING_STATUS,
        'generated' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'feed_id', 'total',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'errors' => 'array',
        'total' => 'integer',
        'generated' => 'integer',
    ];
}

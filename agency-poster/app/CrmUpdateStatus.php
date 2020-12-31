<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmUpdateStatus extends Model
{
    const UPDATED_STATUS = 'updated';
    const UPDATING_STATUS = 'updating';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::UPDATING_STATUS,
        'updated' => 0,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'total',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'errors' => 'array',
        'total' => 'integer',
        'updated' => 'integer',
    ];
}

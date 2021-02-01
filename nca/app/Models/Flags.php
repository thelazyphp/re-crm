<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flags extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'flags_table';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'skip_count' => false,
        'skip_price' => false,
        'proportion' => false,
        'new_building' => false,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prop_id',
        'skip_count',
        'skip_price',
        'proportion',
        'new_building',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'skip_count' => 'boolean',
        'skip_price' => 'boolean',
        'proportion' => 'boolean',
        'new_building' => 'boolean',
    ];

    public function prop()
    {
        return $this->belongsTo(Prop::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'country_id' => null,
        'province_id' => null,
        'area_id' => null,
        'locality_id' => null,
        'district_id' => null,
        'route_id' => null,
        'metro_id' => null,
        'street_id' => null,
        'house_id' => null,
        'entrance_id' => null,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_address',
        'lat',
        'lng',
        'country_id',
        'province_id',
        'area_id',
        'locality_id',
        'district_id',
        'route_id',
        'metro_id',
        'street_id',
        'house_id',
        'entrance_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];

    /**
     * Get the addressable model.
     */
    public function addressable()
    {
        return $this->morphTo();
    }
}

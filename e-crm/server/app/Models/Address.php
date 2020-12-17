<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'country',
        'province',
        'area',
        'locality',
        'district',
        'route',
        'metro',
        'street',
        'house',
        'entrance',
    ];

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

    /**
     * Get the country of the address.
     */
    public function country()
    {
        return $this->belongsTo(
            AddressComponent::class, 'country_id'
        );
    }

    /**
     * Get the province of the address.
     */
    public function province()
    {
        return $this->belongsTo(
            AddressComponent::class, 'province_id'
        );
    }

    /**
     * Get the area of the address.
     */
    public function area()
    {
        return $this->belongsTo(
            AddressComponent::class, 'area_id'
        );
    }

    /**
     * Get the locality of the address.
     */
    public function locality()
    {
        return $this->belongsTo(
            AddressComponent::class, 'locality_id'
        );
    }

    /**
     * Get the district of the address.
     */
    public function district()
    {
        return $this->belongsTo(
            AddressComponent::class, 'district_id'
        );
    }

    /**
     * Get the route of the address.
     */
    public function route()
    {
        return $this->belongsTo(
            AddressComponent::class, 'route_id'
        );
    }

    /**
     * Get the metro of the address.
     */
    public function metro()
    {
        return $this->belongsTo(
            AddressComponent::class, 'metro_id'
        );
    }

    /**
     * Get the street of the address.
     */
    public function street()
    {
        return $this->belongsTo(
            AddressComponent::class, 'street_id'
        );
    }

    /**
     * Get the house of the address.
     */
    public function house()
    {
        return $this->belongsTo(
            AddressComponent::class, 'house_id'
        );
    }

    /**
     * Get the entrance of the address.
     */
    public function entrance()
    {
        return $this->belongsTo(
            AddressComponent::class, 'entrance_id'
        );
    }
}

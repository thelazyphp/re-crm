<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressComponent extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kind',
        'name',
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
}

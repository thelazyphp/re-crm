<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmObject extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'manager_id' => 'integer',
        'disabled' => 'boolean',
        'ocenka' => 'integer',
        'price' => 'integer',
        'pricenow' => 'integer',
        'gps_coordinats' => 'array',
        'area_snb' => 'float',
        'area' => 'float',
        'living_space' => 'float',
        'kitchen_area' => 'float',
        'year_built' => 'integer',
        'floor_apartment' => 'integer',
        'number_of_floors' => 'integer',
        'rooms' => 'integer',
        'land_area' => 'float',
        'newflat' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Get the manager record associated with the object.
     */
    public function manager() {
        return $this->hasOne(CrmManager::class, 'id', 'manager_id');
    }

    /**
     * Get the Jandex geolocation record associated with the object.
     */
    public function jandexGeolocation() {
        return $this->hasOne(JandexGeolocation::class, 'crm_object_id', 'id');
    }
}

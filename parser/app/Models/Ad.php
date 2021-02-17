<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    const CATEGORY_APARTMENTS_ID = 1; // Квартиры
    const CATEGORY_HOUSES_ID = 2; // Дома, дачи, участки
    const CATEGORY_COMMERCIAL_ID = 3; // Коммерческая недвижимость

    const TYPE_RENT_ID = 1; // Аренда
    const TYPE_SELL_ID = 2; // Продажа

    const SOURCE_IRR_ID = 1; // irr.by

    use HasFactory;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['seller'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'type_id',
        'source_id',
        'url',
        'title',
        'images',
        'full_address',
        'rooms',
        'floor',
        'floors',
        'year_built',
        'land_size',
        'total_size',
        'living_size',
        'kitchen_size',
        'roof',
        'walls',
        'balcony',
        'bathroom',
        'price_currency',
        'price',
        'price_sqm',
        'checked',
        'published_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'category_id' => 'integer',
        'type_id' => 'integer',
        'source_id' => 'integer',
        'images' => 'array',
        'rooms' => 'integer',
        'floor' => 'integer',
        'floors' => 'integer',
        'year_built' => 'integer',
        'land_size' => 'float',
        'total_size' => 'float',
        'living_size' => 'float',
        'kitchen_size' => 'float',
        'price' => 'integer',
        'price_sqm' => 'float',
        'checked' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}

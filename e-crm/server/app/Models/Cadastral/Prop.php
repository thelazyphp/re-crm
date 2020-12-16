<?php

namespace App\Models\Cadastral;

use App\HasAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prop extends Model
{
    use HasFactory;
    use HasAddress;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cadastral_props';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [
        'type',
        'address',
        'function',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'inventory_number',
        'function_id',
        'function_description',
        'name',
        'size',
        'walls',
        'entry_date',
        'transaction_date',
        'transaction_id',
        'objects_count',
        'price_byn',
        'price_sqm_byn',
        'price_description',
        'price_usd',
        'price_sqm_usd',
        'price_eur',
        'price_sqm_eur',
        'contract_price_amount',
        'contract_price_currency',
        'pieces_before_transaction',
        'pieces_after_transaction',
        'rooms',
        'floor',
        'capital_inventory_number',
        'capital_size',
        'capital_function',
        'capital_function_description',
        'capital_name',
        'capital_ready_percentage',
        'capital_floors',
        'capital_underground_floors',
        'extra_objects',
        'land_cadastral_number',
        'land_function',
        'land_size',
        'ate_unique_number',
        'markers',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'size' => 'float',
        'entry_date' => 'date',
        'transaction_date' => 'date',
        'objects_count' => 'integer',
        'price_byn' => 'float',
        'price_sqm_byn' => 'float',
        'price_usd' => 'float',
        'price_sqm_usd' => 'float',
        'price_eur' => 'float',
        'price_sqm_eur' => 'float',
        'contract_price_amount' => 'float',
        'rooms' => 'integer',
        'floor' => 'integer',
        'capital_size' => 'float',
        'capital_ready_percentage' => 'integer',
        'capital_floors' => 'integer',
        'capital_underground_floors' => 'integer',
        'land_size' => 'float',
    ];

    /**
     * Get the prop type.
     */
    public function type()
    {
        return $this->belongsTo(
            PropComponent::class, 'type_id')->where('kind', 'type');
    }

    /**
     * Get the prop function.
     */
    public function function()
    {
        return $this->belongsTo(
            PropComponent::class, 'function_id')->where('kind', 'function');
    }
}

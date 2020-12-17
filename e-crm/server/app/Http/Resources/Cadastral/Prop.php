<?php

namespace App\Http\Resources\Cadastral;

use App\Http\Resources\Address;
use Illuminate\Http\Resources\Json\JsonResource;

class Prop extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'address' => new Address($this->whenLoaded('address')),
            'type' => new PropComponent($this->whenLoaded('type')),
            'inventory_number' => $this->inventory_number,
            'function' => new PropComponent($this->whenLoaded('function')),
            'function_description' => $this->function_description,
            'name' => $this->name,
            'size' => $this->size,
            'walls' => $this->walls,
            'entry_date' => $this->entry_date,
            'transaction_date' => $this->transaction_date,
            'transaction_id' => $this->transaction_id,
            'objects_count' => $this->objects_count,
            'price_byn' => $this->price_byn,
            'price_sqm_byn' => $this->price_sqm_byn,
            'price_description' => $this->price_description,
            'price_usd' => $this->price_usd,
            'price_sqm_usd' => $this->price_sqm_usd,
            'price_eur' => $this->price_eur,
            'price_sqm_eur' => $this->price_sqm_eur,
            'contract_price_amount' => $this->contract_price_amount,
            'contract_price_currency' => $this->contract_price_currency,
            'pieces_before_transaction' => $this->pieces_before_transaction,
            'pieces_after_transaction' => $this->pieces_after_transaction,
            'rooms' => $this->rooms,
            'floor' => $this->floor,
            'capital_inventory_number' => $this->capital_inventory_number,
            'capital_size' => $this->capital_size,
            'capital_function' => $this->capital_function,
            'capital_function_description' => $this->capital_function_description,
            'capital_name' => $this->capital_name,
            'capital_ready_percentage' => $this->capital_ready_percentage,
            'capital_floors' => $this->capital_floors,
            'capital_underground_floors' => $this->capital_underground_floors,
            'extra_objects' => $this->extra_objects,
            'land_cadastral_number' => $this->land_cadastral_number,
            'land_function' => $this->land_function,
            'land_size' => $this->land_size,
            'ate_unique_number' => $this->ate_unique_number,
            'markers' => $this->markers,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

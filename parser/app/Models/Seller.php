<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    const TYPE_OWNER_ID = 1; // Собственник
    const TYPE_AGENT_ID = 2; // Агент
    const TYPE_UNKNOWN_ID = 3; // Неизвестный

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'name',
        'email',
        'contact_phone',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type_id' => 'integer',
    ];

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}

<?php

namespace App;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'active' => true,
        'running' => false,
    ];

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
        'active' => 'boolean',
        'running' => 'boolean',
    ];

    /**
     * @return void
     */
    public function run()
    {
        if (! $this->active) {
            return;
        }

        $this->running = true;
        $this->save();

        set_time_limit(0);
        error_reporting(0);

        try {
            include(
                app_path("scripts/bots/{$this->id}.php")
            );

            $this->running = false;
            $this->run_at = date($this->getDateFormat());

            $this->save();
        } catch (Throwable $e) {
            $this->running = false;
            $this->run_at = date($this->getDateFormat());

            $this->save();

            Log::critical(
                "Bot \"{$this->id}\" error (at line {$e->getLine()}): {$e->getMessage()}!"
            );
        }
    }
}

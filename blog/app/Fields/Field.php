<?php

namespace App\Fields;

use Illuminate\Support\Str;

abstract class Field
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $attribute;

    /**
     * @param  string  $label
     * @param  string|null  $attribute
     * @return static
     */
    public static function make($label, $attribute = null)
    {
        return new static($label, $attribute);
    }

    /**
     * @param  string  $label
     * @param  string|null  $attribute
     */
    public function __construct($label, $attribute = null)
    {
        $this->label = $label;
        $this->attribute = $attribute ?? Str::snake($label);
    }
}

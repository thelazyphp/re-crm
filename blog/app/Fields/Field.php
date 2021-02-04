<?php

namespace App\Fields;

use Illuminate\Validation\Rule;

abstract class Field
{
    /**
     * @var string
     */
    protected static $component;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var array|string|callable
     */
    protected $rules = [];

    /**
     * @var array|string|callable
     */
    protected $createRules = [];

    /**
     * @var array|string|callable
     */
    protected $updateRules = [];

    /**
     * @param  string  $label
     * @param  string  $attribute
     * @return static
     */
    public static function make($label, $attribute)
    {
        return new static(
            $label,
            $attribute
        );
    }

    /**
     * @param  string  $label
     * @param  string  $attribute
     */
    public function __construct($label, $attribute)
    {
        $this->label = $label;
        $this->attribute = $attribute;
    }

    /**
     * @param  array|string|callable  $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * @param  array|string|callable  $rules
     * @return $this
     */
    public function createRules($rules)
    {
        $this->createRules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * @param  array|string|callable  $rules
     * @return $this
     */
    public function updateRules($rules)
    {
        $this->updateRules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }
}

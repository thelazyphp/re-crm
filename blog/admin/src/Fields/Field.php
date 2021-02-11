<?php

namespace Admin\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
     * @var string
     */
    public $helpText;

    /**
     * @var string
     */
    public $placeholder;

    /**
     * @var array|string|callable
     */
    protected $rules = [
        //
    ];

    /**
     * @var array|string|callable
     */
    protected $createRules = [
        //
    ];

    /**
     * @var array|string|callable
     */
    protected $updateRules = [
        //
    ];

    /**
     * @var callable
     */
    protected $resolveCallback;

    /**
     * @param  string  $label
     * @param  string|null  $attribute
     * @param  callable|null  $resolveCallback
     * @return static
     */
    public static function make(
        $label,
        $attribute = null,
        ?callable $resolveCallback = null
    ) {
        return new static(
            $label,
            $attribute,
            $resolveCallback
        );
    }

    /**
     * @param  string  $label
     * @param  string|null  $attribute
     * @param  callable|null  $resolveCallback
     */
    public function __construct(
        $label,
        $attribute = null,
        ?callable $resolveCallback = null
    ) {
        $this->resolveCallback = $resolveCallback;
        $this->label = $label;
        $this->attribute = $attribute ?? Str::snake($label);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function serializeToJSON(Request $request)
    {
        return [
            'label' => $this->label,
            'attribute' => $this->attribute,
            'helpText' => $this->helpText,
            'placeholder' => $this->placeholder,
        ];
    }

    /**
     * @param  string  $text
     * @return $this
     */
    public function helpText($text)
    {
        $this->helpText = $text;

        return $this;
    }

    /**
     * @param  string  $placeholder
     * @return $this
     */
    public function placeholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
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

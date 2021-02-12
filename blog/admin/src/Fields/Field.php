<?php

namespace Admin\Fields;

use Admin\Element;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

abstract class Field extends Element
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
    public $placeholder;

    /**
     * @var string
     */
    public $helpText;

    /**
     * @var \Closure|mixed
     */
    protected $default;

    /**
     * @var \Closure|bool
     */
    protected $required;

    /**
     * @var \Closure
     */
    protected $fillCallback;

    /**
     * @var \Closure
     */
    protected $resolveCallback;

    /**
     * @var \Closure
     */
    protected $displayCallback;

    /**
     * @var \Closure|array|string
     */
    protected $rules = [];

    /**
     * @var \Closure|array|string
     */
    protected $createRules = [];

    /**
     * @var \Closure|array|string
     */
    protected $updateRules = [];

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
            'component' => $this->component,
            'meta' => $this->meta,
            'label' => $this->label,
            'attribute' => $this->attribute,
            'placeholder' => $this->placeholder,
            'helpText' => $this->helpText,
            'default' => $this->getDefault($request),
            'required' => $this->isRequired($request),
        ];
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
     * @param  string  $text
     * @return $this
     */
    public function helpText($text)
    {
        $this->helpText = $text;

        return $this;
    }

    /**
     * @param  \Closure|mixed  $callback
     * @return $this
     */
    public function default($callback = true)
    {
        $this->default = $callback;

        return $this;
    }

    /**
     * @param  \Closure|bool  $callback
     * @return $this
     */
    public function required($callback = true)
    {
        $this->required = $callback;

        return $this;
    }

    /**
     * @param  \Closure  $callback
     * @return $this
     */
    public function fillUsing(Closure $callback)
    {
        $this->fillCallback = $callback;

        return $this;
    }

    /**
     * @param  \Closure  $callback
     * @return $this
     */
    public function resolveUsing(Closure $callback)
    {
        $this->resolveCallback = $callback;

        return $this;
    }

    /**
     * @param  \Closure  $callback
     * @return $this
     */
    public function displayUsing(Closure $callback)
    {
        $this->displayCallback = $callback;

        return $this;
    }

    /**
     * @param  \Closure|array|string  $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * @param  \Closure|array|string  $rules
     * @return $this
     */
    public function createRules($rules)
    {
        $this->createRules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * @param  \Closure|array|string  $rules
     * @return $this
     */
    public function updateRules($rules)
    {
        $this->updateRules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isRequired(Request $request)
    {
        return is_callable($this->required) ? call_user_func($this->required, $request) : $this->required;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function getDefault(Request $request)
    {
        return is_callable($this->default) ? call_user_func($this->default, $request) : $this->default;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getRules(Request $request)
    {
        $rules = is_callable($this->rules) ? call_user_func($this->rules, $request) : $this->rules;

        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        return [$this->attribute => $rules];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getCreateRules(Request $request)
    {
        $rules = is_callable($this->createRules) ? call_user_func($this->createRules, $request) : $this->createRules;

        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        return array_merge_recursive(
            $this->getRules($request), [
                $this->attribute => $rules,
            ]
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getUpdateRules(Request $request)
    {
        $rules = is_callable($this->updateRules) ? call_user_func($this->updateRules, $request) : $this->updateRules;

        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        return array_merge_recursive(
            $this->getRules($request), [
                $this->attribute => $rules,
            ]
        );
    }
}

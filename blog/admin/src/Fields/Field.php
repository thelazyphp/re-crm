<?php

namespace Admin\Fields;

use Admin\Element;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use JsonSerializable;

abstract class Field extends Element implements JsonSerializable
{
    /**
     * @var mixed
     */
    public $value;

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
    public $help;

    /**
     * @var string
     */
    public $placeholder;

    /**
     * @var \Closure|mixed
     */
    protected $default = false;

    /**
     * @var \Closure|bool
     */
    protected $nullable = false;

    /**
     * @var \Closure|bool
     */
    protected $required = false;

    /**
     * @var \Closure|bool
     */
    protected $readonly = false;

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
     * @param  \Closure|null  $resolveCallback
     * @return static
     */
    public static function make(
        $label,
        $attribute = null,
        ?Closure $resolveCallback = null
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
     * @param  \Closure|null  $resolveCallback
     */
    public function __construct(
        $label,
        $attribute = null,
        ?Closure $resolveCallback = null
    ) {
        $this->resolveCallback = $resolveCallback;
        $this->label = $label;
        $this->attribute = $attribute ?? Str::snake($label);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return $this
     */
    public function fill(Request $request, Model $model)
    {
        if (is_callable($this->fillCallback)) {
            call_user_func(
                $this->fillCallback, $request, $model, $this->attribute
            );
        } else {
            $model->{$this->attribute} = $request->input(
                $this->attribute, $this->getDefault($request)
            );
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return [
            'component' => $this->component,
            'value' => $this->value,
            'label' => $this->label,
            'attribute' => $this->attribute,
            'help' => $this->help,
            'placeholder' => $this->placeholder,
            'default' => $this->getDefault(request()),
            'nullable' => $this->isNullable(request()),
            'required' => $this->isRequired(request()),
            'readonly' => $this->isReadonly(request()),
        ];
    }

    /**
     * @param  string  $help
     * @return $this
     */
    public function help($help)
    {
        $this->help = $help;

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
     * @param  \Closure|mixed  $default
     * @return $this
     */
    public function default($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @param  \Closure|bool  $nullable
     * @return $this
     */
    public function nullable($nullable = true)
    {
        $this->nullable = $nullable;

        return $this;
    }

    /**
     * @param  \Closure|bool  $required
     * @return $this
     */
    public function required($required = true)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @param  \Closure|bool  $readonly
     * @return $this
     */
    public function readonly($readonly = true)
    {
        $this->readonly = $readonly;

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
     * @return mixed
     */
    public function getDefault(Request $request)
    {
        return is_callable($this->default) ? call_user_func($this->default, $request) : $this->default;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isNullable(Request $request)
    {
        return is_callable($this->nullable) ? call_user_func($this->nullable, $request) : $this->nullable;
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
     * @return bool
     */
    public function isReadonly(Request $request)
    {
        return is_callable($this->readonly) ? call_user_func($this->readonly, $request) : $this->readonly;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getRules(Request $request)
    {
        $rules = is_callable($this->rules) ? call_user_func($this->rules, $request) : $this->rules;

        if (is_string($rules)) {
            $rules = explode(
                '|', $rules
            );
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
            $rules = explode(
                '|', $rules
            );
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
            $rules = explode(
                '|', $rules
            );
        }

        return array_merge_recursive(
            $this->getRules($request), [
                $this->attribute => $rules,
            ]
        );
    }
}

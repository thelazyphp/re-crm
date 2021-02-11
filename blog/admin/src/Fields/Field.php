<?php

namespace Admin\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Field
{
    /**
     * @var string
     */
    public static $component;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $attribute;

    /**
     * @var array|string|callable
     */
    public $rules = [];

    /**
     * @var array|string|callable
     */
    public $createRules = [];

    /**
     * @var array|string|callable
     */
    public $updateRules = [];

    /**
     * @var callable
     */
    protected $fillCallback;

    /**
     * @var callable
     */
    protected $resolveCallback;

    /**
     * @var callable
     */
    protected $displayCallback;

    /**
     * @var string
     */
    public $placeholder;

    /**
     * @var string
     */
    public $help;

    /**
     * @var bool|callable
     */
    public $required = false;

    /**
     * @var bool|callable
     */
    public $readonly = false;

    /**
     * @var array
     */
    public $meta = [];

    /**
     * @var mixed
     */
    public $default;

    /**
     * @var bool
     */
    public $showOnIndex = true;

    /**
     * @var bool
     */
    public $showOnDetail = true;

    /**
     * @var bool
     */
    public $showOnCreate = true;

    /**
     * @var bool
     */
    public $showOnUpdate = true;

    /**
     * @param  string  $label
     * @param  string|null  $attribute
     * @return static
     */
    public static function make($label, $attribute = null)
    {
        return new static(
            $label,
            $attribute
        );
    }

    /**
     * @param  string  $label
     * @param  string| null  $attribute
     */
    public function __construct($label, $attribute = null)
    {
        $this->label = $label;
        $this->attribute = $attribute ?? Str::snake($label);
    }

    /**
     * @param  array|string|callable  $callback
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @param  array|string|callable  $callback
     * @return $this
     */
    public function createRules($rules)
    {
        $this->createRules = $rules;

        return $this;
    }

    /**
     * @param  array|string|callable  $callback
     * @return $this
     */
    public function updateRules($rules)
    {
        $this->updateRules = $rules;

        return $this;
    }

    /**
     * @param  callable  $callback
     * @return $this
     */
    public function fillUsing(callable $callback)
    {
        $this->fillCallback = $callback;

        return $this;
    }

    /**
     * @param  callable  $callback
     * @return $this
     */
    public function resolveUsing(callable $callback)
    {
        $this->resolveCallback = $callback;

        return $this;
    }

    /**
     * @param  callable  $callback
     * @return $this
     */
    public function displayUsing(callable $callback)
    {
        $this->displayCallback = $callback;

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
     * @param  string  $help
     * @return $this
     */
    public function help($help)
    {
        $this->help = $help;

        return $this;
    }

    /**
     * @param  bool|callable  $callback
     * @return $this
     */
    public function required($callback = true)
    {
        $this->required = $callback;

        return $this;
    }

    /**
     * @param  bool|callable  $callback
     * @return $this
     */
    public function readonly($callback = true)
    {
        $this->readonly = $callback;

        return $this;
    }

    /**
     * @param  array  $meta
     * @return $this
     */
    public function meta(array $meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @param  array  $default
     * @return $this
     */
    public function default($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @param  bool|callable  $callback
     * @return $this
     */
    public function showOnIndex($callback = true)
    {
        $this->showOnIndex = $callback;

        return $this;
    }

    /**
     * @param  bool|callable  $callback
     * @return $this
     */
    public function showOnDetail($callback = true)
    {
        $this->showOnDetail = $callback;

        return $this;
    }

    /**
     * @param  bool|callable  $callback
     * @return $this
     */
    public function showOnCreate($callback = true)
    {
        $this->showOnCreate = $callback;

        return $this;
    }

    /**
     * @param  bool|callable  $callback
     * @return $this
     */
    public function showOnUpdate($callback = true)
    {
        $this->showOnUpdate = $callback;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnIndex()
    {
        $this->showOnIndex  = true;
        $this->showOnDetail = false;
        $this->showOnCreate = false;
        $this->showOnUpdate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnDetail()
    {
        $this->showOnIndex  = false;
        $this->showOnDetail = true;
        $this->showOnCreate = false;
        $this->showOnUpdate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnCreate()
    {
        $this->showOnIndex  = false;
        $this->showOnDetail = false;
        $this->showOnCreate = true;
        $this->showOnUpdate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnUpdate()
    {
        $this->showOnIndex  = false;
        $this->showOnDetail = false;
        $this->showOnCreate = false;
        $this->showOnUpdate = true;

        return $this;
    }

    /**
     * @param  bool|callable  $callback
     * @return $this
     */
    public function hideFromIndex($callback = true)
    {
        $this->showOnIndex = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array(
                $callback, func_get_args()
            );
        } : ! $callback;

        return $this;
    }

    /**
     * @param  bool|callable  $callback
     * @return $this
     */
    public function hideFromDetail($callback = true)
    {
        $this->showOnDetail = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array(
                $callback, func_get_args()
            );
        } : ! $callback;

        return $this;
    }

    /**
     * @param  bool|callable  $callback
     * @return $this
     */
    public function hideFromCreate($callback = true)
    {
        $this->showOnCreate = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array(
                $callback, func_get_args()
            );
        } : ! $callback;

        return $this;
    }

    /**
     * @param  bool|callable  $callback
     * @return $this
     */
    public function hideFromUpdate($callback = true)
    {
        $this->showOnUpdate = is_callable($callback) ? function () use ($callback) {
            return ! call_user_func_array(
                $callback, func_get_args()
            );
        } : ! $callback;

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $attribute
     * @param  mixed  $value
     * @return $this
     */
    public function fill(
        Request $request,
        Model $model,
        $attribute,
        $value
    ) {
        if (is_callable($this->fillCallback)) {
            call_user_func(
                $this->fillCallback, $request, $model, $attribute, $value
            );
        } else {
            $model->{$attribute} = $value;
        }

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function serializeToArray(Request $request)
    {
        return [
            'component' => static::$component,
            'label' => $this->label,
            'attribute' => $this->attribute,
            'placeholder' => $this->placeholder ?: $this->label,
            'help' => $this->help,
            'required' => $this->isRequired($request),
            'readonly' => $this->isReadonly($request),
            'meta' => $this->meta,
            'default' => $this->default,
            'showOnIndex' => $this->isShowOnIndex($request),
            'showOnDetail' => $this->isShowOnDetail($request),
            'showOnCreate' => $this->isShowOnCreate($request),
            'showOnUpdate' => $this->isShowOnUpdate($request),
        ];
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
     * @return bool
     */
    public function isShowOnIndex(Request $request)
    {
        return is_callable($this->showOnIndex) ? call_user_func($this->showOnIndex, $request) : $this->showOnIndex;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isShowOnDetail(Request $request)
    {
        return is_callable($this->showOnDetail) ? call_user_func($this->showOnDetail, $request) : $this->showOnDetail;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isShowOnCreate(Request $request)
    {
        return is_callable($this->showOnCreate) ? call_user_func($this->showOnCreate, $request) : $this->showOnCreate;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isShowOnUpdate(Request $request)
    {
        return is_callable($this->showOnUpdate) ? call_user_func($this->showOnUpdate, $request) : $this->showOnUpdate;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getRules(Request $request)
    {
        return [
            $this->attribute
                => is_callable($this->rules) ? call_user_func($this->rules, $request) : $this->rules
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getCreateRules(Request $request)
    {
        return array_merge_recursive(
            $this->getRules($request), [
                $this->attribute
                    => is_callable($this->createRules) ? call_user_func($this->createRules, $request) : $this->createRules
            ]
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getUpdateRules(Request $request)
    {
        return array_merge_recursive(
            $this->getRules($request), [
                $this->attribute
                    => is_callable($this->updateRules) ? call_user_func($this->updateRules, $request) : $this->updateRules
            ]
        );
    }
}

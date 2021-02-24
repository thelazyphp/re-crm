<?php

namespace Admin\Fields;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JsonSerializable;

abstract class Field implements JsonSerializable
{
    /**
     * @var string
     */
    public $component;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $indexName;

    /**
     * @var string
     */
    public $attribute;

    /**
     * @var bool
     */
    public $sortable = false;

    /**
     * @var string
     */
    public $help;

    /**
     * @var array
     */
    public $meta = [];

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var callable|mixed
     */
    protected $default;

    /**
     * @var callable|array
     */
    protected $nullValues = [
        null, '',
    ];

    /**
     * @var callable|array
     */
    protected $rules = [];

    /**
     * @var callable|array
     */
    protected $createRules = [];

    /**
     * @var callable|array
     */
    protected $updateRules = [];

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
     * @var callable
     */
    protected $attributeCallback;

    /**
     * @var callable|bool
     */
    protected $readonly = false;

    /**
     * @var callable|bool
     */
    protected $required = false;

    /**
     * @var callable|bool
     */
    protected $nullable = false;

    /**
     * @var callable|bool
     */
    protected $showOnIndex = true;

    /**
     * @var callable|bool
     */
    protected $showOnDetail = true;

    /**
     * @var callable|bool
     */
    protected $showOnCreate = true;

    /**
     * @var callable|bool
     */
    protected $showOnUpdate = true;

    /**
     * @param  string  $name
     * @param  callable|string|null  $attribute
     * @return static
     */
    public static function make($name, $attribute = null)
    {
        return new static(
            $name,
            $attribute
        );
    }

    /**
     * @param  string  $name
     * @param  callable|string|null  $attribute
     */
    public function __construct($name, $attribute = null)
    {
        $this->name = $name;

        if (is_callable($attribute)) {
            $this->attribute = '__COMPUTED__';
            $this->attributeCallback = $attribute;
        } else {
            $this->attribute = $attribute ?? Str::snake($name);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        $request = request();

        return [
            'component' => $this->component,
            'name' => $this->name,
            'indexName' => $this->indexName ?: $this->name,
            'attribute' => $this->attribute,
            'sortable' => $this->sortable,
            'help' => $this->help,
            'meta' => $this->meta,
            'value' => $this->value,
            'default' => $this->getDefault($request),
            'readonly' => $this->isReadonly($request),
            'required' => $this->isRequired($request),
            'nullable' => $this->isNullable($request),
        ];
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
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $resource
     * @return void
     */
    public function fill(Request $request, $resource)
    {
        if (is_callable($this->fillCallback)) {
            call_user_func(
                $this->fillCallback, $request, $resource, $this->attribute
            );
        } else {
            $value = $request->input($this->attribute);

            $resource->{$this->attribute} = $this->isNullValue($value, $request)
                ? null
                : $value;
        }
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
     * @param  mixed  $resource
     * @return void
     */
    public function resolve($resource)
    {
        if ($this->attribute == '__COMPUTED__') {
            $this->value = call_user_func(
                $this->attributeCallback, $resource
            );
        } else {
            $this->value = data_get(
                $resource, $this->attribute
            );

            if (is_callable($this->resolveCallback)) {
                $this->value = call_user_func(
                    $this->resolveCallback, $this->value
                );
            }
        }
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
     * @param  mixed  $resource
     * @return void
     */
    public function resolveForDisplay($resource)
    {
        if ($this->attribute == '__COMPUTED__') {
            $this->value = call_user_func(
                $this->attributeCallback, $resource
            );
        } else {
            $this->value = data_get(
                $resource, $this->attribute
            );

            if (is_callable($this->displayCallback)) {
                $this->value = call_user_func(
                    $this->displayCallback, $this->value
                );
            }
        }
    }

    /**
     * @param  string  $name
     * @return $this
     */
    public function indexName($name)
    {
        $this->indexName = $name;

        return $this;
    }

    /**
     * @param  string  $sortable
     * @return $this
     */
    public function sortable($sortable = true)
    {
        if (! $this->attribute == '__COMPUTED__') {
            $this->sortable = $sortable;
        }

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
     * @param  array  $meta
     * @return $this
     */
    public function withMeta(array $meta)
    {
        $this->meta = array_merge(
            $this->meta, $meta
        );

        return $this;
    }

    /**
     * @param  string  $placeholder
     * @return $this
     */
    public function placeholder($placeholder)
    {
        return $this->withMeta([
            'placeholder' => $placeholder,
        ]);
    }

    /**
     * @param  callable|mixed  $default
     * @return $this
     */
    public function default($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function getDefault(Request $request)
    {
        return ! is_callable($this->default)
            ? $this->default
            : call_user_func(
                $this->default, $request
            );
    }

    /**
     * @param  callable|array|mixed  $values
     * @return $this
     */
    public function nullValues($values)
    {
        $this->nullValues = is_array($values) || is_callable($values)
            ? $values
            : func_get_args();

        return $this;
    }

    /**
     * @param  callable|array|mixed  $rules
     * @return $this
     */
    public function rules($rules)
    {
        $this->rules = is_array($rules) || is_callable($rules)
            ? $rules
            : func_get_args();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getRules(Request $request)
    {
        return [
            $this->attribute => ! is_callable($this->rules)
                ? $this->rules
                : call_user_func(
                    $this->rules, $request
                )
        ];
    }

    /**
     * @param  callable|array|mixed  $rules
     * @return $this
     */
    public function createRules($rules)
    {
        $this->createRules = is_array($rules) || is_callable($rules)
            ? $rules
            : func_get_args();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getCreateRules(Request $request)
    {
        return array_merge_recursive(
            $this->getRules($request), [
                $this->attribute => ! is_callable($this->createRules)
                    ? $this->createRules
                    : call_user_func(
                        $this->createRules, $request
                    )
            ]
        );
    }

    /**
     * @param  callable|array|mixed  $rules
     * @return $this
     */
    public function updateRules($rules)
    {
        $this->updateRules = is_array($rules) || is_callable($rules)
            ? $rules
            : func_get_args();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getUpdateRules(Request $request)
    {
        return array_merge_recursive(
            $this->getRules($request), [
                $this->attribute => ! is_callable($this->updateRules)
                    ? $this->updateRules
                    : call_user_func(
                        $this->updateRules, $request
                    )
            ]
        );
    }

    /**
     * @param  callable|bool  $required
     * @return $this
     */
    public function required($required = true)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isRequired(Request $request)
    {
        return ! is_callable($this->required)
            ? $this->required
            : call_user_func(
                $this->required, $request
            );
    }

    /**
     * @param  callable|bool  $readonly
     * @return $this
     */
    public function readonly($readonly = true)
    {
        $this->readonly = $readonly;

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isReadonly(Request $request)
    {
        return ! is_callable($this->readonly)
            ? $this->readonly
            : call_user_func(
                $this->readonly, $request
            );
    }

    /**
     * @param  callable|bool  $nullable
     * @param  callable|array  $nullValues
     * @return $this
     */
    public function nullable($nullable = true, $nullValues = null)
    {
        $this->nullable = $nullable;

        if (! is_null($nullValues)) {
            $this->nullValues($nullValues);
        }

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isNullable(Request $request)
    {
        return ! is_callable($this->nullable)
            ? $this->nullable
            : call_user_func(
                $this->nullable, $request
            );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $resource
     * @return bool
     */
    public function isShowOnIndex(Request $request, $resource)
    {
        return ! is_callable($this->showOnIndex)
            ? $this->showOnIndex
            : call_user_func(
                $this->showOnIndex, $request, $resource
            );
    }

    /**
     * @param  callable|bool  $callback
     * @return $this
     */
    public function showOnIndex($callback = true)
    {
        $this->showOnIndex = $callback;

        return $this;
    }

    /**
     * @param  callable|bool  $callback
     * @return $this
     */
    public function hideFromIndex($callback = true)
    {
        $this->showOnIndex = ! is_callable($callback)
            ? ! $callback
            : function () use ($callback) {
                return call_user_func(
                    $callback,
                    func_get_args()
                );
            };

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnIndex()
    {
        $this->showOnDetail = $this->showOnCreate = $this->showOnUpdate = false;

        $this->showOnIndex = true;

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $resource
     * @return bool
     */
    public function isShowOnDetail(Request $request, $resource)
    {
        return ! is_callable($this->showOnDetail)
            ? $this->showOnDetail
            : call_user_func(
                $this->showOnDetail, $request, $resource
            );
    }

    /**
     * @param  callable|bool  $callback
     * @return $this
     */
    public function showOnDetail($callback = true)
    {
        $this->showOnDetail = $callback;

        return $this;
    }

    /**
     * @param  callable|bool  $callback
     * @return $this
     */
    public function hideFromDetail($callback = true)
    {
        $this->showOnDetail = ! is_callable($callback)
        ? ! $callback
        : function () use ($callback) {
            return call_user_func(
                $callback,
                func_get_args()
            );
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnDetail()
    {
        $this->showOnIndex = $this->showOnCreate = $this->showOnUpdate = false;

        $this->showOnDetail = true;

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $resource
     * @return bool
     */
    public function isShowOnCreate(Request $request)
    {
        return ! is_callable($this->showOnCreate)
            ? $this->showOnCreate
            : call_user_func(
                $this->showOnCreate, $request
            );
    }

    /**
     * @param  callable|bool  $callback
     * @return $this
     */
    public function showOnCreate($callback = true)
    {
        $this->showOnCreate = $callback;

        return $this;
    }

    /**
     * @param  callable|bool  $callback
     * @return $this
     */
    public function hideFromCreate($callback = true)
    {
        $this->showOnCreate = ! is_callable($callback)
        ? ! $callback
        : function () use ($callback) {
            return call_user_func(
                $callback,
                func_get_args()
            );
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnCreate()
    {
        $this->showOnIndex = $this->showOnDetail = $this->showOnUpdate = false;

        $this->showOnCreate = true;

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $resource
     * @return bool
     */
    public function isShowOnUpdate(Request $request, $resource)
    {
        return ! is_callable($this->showOnUpdate)
            ? $this->showOnUpdate
            : call_user_func(
                $this->showOnUpdate, $request, $resource
            );
    }

    /**
     * @param  callable|bool  $callback
     * @return $this
     */
    public function showOnUpdate($callback = true)
    {
        $this->showOnUpdate = $callback;

        return $this;
    }

    /**
     * @param  callable|bool  $callback
     * @return $this
     */
    public function hideFromUpdate($callback = true)
    {
        $this->showOnUpdate = ! is_callable($callback)
        ? ! $callback
        : function () use ($callback) {
            return call_user_func(
                $callback,
                func_get_args()
            );
        };

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnUpdate()
    {
        $this->showOnIndex = $this->showOnDetail = $this->showOnCreate = false;

        $this->showOnUpdate = true;

        return $this;
    }

    /**
     * @param  mixed  $value
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isNullValue($value, Request $request)
    {
        if (! $this->isNullable($request)) {
            return false;
        }

        return is_callable($this->nullValues)
            ? call_user_func(
                $this->nullValues, $value
            ) : in_array(
                $value, $this->nullValues, true
            );
    }
}

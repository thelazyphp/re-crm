<?php

namespace Admin\Fields;

use Admin\Metable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JsonSerializable;

abstract class Field implements JsonSerializable
{
    use Metable;

    /**
     * @var string
     */
    public $component;

    /**
     * @var string
     */
    public $name;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public $attribute;

    /**
     * @var callable|mixed
     */
    protected $default;

    /**
     * @var callable|bool
     */
    protected $sortable = false;

    /**
     * @var callable|array
     */
    protected $asNull = [
        '',
    ];

    /**
     * @var callable|bool
     */
    protected $nullable = false;

    /**
     * @var callable|bool
     */
    protected $required = false;

    /**
     * @var callable|bool
     */
    protected $readonly = false;

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
    protected $computeUsing;

    /**
     * @var callable
     */
    protected $fillUsing;

    /**
     * @var callable
     */
    protected $resolveUsing;

    /**
     * @var callable
     */
    protected $displayUsing;

    /**
     * @var callable|bool
     */
    protected $displayOnIndex = true;

    /**
     * @var callable|bool
     */
    protected $displayOnShow = true;

    /**
     * @var callable|bool
     */
    protected $displayOnCreate = true;

    /**
     * @var callable|bool
     */
    protected $displayOnUpdate = true;

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
            $this->attribute = 'COMPUTED';
            $this->computeUsing = $attribute;
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
            'meta' => $this->meta,
            'component' => $this->component,
            'name' => $this->name,
            'value' => $this->value,
            'attribute' => $this->attribute,
            'sortable' => $this->isSortable($request),
            'nullable' => $this->isNullable($request),
            'required' => $this->isRequired($request),
            'readonly' => $this->isReadonly($request),
        ];
    }

    /**
     * @return bool
     */
    public function isComputed()
    {
        return $this->attribute == 'COMPUTED' && is_callable($this->computeUsing);
    }

    /**
     * @param  callable  $fillUsing
     * @return $this
     */
    public function fillUsing(callable $fillUsing)
    {
        $this->fillUsing = $fillUsing;

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function fill(Request $request, Model $model)
    {
        if ($this->isComputed()) {
            return;
        }

        if (is_callable($this->fillUsing)) {
            call_user_func(
                $this->fillUsing,
                $request, $model, $this->attribute
            );
        } else {
            $value = $request->{$this->attribute};

            $model->{$this->attribute} = $this->isNull($value, $request)
                ? null
                : $value;
        }
    }

    /**
     * @param  callable  $resolveUsing
     * @return $this
     */
    public function resolveUsing(callable $resolveUsing)
    {
        $this->resolveUsing = $resolveUsing;

        return $this;
    }

    /**
     * @param  callable  $displayUsing
     * @return $this
     */
    public function displayUsing(callable $displayUsing)
    {
        $this->displayUsing = $displayUsing;

        return $this;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  bool  $forDisplay
     * @return void
     */
    public function resolve(Model $model, $forDisplay = false)
    {
        if ($this->isComputed()) {
            $this->value = call_user_func(
                $this->computeUsing, $model
            );

            return;
        }

        $this->value = $model->{$this->attribute};

        if (is_callable($this->resolveUsing)) {
            $this->value = call_user_func(
                $this->resolveUsing,
                $this->value, $model, $this->attribute
            );
        }

        if ($forDisplay && is_callable($this->displayUsing)) {
            $this->value = call_user_func(
                $this->displayUsing,
                $this->value, $model, $this->attribute
            );
        }
    }

    /**
     * @param  string  $placeholder
     * @return $this
     */
    public function placeholder($placeholder)
    {
        return $this->withMeta([
            'attributes' => [
                'placeholder' => $placeholder,
            ],
        ]);
    }

    /**
     * @param  string  $help
     * @return $this
     */
    public function help($help)
    {
        return $this->withMeta([
            'help' => $help,
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
            : call_user_func($this->default, $request);
    }

    /**
     * @param  callable|bool  $sortable
     * @return $this
     */
    public function sortable($sortable = true)
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isSortable(Request $request)
    {
        return ! is_callable($this->sortable)
            ? $this->sortable
            : call_user_func($this->sortable, $request);
    }

    /**
     * @param  callable|array|mixed  $asNull
     * @return $this
     */
    public function asNull($asNull)
    {
        $this->asNull = is_array($asNull) || is_callable($asNull)
            ? $asNull
            : func_get_args();

        return $this;
    }

    /**
     * @param  callable|bool  $nullable
     * @param  callable|array|null  $asNull
     * @return $this
     */
    public function nullable($nullable = true, $asNull = null)
    {
        $this->nullable = $nullable;

        if (! is_null($asNull)) {
            $this->asNull($asNull);
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
            : call_user_func($this->nullable, $request);
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
            : call_user_func($this->required, $request);
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
            : call_user_func($this->readonly, $request);
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

        return $this;
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
                : call_user_func($this->rules, $request)
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

        return $this;
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
                    : call_user_func($this->createRules, $request)
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

        return $this;
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
                    : call_user_func($this->updateRules, $request)
            ]
        );
    }

    /**
     * @param  callable|bool  $displayOnIndex
     * @return $this
     */
    public function displayOnIndex($displayOnIndex = true)
    {
        $this->displayOnIndex = $displayOnIndex;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnIndex()
    {
        $this->displayOnIndex = true;
        $this->displayOnShow = false;
        $this->displayOnCreate = false;
        $this->displayOnUpdate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function exceptOnIndex()
    {
        $this->displayOnIndex = false;
        $this->displayOnShow = true;
        $this->displayOnCreate = true;
        $this->displayOnUpdate = true;

        return $this;
    }

    /**
     * @param  callable|bool  $hideFromIndex
     * @return $this
     */
    public function hideFromIndex($hideFromIndex = true)
    {
        $this->displayOnIndex = ! is_callable($hideFromIndex)
            ? ! $hideFromIndex
            : function () use ($hideFromIndex) {
                return ! call_user_func(
                    $hideFromIndex, func_get_args()
                );
            };

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    public function isDisplayOnIndex(Request $request, Model $model)
    {
        return ! is_callable($this->displayOnIndex)
            ? $this->displayOnIndex
            : call_user_func(
                $this->displayOnIndex, $request, $model
            );
    }

    /**
     * @param  callable|bool  $displayOnShow
     * @return $this
     */
    public function displayOnShow($displayOnShow = true)
    {
        $this->displayOnShow = $displayOnShow;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnShow()
    {
        $this->displayOnIndex = false;
        $this->displayOnShow = true;
        $this->displayOnCreate = false;
        $this->displayOnUpdate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function exceptOnShow()
    {
        $this->displayOnIndex = true;
        $this->displayOnShow = false;
        $this->displayOnCreate = true;
        $this->displayOnUpdate = true;

        return $this;
    }

    /**
     * @param  callable|bool  $hideFromShow
     * @return $this
     */
    public function hideFromShow($hideFromShow = true)
    {
        $this->displayOnShow = ! is_callable($hideFromShow)
            ? ! $hideFromShow
            : function () use ($hideFromShow) {
                return ! call_user_func(
                    $hideFromShow, func_get_args()
                );
            };

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    public function isDisplayOnShow(Request $request, Model $model)
    {
        return ! is_callable($this->displayOnShow)
            ? $this->displayOnShow
            : call_user_func(
                $this->displayOnShow, $request, $model
            );
    }

    /**
     * @return $this
     */
    public function onlyOnForms()
    {
        $this->displayOnIndex = false;
        $this->displayOnShow = false;
        $this->displayOnCreate = true;
        $this->displayOnUpdate = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function exceptOnForms()
    {
        $this->displayOnIndex = true;
        $this->displayOnShow = true;
        $this->displayOnCreate = false;
        $this->displayOnUpdate = false;

        return $this;
    }

    /**
     * @param  callable|bool  $displayOnCreate
     * @return $this
     */
    public function displayOnCreate($displayOnCreate = true)
    {
        $this->displayOnCreate = $displayOnCreate;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnCreate()
    {
        $this->displayOnIndex = false;
        $this->displayOnShow = false;
        $this->displayOnCreate = true;
        $this->displayOnUpdate = false;

        return $this;
    }

    /**
     * @return $this
     */
    public function exceptOnCreate()
    {
        $this->displayOnIndex = true;
        $this->displayOnShow = true;
        $this->displayOnCreate = false;
        $this->displayOnUpdate = true;

        return $this;
    }

    /**
     * @param  callable|bool  $hideFromCreate
     * @return $this
     */
    public function hideFromCreate($hideFromCreate = true)
    {
        $this->displayOnCreate = ! is_callable($hideFromCreate)
            ? ! $hideFromCreate
            : function () use ($hideFromCreate) {
                return ! call_user_func(
                    $hideFromCreate, func_get_args()
                );
            };

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isDisplayOnCreate(Request $request)
    {
        return ! is_callable($this->displayOnCreate)
            ? $this->displayOnCreate
            : call_user_func(
                $this->displayOnCreate, $request
            );
    }

    /**
     * @param  callable|bool  $displayOnUpdate
     * @return $this
     */
    public function displayOnUpdate($displayOnUpdate = true)
    {
        $this->displayOnUpdate = $displayOnUpdate;

        return $this;
    }

    /**
     * @return $this
     */
    public function onlyOnUpdate()
    {
        $this->displayOnIndex = false;
        $this->displayOnShow = false;
        $this->displayOnCreate = false;
        $this->displayOnUpdate = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function exceptOnUpdate()
    {
        $this->displayOnIndex = true;
        $this->displayOnShow = true;
        $this->displayOnCreate = true;
        $this->displayOnUpdate = false;

        return $this;
    }

    /**
     * @param  callable|bool  $hideFromUpdate
     * @return $this
     */
    public function hideFromUpdate($hideFromUpdate = true)
    {
        $this->displayOnUpdate = ! is_callable($hideFromUpdate)
            ? ! $hideFromUpdate
            : function () use ($hideFromUpdate) {
                return ! call_user_func(
                    $hideFromUpdate, func_get_args()
                );
            };

        return $this;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    public function isDisplayOnUpdate(Request $request, Model $model)
    {
        return ! is_callable($this->displayOnUpdate)
            ? $this->displayOnUpdate
            : call_user_func(
                $this->displayOnUpdate, $request, $model
            );
    }

    /**
     * @param  mixed  $value
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isNull($value, Request $request)
    {
        if (! $this->isNullable($request)) {
            return false;
        }

        if ($value === null) {
            return true;
        }

        return is_callable($this->asNull)
            ? call_user_func($this->asNull, $value)
            : in_array($value, $this->asNull, true);
    }
}

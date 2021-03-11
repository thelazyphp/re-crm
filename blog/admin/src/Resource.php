<?php

namespace Admin;

use Admin\Fields\Field;
use Admin\Fields\ID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JsonSerializable;

abstract class Resource implements JsonSerializable
{
    /**
     * @var string
     */
    public static $model;

    /**
     * @var string
     */
    public static $title = 'id';

    /**
     * @var bool
     */
    public static $smallTable = false;

    /**
     * @var bool
     */
    public static $borderedTable = false;

    /**
     * @var bool
     */
    public static $displayInNavigation = true;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $modelInstance;

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function newModel()
    {
        return new static::$model;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return static
     */
    public static function forModel(Model $model)
    {
        return new static($model);
    }

    /**
     * @return string
     */
    public static function key()
    {
        return Str::plural(
            Str::kebab(
                class_basename(
                    get_called_class()
                )
            )
        );
    }

    /**
     * @return string
     */
    public static function name()
    {
        return Admin::humanize(
            class_basename(
                get_called_class()
            )
        );
    }

    /**
     * @return string
     */
    public static function pluralName()
    {
        return Str::plural(static::name());
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     */
    public function __construct(?Model $model = null)
    {
        $this->modelInstance = $model ?? static::newModel();
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return [
            'displayInNavigation' => static::$displayInNavigation,
            'smallTable' => static::$smallTable,
            'borderedTable' => static::$borderedTable,
            'key' => static::key(),
            'name' => static::name(),
            'pluralName' => static::pluralName(),
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        return $this->modelInstance;
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->model()->{static::$title};
    }

    /**
     * @return string|null
     */
    public function subtitle()
    {
        return null;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Support\Collection|null  $fields
     * @return array
     */
    public function getCreateRules(Request $request, ?Collection $fields = null)
    {
        $fields = $fields ?? $this->getCreateFields($request, true);

        return collect($fields)->mapWithKeys(function (Field $field) use ($request) {
            return $field->getCreateRules($request);
        })->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Support\Collection|null  $fields
     * @return array
     */
    public function getUpdateRules(Request $request, ?Collection $fields = null)
    {
        $fields = $fields ?? $this->getUpdateFields($request, true);

        return collect($fields)->mapWithKeys(function (Field $field) use ($request) {
            return $field->getUpdateRules($request);
        })->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $forUpdate
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function fill(Request $request, $forUpdate = false)
    {
        $fields = $forUpdate
            ? $this->getUpdateFields($request, true)
            : $this->getCreateFields($request, true);

        $validator = Validator::make(
            $request->all(),
            $forUpdate
                ? $this->getUpdateRules($request, $fields)
                : $this->getCreateRules($request, $fields)
        );

        $validator->validate();

        $fields->each->fill(
            $request, $this->model()
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function serializeForIndex(Request $request)
    {
        $fields = $this->getIndexFields($request);

        return [
            'id' => $fields->whereInstanceOf(ID::class)->first() ?? ID::forModel($this->model()),
            'fields' => $fields->values()->all(),
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function serializeForShow(Request $request)
    {
        $fields = $this->getShowFields($request);

        return [
            'id' => $fields->whereInstanceOf(ID::class)->first() ?? ID::forModel($this->model()),
            'fields' => $fields->values()->all(),
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    abstract public function fields(Request $request): array;

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function getIndexFields(Request $request)
    {
        $method = ! method_exists($this, 'indexFields')
            ? 'fields'
            : 'indexFields';

        return tap(collect(call_user_func([$this, $method], $request) ?: [])->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                $field->isDisplayOnIndex($request, $this->model());
        }), function (Collection $fields) {
            $fields->each->resolve($this->model(), true);
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function getShowFields(Request $request)
    {
        $method = ! method_exists($this, 'showFields')
            ? 'fields'
            : 'showFields';

        return tap(collect(call_user_func([$this, $method], $request) ?: [])->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                $field->isDisplayOnShow($request, $this->model());
        }), function (Collection $fields) {
            $fields->each->resolve($this->model(), true);
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $exceptReadonly
     * @return \Illuminate\Support\Collection
     */
    public function getCreateFields(Request $request, $exceptReadonly = false)
    {
        $method = ! method_exists($this, 'createFields')
            ? 'fields'
            : 'createFields';

        return tap(collect(call_user_func([$this, $method], $request) ?: [])->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                ! $field->isComputed() &&
                $field->isDisplayOnCreate($request) &&
                ! $field instanceof ID && $field->attribute != $this->model()->getKeyName();
        })->reject(function (Field $field) use ($request, $exceptReadonly) {
            return $exceptReadonly && $field->isReadonly($request);
        }), function (Collection $fields) {
            $fields->each->resolve($this->model());
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $exceptReadonly
     * @return \Illuminate\Support\Collection
     */
    public function getUpdateFields(Request $request, $exceptReadonly = false)
    {
        $method = ! method_exists($this, 'updateFields')
            ? 'fields'
            : 'updateFields';

        return tap(collect(call_user_func([$this, $method], $request) ?: [])->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                ! $field->isComputed() &&
                $field->isDisplayOnUpdate($request, $this->model()) &&
                ! $field instanceof ID && $field->attribute != $this->model()->getKeyName();
        })->reject(function (Field $field) use ($request, $exceptReadonly) {
            return $exceptReadonly && $field->isReadonly($request);
        }), function (Collection $fields) {
            $fields->each->resolve($this->model());
        });
    }
}

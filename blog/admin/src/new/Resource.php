<?php

namespace Admin;

use Admin\Fields\Field;
use Admin\Fields\ID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
    public static $displayInNavigation = true;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $modelInstance;

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function mewModel()
    {
        return new static::$model;
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
            'key' => static::key(),
            'name' => static::name(),
            'pluralName' => static::pluralName(),
            'title' => $this->title(),
            'subtitle' => $this->subtitle(),
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
     * @return \Illuminate\Support\Collection
     */
    public function getCreateFieldsExceptReadonly(Request $request)
    {
        return $this->getCreateFields($request)->reject(function (Field $field) use ($request) {
            return $field->isReadonly($request);
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function getCreateFields(Request $request)
    {
        $method = ! method_exists($this, 'createFields')
            ? 'fields'
            : 'createFields';

        return tap(collect(call_user_func([$this, $method], $request) ?: [])->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                $field->isDisplayOnCreate($request) &&
                ! $field instanceof ID && $field->attribute != $this->model()->getKeyName();
        }), function (Collection $fields) {
            $fields->each->resolve($this->model());
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function getUpdateFieldsExceptReadonly(Request $request)
    {
        return $this->getUpdateFields($request)->reject(function (Field $field) use ($request) {
            return $field->isReadonly($request);
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function getUpdateFields(Request $request)
    {
        $method = ! method_exists($this, 'updateFields')
            ? 'fields'
            : 'updateFields';

        return tap(collect(call_user_func([$this, $method], $request) ?: [])->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                $field->isDisplayOnUpdate($request, $this->model()) &&
                ! $field instanceof ID && $field->attribute != $this->model()->getKeyName();
        }), function (Collection $fields) {
            $fields->each->resolve($this->model());
        });
    }
}

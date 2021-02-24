<?php

namespace Admin;

use Admin\Fields\Field;
use Admin\Fields\ID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
     * @var bool
     */
    public static $showInNavigation = true;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $resource;

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function newModel()
    {
        return new static::$model;
    }

    /**
     * @return string
     */
    public static function name()
    {
        $class = class_basename(get_called_class());

        return Str::plural(
            Str::kebab($class)
        );
    }

    /**
     * @return string
     */
    public static function label()
    {
        $class = class_basename(get_called_class());

        return Str::title(
            Str::snake(
                $class, ' '
            )
        );
    }

    /**
     * @return string
     */
    public static function pluralLabel()
    {
        return Str::plural(
            static::label()
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function getCreateRules(Request $request)
    {
        $resource = static::newModel();

        $fields = (new static($resource))->getCreateFieldsWithoutReadonly($request);

        collect($fields)->mapWithKeys(function ($field) use ($request) {
            return $field->getCreateRules($request);
        })->values()->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     * @return array
     */
    public static function getUpdateRules(Request $request, Model $resource)
    {
        $fields = (new static($resource))->getUpdateFieldsWithoutReadonly($request);

        collect($fields)->mapWithKeys(function ($field) use ($request) {
            return $field->getUpdateRules($request);
        })->values()->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function getValidatorForCreate(Request $request)
    {
        return Validator::make(
            $request->all(),
            static::getCreateRules($request)
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function getValidatorForUpdate(Request $request, Model $resource)
    {
        return Validator::make(
            $request->all(),
            static::getUpdateRules($request, $resource)
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateForCreate(Request $request)
    {
        return static::getValidatorForCreate($request)->validate();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function validateForUpdate(Request $request, Model $resource)
    {
        return static::getValidatorForUpdate($request, $resource)->validate();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function fillForCreate(Request $request)
    {
        static::validateForCreate($request);

        $resource = static::newModel();

        $fields = (new static($resource))->getCreateFieldsWithoutReadonly($request);

        collect($fields)->each(function ($field) use ($request, $resource) {
            $field->fill(
                $request, $resource
            );
        });

        $resource->save();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function fillForUpdate(Request $request, Model $resource)
    {
        static::validateForUpdate($request, $resource);

        $fields = (new static($resource))->getUpdateFieldsWithoutReadonly($request);

        collect($fields)->each(function ($field) use ($request, $resource) {
            $field->fill(
                $request, $resource
            );
        });

        $resource->save();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     */
    public function __construct(Model $resource)
    {
        $this->resource = $resource;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return [
            'showInNavigation' => static::$showInNavigation,
            'name' => static::name(),
            'label' => static::label(),
            'pluralLabel' => static::pluralLabel(),
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model()
    {
        return $this->resource;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function resolveForIndex(Request $request)
    {
        collect($this->getIndexFields($request))->each(function ($field) {
            $field->resolveForDisplay($this->model());
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function resolveForDetail(Request $request)
    {
        collect($this->getDetailFields($request))->each(function ($field) {
            $field->resolveForDisplay($this->model());
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function resolveForCreate(Request $request)
    {
        collect($this->getCreateFields($request))->each(function ($field) {
            $field->resolve($this->model());
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function resolveForUpdate(Request $request)
    {
        collect($this->getUpdateFields($request))->each(function ($field) {
            $field->resolve($this->model());
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    abstract public function fields(Request $request): array;

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getIndexFields(Request $request)
    {
        return collect($this->fields($request))->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                $field->isShowOnIndex($request, $this->model());
        })->values()->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getDetailFields(Request $request)
    {
        return collect($this->fields($request))->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                $field->isShowOnDetail($request, $this->model());
        })->values()->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getCreateFields(Request $request)
    {
        return collect($this->fields($request))->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                $field->isShowOnCreate($request) &&
                $field->attribute != '__COMPUTED__' &&
                ! $field instanceof ID &&
                $field->attribute != $this->model()->getKey();
        })->values()->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getCreateFieldsWithoutReadonly(Request $request)
    {
        return collect($this->getCreateFields($request))->filter(function ($field) use ($request) {
            return ! $field->isReadonly($request);
        })->values()->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getUpdateFields(Request $request)
    {
        return collect($this->fields($request))->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                $field->isShowOnUpdate($request, $this->model()) &&
                $field->attribute != '__COMPUTED__' &&
                ! $field instanceof ID &&
                $field->attribute != $this->model()->getKey();
        })->values()->all();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getUpdateFieldsWithoutReadonly(Request $request)
    {
        return collect($this->getUpdateFields($request))->filter(function ($field) use ($request) {
            return ! $field->isReadonly($request);
        })->values()->all();
    }
}

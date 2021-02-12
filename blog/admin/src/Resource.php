<?php

namespace Admin;

use Admin\Fields\Field;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Resource
{
    /**
     * @var string
     */
    public static $model;

    /**
     * @var bool
     */
    public static $displayInNavigation = true;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $resource;

    /**
     * @return string
     */
    public static function uriKey()
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
    public static function label()
    {
        return Str::title(
            Str::snake(
                class_basename(get_called_class()), ' '
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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function newModel(): Model
    {
        return new static::$model;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     */
    public function __construct(Model $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function serializeToJSON(Request $request)
    {
        return [
            'displayInNavigation' => static::$displayInNavigation,
            'uriKey' => static::uriKey(),
            'label' => static::label(),
            'pluralLabel' => static::pluralLabel(),
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    abstract public function fields(Request $request);

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    public function fieldsForIndex(Request $request)
    {
        return [];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    public function fieldsForDetail(Request $request)
    {
        return [];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    public function fieldsForCreate(Request $request)
    {
        return [];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    public function fieldsForUpdate(Request $request)
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function model(): Model
    {
        return $this->resource;
    }

    /**
     * @return mixed
     */
    public function modelKey()
    {
        return $this->model()->getKey();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    public function indexFields(Request $request)
    {
        $fieldsForIndex = $this->fieldsForIndex($request);

        return collect(empty($fieldsForIndex) ? $this->fields($request) : $fieldsForIndex)->filter(function ($field) use ($request) {
            return $field instanceof Field && $field->isShowOnIndex($request);
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    public function detailFields(Request $request)
    {
        $fieldsForDetail = $this->fieldsForDetail($request);

        return collect(empty($fieldsForDetail) ? $this->fields($request) : $fieldsForDetail)->filter(function ($field) use ($request) {
            return $field instanceof Field && $field->isShowOnDetail($request);
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    public function createFields(Request $request)
    {
        $fieldsForCreate = $this->fieldsForCreate($request);

        return collect(empty($fieldsForCreate) ? $this->fields($request) : $fieldsForCreate)->filter(function ($field) use ($request) {
            return $field instanceof Field && $field->isShowOnCreate($request);
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    public function updateFields(Request $request)
    {
        $fieldsForUpdate = $this->fieldsForUpdate($request);

        return collect(empty($fieldsForUpdate) ? $this->fields($request) : $fieldsForUpdate)->filter(function ($field) use ($request) {
            return $field instanceof Field && $field->isShowOnUpdate($request);
        });
    }
}

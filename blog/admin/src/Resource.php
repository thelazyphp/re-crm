<?php

namespace Admin;

use Admin\Fields\Field;
use Admin\Fields\ID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
    protected $resource;

    /**
     * @return string
     */
    public static function name()
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
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return [
            'title' => static::$title,
            'displayInNavigation' => static::$displayInNavigation,
            'name' => static::name(),
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
    public function fieldsForForms(Request $request)
    {
        return [];
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
        $fieldsForForms = $this->fieldsForForms($request);

        return collect(empty($fieldsForForms) ? $this->fields($request) : $fieldsForForms)->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                ! $field instanceof ID &&
                $field->isShowOnCreate($request);
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    public function updateFields(Request $request)
    {
        $fieldsForForms = $this->fieldsForForms($request);

        return collect(empty($fieldsForForms) ? $this->fields($request) : $fieldsForForms)->filter(function ($field) use ($request) {
            return $field instanceof Field &&
                ! $field instanceof ID &&
                $field->isShowOnUpdate($request);
        });
    }
}

<?php

namespace Admin;

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
    public function createFields(Request $request)
    {
        return [
            //
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    public function updateFields(Request $request)
    {
        return [
            //
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Admin\Fields\Field[]
     */
    abstract public function fields(Request $request);
}

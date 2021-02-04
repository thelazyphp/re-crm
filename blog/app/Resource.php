<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class Resource
{
    /**
     * @var string
     */
    public static $model;

    /**
     * @var string
     */
    public static $label = '';

    /**
     * @var string
     */
    public static $pluralLabel = '';

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $resource;

    /**
     * @return static
     */
    public static function make()
    {
        return new static(
            new static::$model
        );
    }

    /**
     * @return string
     */
    public static function key()
    {
        return Str::kebab(
            Str::plural(
                class_basename(static::$model)
            )
        );
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     */
    public function __construct(Model $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return array
     */
    abstract public function fields(): array;
}

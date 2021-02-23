<?php

namespace Admin;

use Illuminate\Http\Request;

abstract class Resource
{
    /**
     * @var string
     */
    public static $model;

    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function newModel()
    {
        return new static::$model;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    abstract public function fields(Request $request): array;
}

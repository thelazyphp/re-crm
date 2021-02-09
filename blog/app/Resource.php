<?php

namespace App;

use Illuminate\Http\Request;
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
     * @var bool
     */
    public static $displayInNavigation = true;

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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    abstract public function fields(Request $request): array;
}

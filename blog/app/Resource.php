<?php

namespace App;

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
}

<?php

namespace Admin;

abstract class Admin
{
    /**
     * @var array
     */
    public static $resources = [];

    /**
     * @param  array  $resources
     * @return void
     */
    public static function resources(array $resources)
    {
        static::$resources = array_unique(
            array_merge(
                static::$resources, $resources
            )
        );
    }

    /**
     * @return string
     */
    public static function version()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public static function name()
    {
        return config('admin.name');
    }

    /**
     * @return string
     */
    public static function path()
    {
        return config('admin.path');
    }

    /**
     * @return string
     */
    public static function locale()
    {
        return config('admin.locale');
    }

    /**
     * @param  string  $uriKey
     * @return string|null
     */
    public static function resourceByUriKey($uriKey)
    {
        return collect(static::$resources)->first(function ($resource) use ($uriKey) {
            return $uriKey == $resource::$uriKey;
        });
    }
}

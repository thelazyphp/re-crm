<?php

namespace Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Admin
{
    /**
     * @var string
     */
    public static $version = '1.0.0';

    /**
     * @var array
     */
    public static $resources = [];

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
     * @param  string  $value
     * @return string
     */
    public static function humanize($value)
    {
        return Str::title(
            Str::snake(
                $value, ' '
            )
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function json(Request $request)
    {
        return [
            'version' => static::$version,
            'name' => static::name(),
            'base' => static::path(),
            'locale' => static::locale(),
            'resources' => collect(static::$resources)->map(function ($resource) {
                return [
                    'displayInNavigation' => $resource::$displayInNavigation,
                    'key' => $resource::key(),
                    'name' => $resource::name(),
                    'pluralName' => $resource::pluralName(),
                    'smallTable' => $resource::$smallTable,
                    'borderedTable' => $resource::$borderedTable,
                ];
            })
        ];
    }

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
     * @param  string  $key
     * @return string|null
     */
    public static function findResourceByKey($key)
    {
        return collect(static::$resources)->first(function ($resource) use ($key) {
            return $resource::key() == $key;
        });
    }
}

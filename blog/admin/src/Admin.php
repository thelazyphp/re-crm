<?php

namespace Admin;

use Illuminate\Http\Request;

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
                    'showInNavigation' => $resource::$showInNavigation,
                    'name' => $resource::name(),
                    'label' => $resource::label(),
                    'pluralLabel' => $resource::pluralLabel(),
                ];
            }),
            'apiUrl' => config('app.url').static::path().'/api',
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
     * @param  string  $name
     * @return string|null
     */
    public static function findResourceByName($name)
    {
        return collect(static::$resources)->first(function ($resource) use ($name) {
            return $resource::name() == $name;
        });
    }
}

<?php

namespace Admin;

use GuzzleHttp\Psr7\Uri;

abstract class Admin
{
    /**
     * @var array
     */
    public static $resources = [];

    /**
     * @var array
     */
    public static $translations = [];

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
     * @param  string  $name
     * @return string|null
     */
    public static function resourceByName($name)
    {
        return collect(static::$resources)->first(function ($resource) use ($name) {
            return $name == $resource::name();
        });
    }

    /**
     * @return array
     */
    public static function toJSON()
    {
        $resources = collect(static::$resources)->map(function ($resource) {
            return new $resource($resource::newModel());
        });

        return [
            'resources' => $resources,
            'translations' => static::$translations,
            'version' => static::version(),
            'name' => static::name(),
            'path' => static::path(),
            'locale' => static::locale(),
            'assetUrl' => asset('vendor/admin'),
            'baseUrl' => config('app.url').static::path(),
            'apiBaseUrl' => config('app.url').static::path().'/api',
            'routerBaseUrl' => '/'.trim((new Uri(config('app.url').static::path()))->getPath(), '/').'/',
        ];
    }
}

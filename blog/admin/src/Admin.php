<?php

namespace Admin;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Http\Request;

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
     * @return string
     */
    public static function fallbackLocale()
    {
        return config('admin.fallback_locale');
    }

    /**
     * @param  string  $uriKey
     * @return string|null
     */
    public static function resourceByUriKey($uriKey)
    {
        return collect(static::$resources)->first(function ($resource) use ($uriKey) {
            return $uriKey == $resource::uriKey();
        });
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function serializeToJSON(Request $request)
    {
        $resources = collect(static::$resources)->map(function ($resource) use ($request) {
            return (new $resource($resource::newModel()))->serializeToJSON($request);
        });

        return [
            'resources' => $resources,
            'translations' => static::$translations,
            'version' => static::version(),
            'name' => static::name(),
            'path' => static::path(),
            'locale' => static::locale(),
            'fallbackLocale' => static::fallbackLocale(),
            'assetUrl' => asset('vendor/admin'),
            'apiBaseUrl' => rtrim(config('app.url'), '/').'/'.trim(static::path(), '/').'/api',
            'routerBaseUrl' => '/'.trim((new Uri(config('app.url')))->getPath(), '/').'/'.trim(static::path(), '/').'/',
        ];
    }
}

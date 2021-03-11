<?php

namespace Admin;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JsonSerializable;

abstract class Action implements JsonSerializable
{
    /**
     * @return static
     */
    public static function make()
    {
        return new static;
    }

    /**
     * @return string
     */
    public static function key()
    {
        return Str::kebab(
            class_basename(
                get_called_class()
            )
        );
    }

    /**
     * @return string
     */
    public static function name()
    {
        return Admin::humanize(
            class_basename(
                get_called_class()
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return [
            'key' => static::key(),
            'name' => static::name(),
            'fields' => $this->fields(request()),
        ];
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function fields(Request $request)
    {
        return [];
    }

    /**
     * @param  ActionFields  $fields
     * @param  \Illuminate\Database\Eloquent\Collection  $models
     * @return mixed
     */
    abstract public function handle(ActionFields $fields, Collection $models);
}

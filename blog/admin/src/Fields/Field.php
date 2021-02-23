<?php

namespace Admin\Fields;

use Illuminate\Support\Str;

abstract class Field
{
    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $attribute;

    /**
     * @var callable
     */
    protected $resolveCallback;

    /**
     * @var callable
     */
    protected $displayCallback;

    /**
     * @var callable
     */
    protected $attributeCallback;

    /**
     * @param  string  $name
     * @param  callable|string|null  $attribute
     * @return static
     */
    public static function make($name, $attribute = null)
    {
        return new static(
            $name,
            $attribute
        );
    }

    /**
     * @param  string  $name
     * @param  callable|string|null  $attribute
     */
    public function __construct($name, $attribute = null)
    {
        $this->name = $name;

        if (is_callable($attribute)) {
            $this->attribute = '__COMPUTED__';
            $this->attributeCallback = $attribute;
        } else {
            $this->attribute = $attribute ?? Str::snake($name);
        }
    }

    /**
     * @param  callable  $callback
     * @return $this
     */
    public function resolveUsing(callable $callback)
    {
        $this->resolveCallback = $callback;

        return $this;
    }

    /**
     * @param  callable  $callback
     * @return $this
     */
    public function displayUsing(callable $callback)
    {
        $this->displayCallback = $callback;

        return $this;
    }

    /**
     * @param  mixed  $resource
     * @return void
     */
    public function resolve($resource)
    {
        if ($this->attribute == '__COMPUTED__') {
            $this->value = call_user_func(
                $this->attributeCallback, $resource
            );
        } else {
            $this->value = data_get(
                $resource, $this->attribute
            );

            if (is_callable($this->resolveCallback)) {
                $this->value = call_user_func(
                    $this->resolveCallback, $this->value
                );
            }
        }
    }
}

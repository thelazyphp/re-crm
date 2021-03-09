<?php

namespace Admin\Fields;

use Illuminate\Database\Eloquent\Model;

class ID extends Field
{
    /**
     * {@inheritDoc}
     */
    public $component = 'text-field';

    /**
     * {@inheritDoc}
     */
    public static function make($name = 'ID', $attribute = 'id')
    {
        return new static(
            $name,
            $attribute
        );
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return static
     */
    public static function forModel(Model $model)
    {
        return tap(static::make('ID', $model->getKeyName()), function (ID $id) use ($model) {
            $id->resolve($model);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function __construct($name = 'ID', $attribute = 'id')
    {
        parent::__construct(
            $name,
            $attribute
        );

        $this->exceptOnForms();
    }

    /**
     * @param  bool  $asBigInt
     * @return $this
     */
    public function asBigInt($asBigInt = true)
    {
        if ($asBigInt) {
            $this->resolveUsing(function ($value) {
                return (string) $value;
            });
        }

        return $this;
    }
}

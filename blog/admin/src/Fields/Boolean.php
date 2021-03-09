<?php

namespace Admin\Fields;

class Boolean extends Field
{
    /**
     * {@inheritDoc}
     */
    public $component = 'boolean-field';

    /**
     * @var bool
     */
    public $trueValue = true;

    /**
     * @var bool
     */
    public $falseValue = false;

    /**
     * {@inheritDoc}
     */
    public function __construct($name, $attribute = null)
    {
        parent::__construct(
            $name,
            $attribute
        );

        $this->fillUsing(function ($request, $model, $attribute) {
            $model->{$attribute} = $request->boolean($attribute)
                ? $this->trueValue
                : $this->falseValue;
        });

        $this->resolveUsing(function ($value) {
            return $value == $this->trueValue
                ? true
                : false;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(), [
                'trueValue' => $this->trueValue,
                'falseValue' => $this->falseValue,
            ]
        );
    }

    /**
     * @param  mixed  $value
     * @return $this
     */
    public function trueValue($value)
    {
        $this->trueValue = $value;

        return $this;
    }

    /**
     * @param  mixed  $value
     * @return $this
     */
    public function falseValue($value)
    {
        $this->falseValue = $value;

        return $this;
    }
}

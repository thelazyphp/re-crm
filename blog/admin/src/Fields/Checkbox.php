<?php

namespace Admin\Fields;

class Checkbox extends Field
{
    /**
     * {@inheritDoc}
     */
    public $component = 'checkbox-field';

    /**
     * @var bool
     */
    public $trueValue = true;

    /**
     * @var bool
     */
    public $falseValue = true;

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
}

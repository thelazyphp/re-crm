<?php

namespace Admin\Fields;

class Checkbox extends Field
{
    /**
     * @var string
     */
    public $component = 'checkbox-field';

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

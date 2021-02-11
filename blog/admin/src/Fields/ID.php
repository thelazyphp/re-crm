<?php

namespace Admin\Fields;

class ID extends Field
{
    /**
     * @var string
     */
    public static $component = 'v-id-field';

    /**
     * {@inheritDoc}
     */
    public static function make($label = 'ID', $attribute = 'id')
    {
        return parent::make(
            $label,
            $attribute
        );
    }

    /**
     * {@inheritDoc}
     */
    public function __construct($label = 'ID', $attribute = 'id')
    {
        parent::__construct(
            $label,
            $attribute
        );
    }
}

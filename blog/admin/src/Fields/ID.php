<?php

namespace Admin\Fields;

class ID extends Field
{
    /**
     * @var string
     */
    public $component = 'text-field';

    /**
     * {@inheritDoc}
     */
    public static function make(
        $label = 'ID',
        $attribute = 'id',
        ?callable $resolveCallback = null
    ) {
        return new static(
            $label,
            $attribute,
            $resolveCallback
        );
    }

    /**
     * {@inheritDoc}
     */
    public function __construct(
        $label = 'ID',
        $attribute = 'id',
        ?callable $resolveCallback = null
    ) {
        parent::__construct(
            $label,
            $attribute,
            $resolveCallback
        );
    }

    /**
     * @return $this
     */
    public function asBigInt()
    {
        $this->resolveCallback = function ($id) {
            return (string) $id;
        };

        return $this;
    }
}

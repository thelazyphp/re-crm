<?php

namespace Admin\Fields;

use Closure;

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
        ?Closure $resolveCallback = null
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
        ?Closure $resolveCallback = null
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
    public function asString()
    {
        $this->resolveCallback = function ($id) {
            return (string) $id;
        };

        return $this;
    }
}

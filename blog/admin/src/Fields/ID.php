<?php

namespace Admin\Fields;

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
        return parent::make(
            $name,
            $attribute
        );
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

        $this->hideFromCreate();
        $this->hideFromUpdate();
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

            $this->displayUsing(function ($value) {
                return (string) $value;
            });
        }

        return $this;
    }
}

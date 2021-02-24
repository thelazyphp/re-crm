<?php

namespace Admin\Fields;

class Textarea extends Field
{
    /**
     * {@inheritDoc}
     */
    public $component = 'textarea-field';

    /**
     * {@inheritDoc}
     */
    public function __construct($name, $attribute = null)
    {
        parent::__construct(
            $name,
            $attribute
        );

        $this->hideFromIndex();
    }

    /**
     * @param  int  $rows
     * @return $this
     */
    public function rows($rows)
    {
        return $this->withMeta([
            'rows' => $rows,
        ]);
    }
}

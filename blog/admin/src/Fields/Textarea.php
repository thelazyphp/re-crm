<?php

namespace Admin\Fields;

class Textarea extends Field
{
    /**
     * @var string
     */
    public $component = 'textarea-field';

    /**
     * @var int
     */
    public $rows = 3;

    /**
     * @param  int  $rows
     * @return $this
     */
    public function rows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(), [
                'rows' => $this->rows,
            ]
        );
    }
}

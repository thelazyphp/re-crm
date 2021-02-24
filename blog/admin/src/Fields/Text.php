<?php

namespace Admin\Fields;

class Text extends Field
{
    /**
     * {@inheritDoc}
     */
    public $component = 'text-field';

    /**
     * @var bool
     */
    public $asHtml = false;

    /**
     * @param  bool  $asHtml
     * @return $this
     */
    public function asHtml($asHtml = true)
    {
        $this->asHtml = $asHtml;

        return $this;
    }
}

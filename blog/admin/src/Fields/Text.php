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
     * @var array
     */
    public $suggestions = [];

    /**
     * @param  bool  $asHtml
     * @return $this
     */
    public function asHtml($asHtml = true)
    {
        $this->asHtml = $asHtml;

        return $this;
    }

    /**
     * @param  callable|array  $suggestions
     * @return $this
     */
    public function suggestions($suggestions)
    {
        $this->suggestions = ! is_callable($suggestions)
            ? $suggestions
            : call_user_func($suggestions);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(), [
                'asHtml' => $this->asHtml,
                'suggestions' => $this->suggestions,
            ]
        );
    }
}

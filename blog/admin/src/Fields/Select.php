<?php

namespace Admin\Fields;

use Illuminate\Http\Request;

class Select extends Field
{
    /**
     * @var string
     */
    public static $component = 'v-select-field';

    /**
     * @var array|callable
     */
    public $options = [];

    /**
     * @param  array|callable  $options
     * @return $this
     */
    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function serializeToArray(Request $request)
    {
        return array_merge(
            parent::serializeToArray($request), [
                'options' => $this->getOptions($request),
            ]
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getOptions(Request $request)
    {
        return is_callable($this->options) ? call_user_func($this->options, $request) : $this->options;
    }
}

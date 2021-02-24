<?php

namespace Admin\Fields;

class Select extends Field
{
    /**
     * {@inheritDoc}
     */
    public $component = 'select-field';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param  callable|array  $options
     * @return $this
     */
    public function options($options)
    {
        if (is_callable($options)) {
            $options = call_user_func($options);
        }

        $this->options = collect($options ?: [])->map(function ($key, $value) {
            return [
                'label' => $key,
                'value' => $value,
            ];
        })->values()->all();
    }

    /**
     * @param  bool  $displayUsingLabels
     * @return $this
     */
    public function displayUsingLabels($displayUsingLabels = true)
    {
        if ($displayUsingLabels) {
            $this->displayUsing(function ($value) {
                $option = collect($this->options)->first(function ($option) use ($value) {
                    return $option['value'] == $value;
                });

                return ! $option
                    ? $value
                    : $option['label'];
            });
        }

        return $this;
    }
}

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
            $options = call_user_func($options) ?: [];
        }

        $this->options = collect($options)->map(function ($value, $key) {
            return [
                'value' => $key,
                'label' => $value,
            ];
        })->values()->all();

        return $this;
    }

    /**
     * @param  bool  $displayUsingLabels
     * @return $this
     */
    public function displayUsingLabels($displayUsingLabels = true)
    {
        if ($displayUsingLabels) {
            $this->displayUsing(function ($value) {
                return collect($this->options)->where('value', $value)
                    ->first()['label'] ?? $value;
            });
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(), [
                'options' => $this->options,
            ]
        );
    }
}

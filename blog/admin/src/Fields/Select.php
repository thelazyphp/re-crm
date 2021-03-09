<?php

namespace Admin\Fields;

class Select extends Field
{
    /**
     * {@inheritDoc}
     */
    public $component = 'select-field';

    /**
     * @var bool
     */
    public $searchable = false;

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(), [
                'searchable' => $this->searchable,
            ]
        );
    }

    /**
     * @param  bool  $searchable
     * @return $this
     */
    public function searchable($searchable = true)
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * @param  callable|array  $options
     * @return $this
     */
    public function options($options)
    {
        if (is_callable($options)) {
            $options = call_user_func($options) ?: [];
        }

        return $this->withMeta([
            'options' => collect($options)->map(function ($value, $key) {
                return [
                    'value' => $key,
                    'label' => $value,
                ];
            })->values()->all(),
        ]);
    }

    /**
     * @param  bool  $displayUsingLabels
     * @return $this
     */
    public function displayUsingLabels($displayUsingLabels = true)
    {
        if ($displayUsingLabels) {
            $this->displayUsing(function ($value) {
                return collect($this->meta['options'])->where('value', $value)->first()['label'] ?? $value;
            });
        }

        return $this;
    }
}

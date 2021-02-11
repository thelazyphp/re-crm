<?php

namespace Admin\Fields;

use Illuminate\Http\Request;

class Checkbox extends Field
{
    /**
     * @var string
     */
    public $component = 'checkbox-field';

    /**
     * @var bool
     */
    public $trueValue = true;

    /**
     * @var bool
     */
    public $falseValue = false;

    /**
     * {@inheritDoc}
     */
    public function serializeToJSON(Request $request)
    {
        return array_merge(
            parent::serializeToJSON($request), [
                'component' => $this->component,
                'trueValue' => $this->trueValue,
                'falseValue' => $this->falseValue,
            ]
        );
    }
}

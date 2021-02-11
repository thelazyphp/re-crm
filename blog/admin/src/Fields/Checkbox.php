<?php

namespace Admin\Fields;

use Illuminate\Http\Request;

class Checkbox extends Field
{
    /**
     * @var string
     */
    public static $component = 'v-checkbox-field';

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
    public function serializeToArray(Request $request)
    {
        return array_merge(
            parent::serializeToArray($request), [
                'trueValue' => $this->trueValue,
                'falseValue' => $this->falseValue,
            ]
        );
    }
}

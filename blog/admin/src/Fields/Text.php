<?php

namespace Admin\Fields;

use Illuminate\Http\Request;

class Text extends Field
{
    /**
     * @var string
     */
    public $component = 'text-field';

    /**
     * {@inheritDoc}
     */
    public function serializeToJSON(Request $request)
    {
        return array_merge(
            parent::serializeToJSON($request), [
                'component' => $this->component,
            ]
        );
    }
}

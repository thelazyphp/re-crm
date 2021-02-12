<?php

namespace Admin\Fields;

use Illuminate\Http\Request;

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
    public function serializeToJSON(Request $request)
    {
        return array_merge(
            parent::serializeToJSON($request), [
                'rows' => $this->rows,
            ]
        );
    }
}

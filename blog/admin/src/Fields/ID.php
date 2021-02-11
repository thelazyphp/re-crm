<?php

namespace Admin\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ID extends Field
{
    /**
     * @var string
     */
    public $component = 'text-field';

    /**
     * {@inheritDoc}
     */
    public static function make(
        $label = 'ID',
        $attribute = 'id',
        ?callable $resolveCallback = null
    ) {
        return new static(
            $label,
            $attribute,
            $resolveCallback
        );
    }

    /**
     * {@inheritDoc}
     */
    public function __construct(
        $label = 'ID',
        $attribute = 'id',
        ?callable $resolveCallback = null
    ) {
        parent::__construct(
            $label,
            $attribute,
            $resolveCallback
        );
    }

    /**
     * @return $this
     */
    public function asBigInt()
    {
        $this->resolveCallback = function (
            Request $request,
            Model $model,
            $attribute
        ) {
            return (string) $model->{$attribute};
        };

        return $this;
    }

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

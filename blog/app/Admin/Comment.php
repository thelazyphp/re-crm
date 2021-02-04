<?php

namespace App\Admin;

use App\Resource;

class Comment extends Resource
{
    /**
     * {@inheritDoc}
     */
    public static $model = 'App\Models\Comment';

    /**
     * {@inheritDoc}
     */
    public static $label = 'Коментарий';

    /**
     * {@inheritDoc}
     */
    public static $pluralLabel = 'Коментарии';

    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Admin;

use App\Resource;

class Post extends Resource
{
    /**
     * {@inheritDoc}
     */
    public static $model = 'App\Models\Post';

    /**
     * {@inheritDoc}
     */
    public static $label = 'Пост';

    /**
     * {@inheritDoc}
     */
    public static $pluralLabel = 'Посты';

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

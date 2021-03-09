<?php

namespace App\Admin;

use Admin\Fields\ID;
use Admin\Fields\Text;
use Admin\Resource;
use Illuminate\Http\Request;

class Category extends Resource
{
    /**
     * {@inheritDoc}
     */
    public static $model = 'App\Models\Category';

    /**
     * {@inheritDoc}
     */
    public static $title = 'id';

    /**
     * {@inheritDoc}
     */
    public static $smallTable = false;

    /**
     * {@inheritDoc}
     */
    public static $borderedTable = false;

    /**
     * {@inheritDoc}
     */
    public static $displayInNavigation = true;

    /**
     * {@inheritDoc}
     */
    public function fields(Request $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->sortable()->required()->rules('required', 'string', 'max:255'),
        ];
    }
}

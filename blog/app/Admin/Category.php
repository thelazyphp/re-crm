<?php

namespace App\Admin;

use App\Fields\Text;
use App\Resource;
use Illuminate\Http\Request;

class Category extends Resource
{
    public static $model = 'App\Models\Category';

    public static $label = 'Categories';

    public function fields(Request $request): array
    {
        return [
            Text::make('Name'),
            Text::make('Slug'),
        ];
    }
}

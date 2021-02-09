<?php

namespace App\Admin;

use App\Fields\Checkbox;
use App\Fields\Text;
use App\Fields\Textarea;
use App\Resource;
use Illuminate\Http\Request;

class Post extends Resource
{
    public static $model = 'App\Models\Post';

    public static $label = 'Posts';

    public function fields(Request $request): array
    {
        return [
            Text::make('Title'),
            Text::make('Slug'),
            Textarea::make('Content'),
            Checkbox::make('Published'),
        ];
    }
}

<?php

namespace App\Admin;

use Admin\Fields\Boolean;
use Admin\Fields\ID;
use Admin\Fields\Select;
use Admin\Fields\Text;
use Admin\Fields\Textarea;
use Admin\Resource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Post extends Resource
{
    /**
     * {@inheritDoc}
     */
    public static $model = 'App\Models\Post';

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

            Select::make('Category', 'category_id')->sortable()->nullable()->searchable()->displayUsingLabels()->options(Category::all()->mapWithKeys(function ($category) {
                return [$category->id => $category->name];
            })),

            Text::make('Title')->sortable()->required()->rules(['required', 'string', 'max:255']),

            Text::make('Slug', function ($model) {
                return Str::slug($model->title);
            }),

            Textarea::make('Content')->required()->rules('required', 'string'),
            Boolean::make('Is Published')->sortable(),
        ];
    }
}

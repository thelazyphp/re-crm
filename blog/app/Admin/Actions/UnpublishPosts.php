<?php

namespace App\Admin\Actions;

use Admin\Action;
use Admin\ActionFields;
use Illuminate\Support\Collection;

class UnpublishPosts extends Action
{
    /**
     * {@inheritDoc}
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each(function ($model) {
            $model->is_published = false;
            $model->save();
        });
    }
}

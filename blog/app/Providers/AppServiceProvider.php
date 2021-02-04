<?php

namespace App\Providers;

use App\Admin;
use App\Admin\Comment;
use App\Admin\Post;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Admin::resources([
            Post::class,
            Comment::class,
        ]);

        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

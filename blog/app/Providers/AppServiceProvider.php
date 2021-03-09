<?php

namespace App\Providers;

use Admin\Admin;
use App\Admin\Category;
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
            Category::class,
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

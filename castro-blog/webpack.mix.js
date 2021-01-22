const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setResourceRoot('/castro-blog/');

mix.js('resources/js/entry-client.js', 'public/js').vue()
mix.js('resources/js/entry-server.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);

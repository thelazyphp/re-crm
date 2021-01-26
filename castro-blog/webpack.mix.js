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
    .js('resources/js/entry-server.js', 'public/js').vue()
    .js('resources/js/admin/app.js', 'public/js/admin').vue()
    .sass('resources/css/app.scss', 'public/css')
    .sass('resources/css/admin/app.scss', 'public/css/admin');
    // .postCss('resources/css/app.css', 'public/css', [
    //     //
    // ]);

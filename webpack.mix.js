const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.autoload({
    'jquery': ['jQuery', '$'],
})

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/admin-lte.js', 'public/js/admin-lte.js')
    .sass('resources/sass/admin-lte.scss', 'public/css/admin-lte.css').version();

if (mix.inProduction()) {
    mix.version();
}

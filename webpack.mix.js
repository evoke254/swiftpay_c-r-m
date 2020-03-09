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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .styles('resources/sass/kahaki.css', 'public/css/kahaki.css')    
    .copy('resources/themes/css/kahaki/animate.min.css', 'public/css/animate.css')
    .copy('resources/themes/css/kahaki/style.min.css', 'public/css/style.min.css')
    .copy('resources/themes/css/kahaki/style-responsive.min.css', 'public/css/style-responsive.min.css')
    .copy('resources/themes/css/kahaki/theme/green.css', 'public/css/theme.css')
    .copy('resources/themes/css/kahaki/tempus.min.css', 'public/css/tempus.css')
    .copy('resources/js/pace/pace.min.js', 'public/js/kahaki/pace.js');

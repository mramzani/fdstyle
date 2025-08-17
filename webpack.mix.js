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

mix.js([
    'public/assets/panel/vendor/libs/jquery/jquery.js',
    'public/assets/panel/vendor/libs/popper/popper.js',
    'public/assets/panel/vendor/js/bootstrap.js',
    'public/assets/panel/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
    'public/assets/panel/vendor/libs/hammer/hammer.js',
    'public/assets/panel/vendor/js/menu.js',
    'public/assets/panel/vendor/libs/sweetalert2/sweetalert2.js',
], 'public/js/app.js');
mix.js([
    'public/assets/panel/vendor/js/helpers.js',
    'public/assets/panel/vendor/js/template-customizer.js',
], 'public/js/head_app.js')
    .sass('resources/sass/panel/app.scss','public/assets/panel/css/app.css').sourceMaps().version();

mix.styles([
    'public/assets/front/css/plugins/jquery.classycountdown.min.css',
    'public/assets/front/css/plugins/swiper.min.css',
    'public/assets/front/css/plugins/select2.min.css',
    'public/assets/front/css/plugins/sweetalert2.min.css',
    'public/assets/front/css/plugins/nouislider.min.css',
    'public/assets/front/css/theme.css',
    'public/assets/front/css/custom.css',
],'public/css/front.css');

mix.js([
    'public/assets/front/js/theme.js',
],'public/js/front.js');

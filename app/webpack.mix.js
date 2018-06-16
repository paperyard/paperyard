let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .combine([
        'node_modules/adminbsb-materialdesign/plugins/bootstrap/css/bootstrap.css',
        'node_modules/adminbsb-materialdesign/plugins/node-waves/waves.css',
        'node_modules/adminbsb-materialdesign/plugins/animate-css/animate.css',
        'node_modules/adminbsb-materialdesign/css/materialize.css',
        'node_modules/adminbsb-materialdesign/css/style.css',
        'node_modules/adminbsb-materialdesign/css/themes/theme-blue.css',
    ],  'public/css/core_mix.css')
    .combine([

    ], 'public/css/dashboard.css')
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/jquery-knob/jquery.knob.min.js',
       'node_modules/adminbsb-materialdesign/js/pages/charts/jquery-knob.js'
    ], 'public/js/dashboard.js')
    .combine([
        'node_modules/adminbsb-materialdesign/plugins/jquery/jquery.min.js',
        'node_modules/adminbsb-materialdesign/plugins/bootstrap/js/bootstrap.js',
        'node_modules/adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.js',
        'node_modules/adminbsb-materialdesign/plugins/node-waves/waves.js',
        'node_modules/adminbsb-materialdesign/js/admin.js',
    ], 'public/js/core_mix.js')

mix.browserSync('http://localhost:8000');


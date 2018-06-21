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

mix.js('resources/assets/js/app.js', 'public/static/js')
    .combine([
        'node_modules/adminbsb-materialdesign/plugins/bootstrap/css/bootstrap.css',
        'node_modules/adminbsb-materialdesign/plugins/node-waves/waves.css',
        'node_modules/adminbsb-materialdesign/plugins/animate-css/animate.css',
        'node_modules/adminbsb-materialdesign/css/materialize.css',
        'node_modules/adminbsb-materialdesign/css/style.css',
        'node_modules/adminbsb-materialdesign/css/themes/theme-blue.css',
    ],  'public/static/css/core_mix.css')
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/jquery-knob/jquery.knob.min.js',
       'node_modules/adminbsb-materialdesign/js/pages/charts/jquery-knob.js'
    ], 'public/static/js/dashboard.js')
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/sweetalert/sweetalert.css',
    ], 'public/static/css/admin_dashboard.css')
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js',
       'node_modules/adminbsb-materialdesign/plugins/sweetalert/sweetalert.min.js',
    ], 'public/static/js/admin_dashboard.js')
    .combine([
        'node_modules/adminbsb-materialdesign/plugins/jquery/jquery.min.js',
        'node_modules/adminbsb-materialdesign/plugins/bootstrap/js/bootstrap.js',
        'node_modules/adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.js',
        'node_modules/adminbsb-materialdesign/plugins/node-waves/waves.js',
        'node_modules/adminbsb-materialdesign/js/admin.js',
    ], 'public/static/js/core_mix.js')

mix.browserSync('http://localhost');


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
    <script src="../../plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="../../plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
*/

mix.js('resources/assets/js/app.js', 'public/static/js')
    // MAIN CSS ASSETS
    .combine([
        'node_modules/adminbsb-materialdesign/plugins/bootstrap/css/bootstrap.css',
        'node_modules/adminbsb-materialdesign/plugins/node-waves/waves.css',
        'node_modules/adminbsb-materialdesign/plugins/animate-css/animate.css',
        'node_modules/adminbsb-materialdesign/css/materialize.css',
        'node_modules/adminbsb-materialdesign/css/style.css',
        'node_modules/adminbsb-materialdesign/css/themes/theme-blue.css',
        'node_modules/adminbsb-materialdesign/plugins/sweetalert/sweetalert.css',
        'node_modules/hover.css/css/hover.css',
    ],  'public/static/css/core_mix.css')
    // USER DASHBOARD ASSETS
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/jquery-knob/jquery.knob.min.js',
       'node_modules/adminbsb-materialdesign/js/pages/charts/jquery-knob.js',
       'node_modules/chart.js/dist/Chart.min.js',
    ], 'public/static/js/dashboard.js')
    // DOCUMENTS INSIDE FOLDER ASSETS
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css',
    ], 'public/static/css/inside_folder.css')
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/jquery-datatable/jquery.dataTables.js',
       'node_modules/adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js',
        'node_modules/adminbsb-materialdesign/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js',
    ], 'public/static/js/inside_folder.js')
    // DOCUMENT UPLOADER ASSETS
    .combine([
       'resources/assets/dropzone/dropzone.css',
    ], 'public/static/css/document_uploader.css')
    .combine([
       'resources/assets/dropzone/dropzone.js',
    ], 'public/static/js/document_uploader.js')
    // SEARCH DOCUMENTS ASSETS
    .combine([
       'node_modules/chart.js/dist/Chart.min.js',
    ], 'public/static/js/search_documents.js')
    // FOLDERS ASSETS
    .combine([
       'node_modules/chart.js/dist/Chart.min.js',
    ], 'public/static/js/folders.js')
    // DOCUMENTS VIEW ASSETS
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
       'node_modules/slick-carousel/slick/slick.css',
       'node_modules/slick-carousel/slick/slick-theme.css'
    ], 'public/static/css/document.css')
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/jquery-inputmask/jquery.inputmask.bundle.js',
       'node_modules/adminbsb-materialdesign/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js',
       'node_modules/slick-carousel/slick/slick.min.js'
    ], 'public/static/js/document.js')
    // CUSTOMIZE PDF ASSETS
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/waitme/waitMe.css',
       'node_modules/ng-material-floating-button/mfb/dist/mfb.min.css',
    ], 'public/static/css/customize_pdf.css')
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/waitme/waitMe.js',
       'node_modules/ng-material-floating-button/src/mfb-directive.js',
    ], 'public/static/js/customize_pdf.js')
    // REMINDERS ASSETS
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
       'node_modules/adminbsb-materialdesign/plugins/waitme/waitMe.css',
       'node_modules/ng-material-floating-button/mfb/dist/mfb.min.css',
    ], 'public/static/css/reminders.css')
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/autosize/autosize.js',
       'node_modules/adminbsb-materialdesign/plugins/momentjs/moment.js',
       'node_modules/adminbsb-materialdesign/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js',
       'node_modules/adminbsb-materialdesign/plugins/waitme/waitMe.js',
       'node_modules/ng-material-floating-button/src/mfb-directive.js',
    ], 'public/static/js/reminders.js')
    .copyDirectory('resources/assets/datetimepicker/bootstrap-material-datetimepicker.css', 'public/static/css/bootstrap-material-datetimepicker.css')
    .copyDirectory('resources/assets/datetimepicker/bootstrap-material-datetimepicker.js', 'public/static/js/bootstrap-material-datetimepicker.css')
    //NOTIFICATIONS ASSETS
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
       'node_modules/adminbsb-materialdesign/plugins/waitme/waitMe.css',
    ], 'public/static/css/notifications.css')
    .combine([
       'node_modules/adminbsb-materialdesign/plugins/autosize/autosize.js',
       'node_modules/adminbsb-materialdesign/plugins/momentjs/moment.js',
       'node_modules/adminbsb-materialdesign/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js',
       'node_modules/adminbsb-materialdesign/plugins/waitme/waitMe.js',
    ], 'public/static/js/notifications.js')
    // COPY FONTS
    .copyDirectory('resources/assets/fonts', 'public/static/fonts')
    .copyDirectory('node_modules/slick-carousel/slick/fonts', 'public/static/css/fonts')
    .copyDirectory('node_modules/slick-carousel/slick/ajax-loader.gif', 'public/static/css')
    // MAIN JS ASSETS
    .combine([
        'node_modules/adminbsb-materialdesign/plugins/jquery/jquery.min.js',
        'node_modules/adminbsb-materialdesign/plugins/bootstrap/js/bootstrap.js',
        //'node_modules/adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.js',
        'node_modules/adminbsb-materialdesign/plugins/node-waves/waves.js',
        'node_modules/adminbsb-materialdesign/js/admin.js',
        'node_modules/adminbsb-materialdesign/plugins/bootstrap-notify/bootstrap-notify.js',
        'node_modules/adminbsb-materialdesign/plugins/sweetalert/sweetalert.min.js',
        'resources/assets/js/angular.min.js',
        'resources/assets/js/ui-bootstrap-tpls-0.10.0.min.js',
        'resources/assets/js/angularjs-sanitize.min.js',
        'node_modules/adminbsb-materialdesign/js/pages/ui/tooltips-popovers.js',
    ], 'public/static/js/core_mix.js')

mix.browserSync('http://localhost');


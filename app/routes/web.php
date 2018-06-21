<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// route for changing language
Route::get('language/{locale}', function ($locale) {

	session()->put('new_lang', $locale);

	app()->setLocale(session()->get('new_lang'));

	return redirect()->back();

});

// middleware for setting the current language
Route::group(['middleware' => 'localize'], function () {

    //default route
	Route::get('/', function () {
		return view('auth/login');
	})->middleware('guest');

    // Authentication routes
	Auth::routes();

    // Generate test admin
    Route::get('/makeAdmin', 'adminController@generateAdmin');

    // Group routes can only be accessed if authenticated
	Route::group(['middleware' => 'auth'], function () {


        // users routess
        Route::group(['middleware' => 'auth_user'], function () {
			Route::get('/dashboard', function(){
				return view('pages/dashboard');
			});
			Route::get('/search', function(){
				return view('pages/search_docs');
			});
			Route::get('/notifications', function(){
				return view('pages/notifications');
			});
			Route::get('/folders', function(){
				return view('pages/folders');
			});
			Route::get('/share', function(){
				return view('pages/share');
			});
			Route::get('/settings', function(){
				return view('pages/settings');
			});
        });
        // admin routess
        Route::group(['middleware' => 'auth_admin'], function () {
        	//admin home route
            Route::get('/admin_dashboard', 'adminController@index');
            //apply ocr route
            Route::post('/run_ocrmypdf', 'adminController@runOCRMYPDF');
            //remove document route.
            Route::get('remove_file/{id}/{filename_org}/{filename_ocr}/{filename_img}', 'adminController@removeFILE');

            Route::get('tt', 'adminController@tt');
        });


	});

});


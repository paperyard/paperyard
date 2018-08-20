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

// CHANGE LOCALIZATION -------------------------------------------------------------

Route::get('language/{locale}', function ($locale) {
	session()->put('new_lang', $locale);
	app()->setLocale(session()->get('new_lang'));
	return redirect()->back();
});

// ----------------------------------------------------------------------------------


// middleware for setting the current language ----------------------------------------------------------------

Route::group(['middleware' => 'localize'], function () {

// Share document public --------------------------------------------------------------------------------------

Route::get('/share/{user_name}/{share_hash}', 'shareDocumentController@shareDocument');
Route::post('/share/public/verify_password', 'shareDocumentController@verifyShared');
Route::get('/share/{user_name}/{share_hash}/{password}', 'shareDocumentController@shareDocumentWithPass');

// ------------------------------------------------------------------------------------------------------------

    //default route -------------------------------------------------------------------------------------------
	Route::get('/', function () { return view('auth/login'); })->middleware('guest');

    // AUTH routes --------------------------------------------------------------------------------------------
	Auth::routes();
    Route::get('register/verify/{token}',                       'Auth\RegisterController@verify');

    // GENERATE admin -----------------------------------------------------------------------------------------
    Route::get('/makeAdmin',                                    'adminController@generateAdmin');

    // AUTHENTICATED routes --------------------------------------------------------------------------------------------

	Route::group(['middleware' => 'auth'], function () {

        // Authenticated  routes ---------------------------------------------------------------------------------------
        Route::group(['middleware' => 'auth_user'], function () {

            //dashboard routes =========================================================================================
            Route::get('/dashboard',                            'dashboardController@index');
            Route::get('/get_docs_edit_archive',                'dashboardController@toEditDocs');
            Route::post('/dashboard_search_documents',          'dashboardController@searchDocuments');
            Route::post('/dashboard_show_autocomplete',         'dashboardController@searchAutoComplete');
            Route::post('/dashboard_search_specific_doc',       'dashboardController@searchSpecificDocuments');

            //search document routes ===================================================================================
            Route::get('/search',                               'searchDocumentController@index');
            Route::post('/search/documentsWithFilter',          'searchDocumentController@searchDocumentWithFilter');
            Route::post('/search/documents',                    'searchDocumentController@searchDocument');
            Route::post('/search/typhead',                      'searchDocumentController@typeHead');

            //notifications routes =====================================================================================
            Route::get('/notifications',                        'notificationsController@index');
            Route::get('/notifications/create',                 'notificationsController@newNotification');
            Route::post('/notifications/save_update',           'notificationsController@createUpdateNotification');
            Route::get('/notifications/edit/{notify_id}',       'notificationsController@editNotification');
            Route::post('/notifications/delete',                'notificationsController@deleteNotification');

            //Reminders route  =========================================================================================
            Route::get('/reminders',                            'remindersController@index');
            Route::get('/get_reminders',                        'remindersController@getReminders');
            Route::get('/reminders/edit/{reminder_id}',         'remindersController@editReminder');
            Route::get('/reminder/create',                      'remindersController@createReminder');
            Route::post('/reminder_documents',                  'remindersController@reminderDocuments');
            Route::post('/reminder_save_update',                'remindersController@save_updateReminder');
            Route::post('/reminder_delete',                     'remindersController@deleteReminder');

			//folders routes ===========================================================================================
			Route::get( '/folders',                             'foldersController@index');
			Route::get( '/folders/return',                      'foldersController@returnFolders');
            Route::post('/folders/new_folder',                  'foldersController@newFolder');
            Route::post('/folders/delete',                      'foldersController@deleteFolder');
            Route::post('/folders/rename',                      'foldersController@renameFolder');

            //inside folder ============================================================================================
            Route::get( '/folder/{folder_id}',                  'foldersController@openFolder');
            Route::get( '/folder/document/upload/{folder_id}',  'foldersController@documentUploader');
            Route::post('/folders/upload_user_docs',            'foldersController@uploadUserDocs');
            Route::post('/folders/show_documents',              'foldersController@folderDocuments');

            //documents routes =========================================================================================
            Route::post('/document/delete',                      'documentsController@deleteDocument');
            Route::post('/document/approve',                     'documentsController@approveDocument');
            Route::get( '/document/{doc_id}',                    'documentsController@viewDocument');
            Route::post('/document/update',                      'documentsController@updateDocument');
            Route::post('/document/share',                       'documentsController@shareDocument');

            // new documents to edit ===================================================================================
            Route::get( '/new_documents',                        'documentsController@new_documents');
            Route::get( '/return_new_documents',                 'documentsController@return_new_documents');

            // customize pdf ===================================================================================
            Route::get( '/customize_pdf/{doc_id}',               'customizePdfController@index');
            Route::post( '/getDocPages',                         'customizePdfController@returnCustomizeDoc');
            Route::post('cstm_removeDocPages',                   'customizePdfController@removeDocPages');
            Route::post('cstm_rotateDocPages',                   'customizePdfController@rotateDocPages');

            // merge pdf ===================================================================================
            Route::get('/merge_pdf',                             'mergeDocumentController@index');
            Route::post('/merge_docs_autocomplete',              'mergeDocumentController@mdAutocomplete');
            Route::post('/merge_doc_select',                     'mergeDocumentController@selectDoc');
            Route::post('/mergeDocuments',                       'mergeDocumentController@mergeDocuments');

            // archive documents =======================================================================================
            Route::get( '/archives',                             'documentsController@archives');
            Route::get( '/return_archives',                      'documentsController@returnArchives');

            //Upload documents  =========================================================================================

            Route::get( '/upload_documents',                    'uploadDocumentController@index');
            Route::post('/upload_documents',                    'uploadDocumentController@fileUpload');

            //Document archives
            Route::post('/move_folders',                        'documentsController@moveFolders');

            //testing ##################################################################################################
            Route::get('/server_test',                           'foldersController@serverTest');

            Route::get('/mail_test', function(){
                     return new App\Mail\sendNotification();
            });

            // #########################################################################################################

            // Share document ------------------------------------------------------------------------------------------

            Route::get('/share',                                'shareDocumentController@index');
            Route::get('/share/get_shared_documents',           'shareDocumentController@returnSharedDocs');
            Route::post('/share/remove_shared',                 'shareDocumentController@removeShared');
            Route::post('/share/generate_password',             'shareDocumentController@generatePassword');

            // ---------------------------------------------------------------------------------------------------------

			Route::get('/settings',                             'settingsController@index');
            Route::post('/settings/change_timezone',            'settingsController@changeTimeZone');

            // ---------------------------------------------------------------------------------------------------------

            // user account settings -------------------------------------------------------------------------------------

            Route::get('/account_settings',                     'userController@accountSettings');
            Route::post('/account_settings/email_update',       'userController@emailUpdate');
            Route::post('/account_settings/passowrd_update',    'userController@passwordUpdate');

            //------------------------------------------------------------------------------------------------------------


        });
        // USERS  routes -------------------------------------------------------------------------------------------------

        // ADMIN  routes -------------------------------------------------------------------------------------------------

        Route::group(['middleware' => 'auth_admin'], function () {

            //admin home route ---------------------------------------
            Route::get('/admin_dashboard',                      'adminController@index');

        });
        // ADMIN  routes -------------------------------------------------------------------------------------------------

	});
    // AUTHENTICATED routes -------------------------------------------------------------------------------------------

});


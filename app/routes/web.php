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
	Route::get('/', 'userController@welcome')->middleware('guest');

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
            Route::view('/failed_ocr_documents',                'pages/failed_documents');
            Route::get('/ocr_failed_documents',                 'dashboardController@return_ocr_failed_documents');

            //search document routes ===================================================================================
            Route::get('/search',                               'searchDocumentController@index');
            Route::get('/search/barchar_datas',                 'searchDocumentController@getBarchartDatas');
            Route::post('/search/documents',                    'searchDocumentController@searchDocument');
            Route::post('/search/typhead',                      'searchDocumentController@typeHead');

            //Search save routes ========================================================================================
            Route::get('/ss_user',                              'saveSearchController@userSavedSearch');
            Route::post('/ss_save',                             'saveSearchController@saveSearch');
            Route::post('/ss_rename',                           'saveSearchController@renameSaveSearch');
            Route::post('/ss_delete',                           'saveSearchController@deleteSaveSearch');


            //common search routes =====================================================================================
            Route::post('/common_search/autocomplete',          'commonSearchDocumentController@autoComplete');
            Route::post('/common_search/search',                'commonSearchDocumentController@searchDocuments');

            //notifications routes =====================================================================================
            Route::get('/notifications',                        'notificationsController@index');
            Route::get('/notifications/create',                 'notificationsController@newNotification');
            Route::post('/notifications/save_update',           'notificationsController@createUpdateNotification');
            Route::get('/notifications/edit/{notify_id}',       'notificationsController@editNotification');
            Route::post('/notifications/delete',                'notificationsController@deleteNotification');
            Route::get('/notifications/line_chart_datas',       'notificationsController@getNotificationLineChartDatas');

            //Reminders route  =========================================================================================
            Route::get('/reminders',                            'remindersController@index');
            Route::get('/get_reminders',                        'remindersController@getReminders');
            Route::get('/reminders/new',                        'remindersController@newReminder');
            Route::post('/reminders/create',                    'remindersController@makeReminder');
            Route::get('/reminder/edit/{reminder_id}',          'remindersController@editReminder');
            Route::post('/reminders/get_to_edit',               'remindersController@getToEditReminder');
            Route::post('/reminders/doc_view',                  'remindersController@getTaskListForDocView');

            Route::post('/reminders/update',                    'remindersController@updateReminder');
            Route::post('/reminders/delete',                    'remindersController@deleteReminder');
            Route::post('/reminders/task_complete',             'remindersController@taskComplete');

            Route::post('/reminders/autocomplete',              'remindersController@autoComplete');
            Route::post('/reminders/search',                    'remindersController@searchDocuments');

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
            Route::post('/document/delete',                     'documentsController@deleteDocument');
            Route::post('/document/approve',                    'documentsController@approveDocument');
            Route::get( '/document/{doc_id}',                   'documentsController@viewDocument');
            Route::post('/document/update',                     'documentsController@updateDocument');
            Route::post('/document/share',                      'documentsController@shareDocument');

            // customize pdf ===================================================================================
            Route::get( '/customize_pdf/{doc_id}',              'customizePdfController@index');
            Route::post('/getDocPages',                         'customizePdfController@returnCustomizeDoc');
            Route::post('cstm_removeDocPages',                  'customizePdfController@removeDocPages');
            Route::post('cstm_rotateDocPages',                  'customizePdfController@rotateDocPages');

            // merge pdf ===================================================================================
            Route::get('/merge_pdf',                            'mergeDocumentController@index');
            Route::post('/merge_docs_autocomplete',             'mergeDocumentController@mdAutocomplete');
            Route::post('/merge_doc_select',                    'mergeDocumentController@selectDoc');
            Route::post('/mergeDocuments',                      'mergeDocumentController@mergeDocuments');

            // archive documents =======================================================================================
            Route::get( '/archives',                            'documentsController@archives');
            Route::get( '/return_archives',                     'documentsController@returnArchives');

            //Upload documents  =========================================================================================
            Route::get( '/upload_documents',                    'uploadDocumentController@index');
            Route::post('/upload_documents',                    'uploadDocumentController@fileUpload');

            //Document archives
            Route::post('/move_folders',                        'documentsController@moveFolders');

            //testing ##################################################################################################
            Route::get('/server_test',                          'foldersController@serverTest');
            // #########################################################################################################

            // Share document ================================================================================================
            Route::get('/share',                                'shareDocumentController@index');
            Route::get('/share/get_shared_documents',           'shareDocumentController@returnSharedDocs');
            Route::post('/share/remove_shared',                 'shareDocumentController@removeShared');
            Route::post('/share/generate_password',             'shareDocumentController@generatePassword');

            // Settings ======================================================================================================
			Route::get('/settings',                             'settingsController@index');
            Route::post('/settings/change_timezone',            'settingsController@changeTimeZone');
            Route::get('/settings/get_d_filename_format',       'settingsController@returnDownloadFilenameFormat');
            Route::post('/settings/update_filename_format',     'settingsController@updateDownloadFilenameFormat');

            // Address Book ==================================================================================================
            Route::get('/address_book/create',                  'addressBookController@create_index');
            Route::post('/address_book/save',                   'addressBookController@saveAddressBook');
            
            Route::get('/address_book',                         'addressBookController@addressBook');
            Route::get('/address_book/list',                    'addressBookController@addressBookList');
            Route::post('/address_book/possible_recipient',     'addressBookController@updatePossibleRecipient');
            
            Route::get('/address_book/edit/{ab_id}',            'addressBookController@editAddressBook');
            Route::post('/address_book/update',                 'addressBookController@updateAddressBook');
            
            Route::get('/address_book/create_child/{parent_id}', 'addressBookController@createChildPage');
            Route::post('/address_book/save_created_child',      'addressBookController@saveCreatedChild');

            Route::post('/address_book/delete',                 'addressBookController@deleteAddressBook');

            // Address Book search ===========================================================================================
            Route::post('/address_book/auto_complete',          'addressBookController@autoComplete');
            Route::post('/address_book/search_address',         'addressBookController@searchAddress');


            // user account settings =========================================================================================
            Route::get('/account_settings',                     'userController@accountSettings');
            Route::post('/account_settings/email_update',       'userController@emailUpdate');
            Route::post('/account_settings/passowrd_update',    'userController@passwordUpdate');

            // IMAP -=========================================================================================================
            Route::get('/imap/new_credential',                  'imap_controller@index');
            Route::post('/imap/save_new_credentials',           'imap_controller@saveCredentials');
            Route::get('/imap/list_of_credentials',             'imap_controller@returnCredentials');
            Route::post('/imap/delete_credentials',             'imap_controller@removeCredentials');

            // FTP ============================================================================================================
            Route::get('/ftp_create_credentials',                      'ftp_controller@index');
            Route::post('/ftp_create_credentails/new_credential',      'ftp_controller@saveNewCredentails');
            Route::get('/ftp_create_credentials/list_of_credentials',  'ftp_controller@returnFTPCredentials');   
            Route::post('/ftp_create_credentails/delete',              'ftp_controller@deleteFTPCredentials');
            //--connect--
            Route::get('/ftp_connect/{ftp_id}',                        'ftp_controller@connectFTP'); 
            Route::post('/ftp_connect/ftp_files',                      'ftp_controller@FTP_connect_get_files'); 
            Route::post('/ftp_connect/download_files',                 'ftp_controller@downloadFiles'); 



            // Files routes ===================================================================================================
            Route::get('/files/{type}/{filename}',              'filesController@index');
            Route::get('/files_public/{type}/{filename}',       'filesController@publicFiles');




            Route::get('/demo', function () {
                return new App\Mail\reminder();
            });


    
        });
        // USERS  routes =====================================================================================================

        // ADMIN  routes =====================================================================================================
        Route::group(['middleware' => 'auth_admin'], function () {
            //admin home route ---------------------------------------
            Route::get('/admin_dashboard',                      'adminController@index');
        });
        // ADMIN  routes -------------------------------------------------------------------------------------------------

	});
    // AUTHENTICATED routes -------------------------------------------------------------------------------------------

});


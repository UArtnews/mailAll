<?php
/*
 * 1.  Super Admin Routes

 * 2.  Public  Routes
 *
 * 3.  Public Logged In Routes
 *
 * 4.  Public Logged In Ajax
 *
 * 5.  Editor Logged In Ajax Routes
 *
 * 6.  Editor Logged In Routes
 *
 */

//Public landing page
Route::get('/', 'HomeController@index');

//Editor landing page
Route::group(array('before' => 'auth|force.ssl'), function(){
    Route::get('editors', 'HomeController@editors');
});

//////////////////////////////////
//                              //
// 1.  SuperAdmin Routes        //
//                              //
//////////////////////////////////
Route::group(array('before' => 'force.ssl|superAuth'), function() {
	Route::resource('/admin/submission/update', 'AdminController@updateSubmission');
    Route::any('admin/{action?}/{subAction?}/{id?}', 'AdminController@route');
});

//////////////////////////
//                      //
//   2. Public Routes   //
//                      //
//////////////////////////

//Show logs
Route::get('/logs/{instanceName}/{fileName}', 'MiscController@showLogs');

//Show live publication in stripped down reader
Route::get('/{instanceName}/', 'PublicController@showPublicHome');

//Show this article with share buttons and stuff
Route::get('/{instanceName}/article/{article_id}', 'PublicController@showArticle');

//Show this publication in stripped down reader
Route::get('/{instanceName}/archive/{publication_id}', 'PublicController@showPublication');

//Show archives
Route::get('/{instanceName}/archive/', 'PublicController@showArchive');

//Show search results for public users
Route::get('/{instanceName}/search', 'PublicController@search');

//Return image lists for ckeditors
Route::get('/json/{instanceName}/images', 'MiscController@imageJSON');

//Return image lists for Tiny MCE
Route::get('/image-picker/{instanceName}/images', 'MiscController@imagePicker');

//////////////////////////////////
//                              //
// 3.  Public Logged In Routes  //
//                              //
//////////////////////////////////
Route::group(array('before' => 'force.ssl'), function(){
    Route::get('/presubmit/{instanceName}', 'SubmissionController@preSubmit');
    Route::get('/submit/{instanceName}', 'SubmissionController@index');
});

////////////////////////////////
//                            //
// 4.  Public Logged In Ajax  //
//                            //
////////////////////////////////
Route::group(array('before' => 'force.ssl|registerSubmitter'), function() {
	
    Route::resource('/resource/submission', 'SubmissionController');
});

//////////////////////////////////////
//                                  //
// 5.  Editor Logged In Ajax Routes //
//                                  //
//////////////////////////////////////

Route::group(array('before' => 'force.ssl'), function(){
    Route::post('/promote/{instanceName}/{submission_id}', 'SubmissionController@promoteSubmission');

    Route::resource('/resource/article', 'ArticleController');

    Route::resource('/resource/publication', 'PublicationController');

    Route::resource('/resource/image', 'ImageController');

    Route::post('/resource/publication/updateOrder/{publication_id}', 'PublicationController@updateOrder');

    Route::post('/resource/publication/updateType/{publication_id}', 'PublicationController@updateType');

    //Handle Article Carts
    //Add to cart
    Route::post('/cart/{instanceName}/add', 'MiscController@cartAdd');
	
	//Test Add Issued
	//Route::get('/cart/{instanceName}/addissued', 'MiscController@cartAddIssued');
	
	//Add to cart
    Route::post('/cart/{instanceName}/addissued', 'MiscController@cartAddIssued');

    //Remove from cart
    Route::post('/cart/{instanceName}/remove', 'MiscController@cartRemove');

    Route::post('/cart/{instanceName}/clear', 'MiscController@cartClear');

    //Post routes so AJAX can grab editable regions
    Route::any('/editable/article/{article_id}/{publication_id?}', 'MiscController@articleAJAX');
	
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+++++++++++++++MIGRATION ROUTES for PUBLICATION LOGS+++++++++++++++++++
	Route::any('/show/reportMigration/{instanceName}', 'MiscController@getDataMigration');
	Route::any('/preprocess/reportMigration/{instanceName}', 'MiscController@preprocessReportMigration');
	Route::any('/save/reportMigration/{instanceName}', 'MiscController@saveReportMigration');
	Route::any('/upload/reportMigration/{instanceName}', 'MiscController@uploadDataMigration');
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+++++++++++++++BACKDOOR SIGNIN ROUTES++++++++++++++++++++++++++++++++++
	Route::any('/user/signin/', 'AdminController@nonShibSignin');
	Route::any('/user/signin/auth', 'AdminController@doNonShibAuth');
	
});

//////////////////////////////////
//                              //
// 6.  Editor Logged In Routes  //
//                              //
//////////////////////////////////
Route::group(array('before' => 'force.ssl|editAuth'), function(){
	
	//Specific Saving Controller for saving Publication Logs Excel doc
	Route::get('/saveExcel/{instanceName}/{action?}/{subAction?}', 'EditorController@route');
	
	Route::get('/show/{instanceName}/{action?}/{subAction?}', 'EditorController@route');
	
    Route::get('/edit/{instanceName}/{action?}/{subAction?}', 'EditorController@route');

    //Specific Saving Controller for things in the editor like saving settings
    Route::get('/deleteProfile/{instanceName}/{profileName}', 'EditorController@deleteProfile');

    //Specific Saving Controller for things in the editor like saving settings
    Route::get('/loadProfile/{instanceName}/{profileName}', 'EditorController@loadProfile');

    //Specific Saving Controller for things in the editor like saving settings
    Route::post('/save/{instanceName}/{action}', 'EditorController@save');

    //Perform and send a mail merge
    Route::any('mergeEmail/{instanceName}/{publication_id}', 'EmailController@mergeEmail');

    //Send an email
    Route::any('sendEmail/{instanceName}/{publication_id}', 'EmailController@sendEmail');
	
	//Specific Saving Controller for saving settings>Profiles>Save Email Favorites
    Route::post('/save/{instanceName}/SaveEmailFavorites/{subAction?}', 'EditorController@SaveEmailFavorites');
	
    //Specific Saving Controller for deleteing settings>Profiles>Delete Email Favorites
    Route::get('/deleteEmailFav/{instanceName}/{emailFavID}', 'EditorController@deleteEmailFav');
    
	//Specific Saving Controller for saving settings>Profiles>Save Email Audiences
    Route::post('/saveAudience/{instanceName}/{action?}/{subAction?}', 'EditorController@SaveEmailAudiences');
	
    //Specific Saving Controller for deleteing settings>Profiles>Delete Email Audiences
    Route::get('/deleteEmailAud/{instanceName}/{emailAudID}', 'EditorController@deleteEmailAudience');
	//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//+++++++++++++++These below routes are only for Migration+
	Route::get('/preview/{instanceName}/{publication_id}', 'EditorController@previewEmail');
	
	
});
//////////////////////////////////
//                              //
// 6.  Editor Backdoor Routes   //
//                              //
//////////////////////////////////
Route::group(array('before' => 'force.ssl'), function(){
    Route::get('/edit_offline/{instanceName}/{action?}/{subAction?}', 'EditorController@route');

    //Specific Saving Controller for things in the editor like saving settings
    Route::get('/deleteProfile_offline/{instanceName}/{profileName}', 'EditorController@deleteProfile');

    //Specific Saving Controller for things in the editor like saving settings
    Route::get('/loadProfile_offline/{instanceName}/{profileName}', 'EditorController@loadProfile');

    //Specific Saving Controller for things in the editor like saving settings
    Route::post('/save_offline/{instanceName}/{action}', 'EditorController@save');

    //Perform and send a mail merge
    Route::any('mergeEmail_offline/{instanceName}/{publication_id}', 'EmailController@mergeEmail');

    //Send an email
    Route::any('sendEmail_offline/{instanceName}/{publication_id}', 'EmailController@sendEmail');
});

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

// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//===== HOME CONTROLLER ======//
// Route::get('/', function () {
//     // if (\Auth::user()->activated == 0) //not yet activated by the admin after registration
//     //     { return view('index-inactive'); }
//     // else{ return view('index'); }

//     return view('index');
// })
// ->name('index');
// //->middleware('auth'); commented to allow non authenticated user to view the index

Route::get('/', 'HomeController@home')->name('index');

Route::get('about', 'HomeController@about')->name('home.about');

Route::get('publish/details/{id}', 'HomeController@viewPublishedDocFullDetails')->name('publish.details.view');
Route::get('publish/list-by-type/{doc_type_id}', 'HomeController@listDocByType')->name('publish.list.bytype');
Route::get('download-file/{id}', 'HomeController@downloadFile')->name('downloadfile');
Route::get('advanced-search/', 'HomeController@advancedSearch')->name('search');
Route::post('advanced-search/', 'HomeController@searching')->name('searching');


//===== /HOME CONTROLLER ======//

Auth::routes(['verify' => true]);

//===== USER REGISTER CONTROLLER ======//
//Route::get('user-register/departments/{officeid}', 'Auth\RegisterController@showDepartmentsByOfficeId')->name('user.register.departments-office.show');
//Route::get('user-register/sections/{deptid}', 'Auth\RegisterController@showSectionsByDepartmentId')->name('user.register.sections-department.show');

//===== USER CONTROLLER ======//
Route::get('user/profile', 'UserController@profileView')->name('user.profile.view'); 
Route::get('user/profile-edit/{id}', 'UserController@profileEdit')->name('user.profile.edit'); 
Route::post('user/profile-update', 'UserController@profileUpdate')->name('user.profile.update'); 
Route::post('user/profile-avatar', 'UserController@avatarUpdate')->name('user.avatar.update');
Route::get('user/change-password/{id}', 'UserController@changePassword')->name('user.change.password'); 
Route::post('user/update-password', 'UserController@updatePassword')->name('user.update.password');

Route::get('user-management/users/reset-password', 'UserController@listUsersForReset')->name('manage.users.list.reset'); //all users
    Route::post('user-management/reset-password/update', 'UserController@resetUserPassword')->name('manage.users.reset.password');
Route::get('user-management/users', 'UserController@users')->name('manage.users'); //all users
    Route::get('user-management/users/valid', 'UserController@validUsers')->name('manage.valid.users');//valid/Registered users
    Route::get('user-management/users/registered', 'UserController@registeredUsers')->name('manage.registered.users');//newly registered users
    Route::get('user-management/users/deactivated', 'UserController@deactivatedUsers')->name('manage.deactivated.users');//deactivated users
    Route::get('user-management/user/details/{id}', 'UserController@detailsUser')->name('manage.user.details');
    Route::post('user-management/user/update', 'UserController@updateUser')->name('manage.user.update');
    Route::post('user-management/user/activate', 'UserController@activateUser')->name('manage.user.activate');

    Route::get('user-management/user/change-location-sameoffice', 'UserController@changeLocationSameOfficeApproval')->name('manage.user.change.location.sameoffice');
    Route::get('user-management/user/change-location-initial', 'UserController@changeLocationInitialApproval')->name('manage.user.change.location.initial');
    Route::get('user-management/user/change-location-final', 'UserController@changeLocationFinalApproval')->name('manage.user.change.location.final');
    Route::get('user-management/user/change-location/approved/{id}', 'UserController@approvedTransfer')->name('manage.user.approved.transfer');
    Route::get('user-management/user/change-location/disapproved/{id}', 'UserController@disapprovedTransfer')->name('manage.user.disapproved.transfer');
    Route::get('user-management/user/disable/{id}', 'UserController@disableUser')->name('manage.user.disable');
    Route::get('user-management/user/enable/{id}', 'UserController@enableUser')->name('manage.user.enable');

Route::get('user-management/roles', 'UserController@roles')->name('manage.roles');
    Route::get('user-management/roles/create', 'UserController@createRole')->name('manage.roles.create');
    Route::post('user-management/roles/save', 'UserController@storeRole')->name('manage.roles.save');
    Route::get('user-management/roles/edit/{id}', 'UserController@editRole')->name('manage.roles.edit');
    Route::post('user-management/roles/update/', 'UserController@updateRole')->name('manage.roles.update');
    Route::get('user-management/roles/disable/{id}', 'UserController@disableRole')->name('manage.roles.disable');
    Route::get('user-management/roles/enable/{id}', 'UserController@enableRole')->name('manage.roles.enable');
Route::get('user-management/permissions', 'UserController@permissions')->name('manage.permissions');
    Route::post('user-management/permissions/save', 'UserController@storePermission')->name('manage.permissions.save');
    Route::get('user-management/permissions/edit/{id}', 'UserController@editPermission')->name('manage.permissions.edit');
    Route::post('user-management/permissions/update/', 'UserController@updatePermission')->name('manage.permissions.update');
    Route::get('user-management/permissions/disable/{id}', 'UserController@disablePermission')->name('manage.permissions.disable');
    Route::get('user-management/permissions/enable/{id}', 'UserController@enablePermission')->name('manage.permissions.enable');
    Route::get('user-management/permissions-role/show/{roleid}', 'UserController@showPermissionsByRoleId')->name('manage.permissions-role.show');

//===== LOCATION MANAGEMENT CONTROLLER ======//

Route::get('location-management/offices', 'LocationController@offices')->name('manage.location.offices');
    Route::get('location-management/offices/create', 'LocationController@createOffice')->name('manage.offices.create');
    Route::post('location-management/offices/save', 'LocationController@storeOffice')->name('manage.offices.save');
    Route::get('location-management/offices/edit/{id}', 'LocationController@editOffice')->name('manage.offices.edit');
    Route::post('location-management/offices/update/', 'LocationController@updateOffice')->name('manage.offices.update');
    Route::get('location-management/offices/disable/{id}', 'LocationController@disableOffice')->name('manage.offices.disable');
    Route::get('location-management/offices/enable/{id}', 'LocationController@enableOffice')->name('manage.offices.enable');
Route::get('location-management/departments', 'LocationController@departments')->name('manage.location.departments');
    Route::get('location-management/departments/create', 'LocationController@createDepartment')->name('manage.departments.create');
    Route::post('location-management/departments/save', 'LocationController@storeDepartment')->name('manage.departments.save');
    Route::get('location-management/departments/edit/{id}', 'LocationController@editDepartment')->name('manage.departments.edit');
    Route::post('location-management/departments/update/', 'LocationController@updateDepartment')->name('manage.departments.update');
    Route::get('location-management/departments/disable/{id}', 'LocationController@disableDepartment')->name('manage.departments.disable');
    Route::get('location-management/departments/enable/{id}', 'LocationController@enableDepartment')->name('manage.departments.enable');
    Route::get('location-management/departments-office/show/{officeid}', 'LocationController@showDepartmentsByOfficeId')->name('manage.departments-office.show');
Route::get('location-management/sections', 'LocationController@sections')->name('manage.location.sections');
    Route::post('location-management/sections/save', 'LocationController@storeSection')->name('manage.sections.save');
    Route::get('location-management/sections/edit/{id}', 'LocationController@editSection')->name('manage.sections.edit');
    Route::post('location-management/sections/update/', 'LocationController@updateSection')->name('manage.sections.update');
    Route::get('location-management/sections/disable/{id}', 'LocationController@disableSection')->name('manage.sections.disable');
    Route::get('location-management/sections/enable/{id}', 'LocationController@enableSection')->name('manage.sections.enable');
    Route::get('location-management/sections-department/show/{deptid}', 'LocationController@showSectionsByDepartmentId')->name('manage.sections-department.show');

//====================================================//

//===== DOCUMENT TYPE MANAGEMENT CONTROLLER ==========//
Route::get('document-type-management/doc-types', 'DocumentTypeController@list')->name('manage.doctypes');
    //Route::get('document-type-management/doc-types/create', 'DocumentTypeController@create')->name('manage.doctypes.create'); it has modal
    Route::post('document-type-management/doc-types/save', 'DocumentTypeController@store')->name('manage.doctypes.save');
    Route::get('document-type-management/doc-types/edit/{id}', 'DocumentTypeController@edit')->name('manage.doctypes.edit');
    Route::post('document-type-management/doc-types/update/', 'DocumentTypeController@update')->name('manage.doctypes.update');
    Route::get('document-type-management/doc-types/disable/{id}', 'DocumentTypeController@disable')->name('manage.doctypes.disable');
    Route::get('document-type-management/doc-types/enable/{id}', 'DocumentTypeController@enable')->name('manage.doctypes.enable');
//====================================================//

//===== DOCUMENT ACTION MANAGEMENT CONTROLLER ==========//
Route::get('document-action-management/doc-actions', 'DocumentActionController@list')->name('manage.docactions');
    Route::post('document-action-management/doc-actions/save', 'DocumentActionController@store')->name('manage.docactions.save');
    Route::get('document-action-management/doc-actions/edit/{id}', 'DocumentActionController@edit')->name('manage.docactions.edit');
    Route::post('document-action-management/doc-actions/update/', 'DocumentActionController@update')->name('manage.docactions.update');
    Route::get('document-action-management/doc-actions/disable/{id}', 'DocumentActionController@disable')->name('manage.docactions.disable');
    Route::get('document-action-management/doc-actions/enable/{id}', 'DocumentActionController@enable')->name('manage.docactions.enable');
//====================================================//

//===== DOCUMENT MANAGEMENT CONTROLLER ==========//
    Route::get('document/new', 'DocumentController@create')->name('document.create'); //generate barcode and create new doc.
    Route::post('document/submit', 'DocumentController@submit')->name('document.submit'); //submit/save to released
    Route::get('document/submitted', 'DocumentController@listSubmitted')->name('document.submitted.list');
    Route::get('document/received', 'DocumentController@listReceived')->name('document.received.list');
    Route::get('document/released', 'DocumentController@listReleased')->name('document.released.list');

    Route::get('document/reply/{docid}/{senderid}', 'DocumentController@reply')->name('document.reply');   
    Route::post('document/replying', 'DocumentController@replying')->name('document.submit.reply');

    Route::get('document/document-details/{docid}', 'DocumentController@detailsByDocIdSenderId')->name('document.fulldetails');

    //Route::post('document/draft/save', 'DocumentController@saveDraft')->name('document.draft.save'); //save as draft
    Route::get('document/draft-list', 'DocumentController@listDraft')->name('document.draft.list'); 
    Route::get('document/draft-edit/{id}', 'DocumentController@editDraft')->name('document.draft.edit'); // select draft item to edit submit
    Route::post('document/draft-update', 'DocumentController@updateDraft')->name('document.draft.update'); // upadate as draft again or use 'document.submit' to submit/save and remove as draft. 
    Route::get('document/draft-delete/{id}', 'DocumentController@deleteDraft')->name('document.draft.delete');  // delete document, recipients, route, etc

    Route::get('document/details/{id}', 'DocumentController@viewFullDocDetails')->name('document.details.view');
    Route::get('document/route-details/{id}', 'DocumentController@viewDocRouteDetails')->name('document.route.details.view'); 
    Route::get('document/user_seen-details/{id}', 'DocumentController@viewSeenLogsDetails')->name('document.seenlogs.view');

    //02-20-2022
    // Route::get('document/details-by-trackingnumber/{id}', 'DocumentController@getFullDocDetailsForRoutingOnlyByBarcode');//->name('document.fulldetails.view');
    // Route::get('document/receiving-document/{id}', 'DocumentController@receivedRoutedDocument');
    // Route::get('document//releasing-document/{id}', 'DocumentController@releasedRoutedDocument');

//===============================================//

//===== ROUTING MANAGEMENT CONTROLLER ==========//
Route::get('route/index', 'RouteController@index')->name('route.index'); 
Route::get('route/draft', 'RouteController@draft')->name('route.draft.list');
Route::get('route/draft-edit/{id}', 'RouteController@editDraft')->name('route.draft.edit'); // select draft item to edit submit
Route::get('route/details/{id}', 'RouteController@viewFullDocDetails');//->name('route.details.view');
Route::get('route/finalized', 'RouteController@finalized')->name('route.finalized.list'); 
Route::get('route/received', 'RouteController@received')->name('route.received.list'); 
Route::get('route/released', 'RouteController@released')->name('route.released.list'); 
Route::get('route/terminal', 'RouteController@terminal')->name('route.terminal.list'); 
Route::get('route/draft-delete/{id}', 'RouteController@deleteDraft')->name('route.draft.delete');
Route::get('route/trail-details/{id}', 'RouteController@routeTrail'); //main index.php, document-route\index.php
Route::get('route/tag-terminal/{id}', 'RouteController@tagAsTerminal')->name('route.terminal.tag');
Route::post('route/tag-terminal-post/', 'RouteController@tagAsTerminalPost')->name('route.terminal.tagpost');
Route::post('route/releasing/', 'RouteController@createReleased')->name('route.create.released'); 
Route::post('route/submit-releasing/', 'RouteController@submitReleasing')->name('route.releasing'); 
Route::post('route/submit-receiving/', 'RouteController@submitReceiving')->name('route.receiving'); 
Route::post('route/routing-document/', 'RouteController@searchByTrackingNo')->name('route.search'); 
Route::get('route/routing-document/{encryptedId}', 'RouteController@searchByEncryptedId');

Route::get('route/create', 'RouteController@create')->name('route.create'); //released, save draft, update draft
Route::post('route/submit-create', 'RouteController@submitCreate')->name('route.submit.create');


//===============================================//

//===== PUBLISH MANAGEMENT CONTROLLER ==========//
Route::get('publish/new', 'PublishController@create')->name('publish.create'); 
Route::post('publish/submit', 'PublishController@submit')->name('publish.submit'); 
Route::get('publish/published-list', 'PublishController@listPublished')->name('publish.list');
Route::get('publish/published-deleted-list', 'PublishController@listTemporaryDeleted')->name('publish.deleted.list');

Route::get('publish/routed-document/{id}', 'PublishController@createPublishedRoutedDoc')->name('publish.routeddoc.create');
Route::post('publish/routed-document/submit', 'PublishController@submitPublishedRoutedDoc')->name('publish.routeddoc.submit');

Route::get('publish/disable/{id}', 'PublishController@temporarilyDeletePublishedDoc')->name('publish.disable');
Route::get('publish/enable/{id}', 'PublishController@rePublishDoc')->name('publish.enable');
Route::get('publish/edit/{id}', 'PublishController@editPublishedDoc')->name('publish.edit');
Route::post('publish/update', 'PublishController@updatePublishedDoc')->name('publish.update'); 

Route::get('publish/announcement', 'PublishController@createAnnouncement')->name('publish.announcement.create'); 
Route::post('publish/announcement-submit', 'PublishController@submitAnnouncement')->name('publish.announcement.submit'); 
//===============================================//

Route::view('barcode', 'barcode'); /////////FOR DELETE and barcode.blade.php

Route::get('pages-login', 'SkoteController@index');
Route::get('pages-login-2', 'SkoteController@index');
Route::get('pages-register', 'SkoteController@index');
Route::get('pages-register-2', 'SkoteController@index');
Route::get('pages-recoverpw', 'SkoteController@index');
Route::get('pages-recoverpw-2', 'SkoteController@index');
Route::get('pages-lock-screen', 'SkoteController@index');
Route::get('pages-lock-screen-2', 'SkoteController@index');
Route::get('pages-404', 'SkoteController@index');
Route::get('pages-500', 'SkoteController@index');
Route::get('pages-maintenance', 'SkoteController@index');
Route::get('pages-comingsoon', 'SkoteController@index');

Route::post('keep-live', 'SkoteController@live');

Route::get('{any}', 'HomeController@index');

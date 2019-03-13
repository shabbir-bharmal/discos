<?php

if (! defined('THEME')) {
    define('THEME', 'disco');
}
    
require_once __DIR__."/../".THEME."/routes.php";

Route::get('ical', 'CalendarController@export');

Route::get('login', array('as'=>'login', 'uses'=>'AuthController@login'));
Route::post('login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');

$core_path = 'Core\Modules'; // core

Route::group(array('prefix' => 'admin', 'before'=>'auth'), function () use ($core_path) {


    $theme_path = ucfirst(THEME).'\Modules'; // site-specific
    
    Route::get('/dashboard', array('before'=>'auth', 'uses'=>'AdminController@dashboard'));
    Route::get('/payment', array('before'=>'auth', 'uses'=>$core_path.'\Booking\Controller\QuoteController@stripepayment'));
   Route::post('/payment', array('as' => 'confirmed', 'before'=>'auth', 'uses'=>$core_path.'\Booking\Controller\QuoteController@update_payment'));
   
    Route::get('calendar-bookings', $theme_path."\Booking\Controller\BookingController@getCalenderBookings");
    Route::get('bookings/edit', function () {
        return Redirect::to('admin/bookings');
    });
    Route::get('emails/edit', function () {
        return Redirect::to('admin/emails');
    });
    Route::get('bookings/edit/{id}', $theme_path.'\Booking\Controller\BookingController@edit');
    Route::get('bookings/delete/{id}', $theme_path.'\Booking\Controller\BookingController@delete');
    Route::get('bookings/send-contract/{id}', $theme_path.'\Booking\Controller\BookingController@sendContract');
    Route::get('bookings/delete-followup/{id}', $theme_path.'\Booking\Controller\BookingController@deleteFollowUp');
    Route::get('quote/get-package/{name}', $core_path.'\Booking\Controller\QuoteController@getPackageDetails');
    
    Route::delete('bookings/multiple', $theme_path.'\Booking\Controller\BookingController@deleteMultiple');
    Route::delete('bookings/pending', $theme_path.'\Booking\Controller\BookingController@deletePending');

    Route::controller('packages/rules', $core_path.'\Booking\Controller\RuleController');
    Route::controller('rules', $core_path.'\Booking\Controller\RuleController');
    Route::delete('clients/multiple', 'ClientController@deleteMultiple');
    Route::delete('clients/pending', 'ClientController@deletePending');
    Route::controller('clients', 'ClientController');
    Route::controller('users', 'UserController');
    Route::controller('settings', 'SettingController');
    Route::controller('export/', 'ExportController');
    Route::get('export/{model}', 'ExportController@getModel');
    Route::post('export/{model}', 'ExportController@postModel');
    Route::controller('bookings', $theme_path.'\Booking\Controller\BookingController');
    Route::controller('extras', $core_path.'\Booking\Controller\ExtraController');
    Route::controller('packages', $core_path.'\Booking\Controller\PackageController');
    Route::controller('invoices', $core_path.'\Booking\Controller\InvoiceController');

    Route::get('booking/delete/pending', $theme_path.'\Booking\Controller\BookingController@deletePending');
    Route::get('client/delete/pending', 'ClientController@deletePending');

    Route::get('invoices/create-contact/{clientId}/{bookingId}', $core_path.'\Booking\Controller\InvoiceController@getCreateContact');
    
    Route::controller('sets', $core_path.'\Booking\Controller\EquipmentController');
    Route::controller('emails', $core_path.'\Email\Controller\EmailController');
    Route::controller('schedules', $core_path.'\Email\Controller\SchedulerController');
    Route::controller('email-offers', $core_path.'\Email\Controller\OfferController');
    Route::controller('quote', $core_path.'\Booking\Controller\QuoteController');
    Route::get('/', $core_path.'\Booking\Controller\QuoteController@getIndex');
});

Route::get('confirm-booking/{id}', ['as' => 'confirm-booking', 'uses' => $core_path.'\Booking\Controller\BookingController@confirm']);

Entrust::routeNeedsPermission('admin/bookings*', 'manage.bookings', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/clients*', 'manage.clients', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/packages*', 'manage.packages', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/rules*', 'manage.packages', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/extras*', 'manage.extras', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/invoices*', 'manage.invoices', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/emails*', 'manage.emails', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/schedules*', 'manage.emails', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/email-offers*', 'manage.emails', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/exports*', 'manage.exports', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/users*', 'manage.users', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/settings*', 'manage.settings', LResponse::make('Unauthorised', 401));
Entrust::routeNeedsPermission('admin/payment*', 'manage.payment', LResponse::make('Unauthorised', 401));

Route::group(array('prefix' => 'ajax', 'before'=>'auth.ajax'), function () {
    Route::resource('bookings', 'Core\Modules\Booking\Controller\BookingAjax');
});


Route::group(array('prefix' => 'api', 'before'=>'auth.api'), function () {
    Route::any('bookings/callback', function () {
        Log::info(print_r(Input::all(), true));
    });
    Route::get('bookings/block-quotes/{date}/{postcode}', 'Core\Modules\Booking\Controller\BookingApi@blockQuotes');
    Route::post('bookings/confirm', 'Core\Modules\Booking\Controller\BookingApi@confirm');
    Route::post('bookings/set-payment', 'Core\Modules\Booking\Controller\BookingApi@setPayment');
    Route::resource('bookings', 'Core\Modules\Booking\Controller\BookingApi');
});

<?php

Route::get('/', 'Disco\Controllers\HomeController@index');
Route::post('/', 'Disco\Controllers\HomeController@getQuotation');

Route::get('booking/{token}', 'Disco\Controllers\HomeController@booking');
Route::post('booking/', 'Disco\Controllers\HomeController@postBooking');


Route::post('validate/{token}', 'Disco\Controllers\HomeController@validate');
Route::get('complete', 'Disco\Controllers\HomeController@complete');
Route::post('auto_quote/', 'Disco\Controllers\HomeController@auto_quote');
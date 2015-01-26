<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['before' => 'sentry.guest'], function()
{
  Route::get('/', 'UserController@showLogin');
  Route::post('login', 'UserController@login');  
});

Route::group(['before' => 'sentry.auth'], function()
{
  Route::get('logout', 'UserController@logout');
  Route::get('dashboard', 'UserController@showDashboard');
  Route::post('sms/purchase', 'SmsController@purchaseSmsCredits');
  Route::post('sms/send', 'SmsController@sendMessage');
});
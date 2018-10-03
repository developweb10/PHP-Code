<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::controllers([
	'account' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController'
]);


Route::get('/', 'WelcomeController@index');
Route::get('/my-meter', 'WelcomeController@myMeter');



Route::post('/home/{method}', function($method){
	return App::call('\App\Http\Controllers\HomeController@' . $method);
});



Route::get('/inspections/{user_id}', 'UtilsController@inspections');
Route::get('/referred/{user_id?}', 'UtilsController@referred');
Route::get('/add-more-time/{meter_id}', 'UtilsController@add_more_time');
Route::get('/notify', 'UtilsController@notifiy' );
Route::match(['get','post'],'/contact-us', 'UtilsController@contact_us' );




/* restricted pages */
Route::controller('admin', 'AdminController', [
	'middleware' => ['auth', 'roles'],
	'roles' => ['Administrator']
]);



Route::get('home', [
	 'middleware' => ['auth', 'roles'],
	 'uses' => 'LandownerController@index',
	 'roles' => ['Landowner']
]);
Route::controller('landowner', 'LandownerController', [
	'middleware' => ['auth', 'roles'],
	'roles' => ['Landowner']
]);
Route::get('/home/{tab}', [
	 'middleware' => ['auth', 'roles'],
	 'uses' => 'LandownerController@index',
	 'roles' => ['Landowner']
]);

Route::get('/affiliate', [
	 'middleware' => ['auth', 'roles'],
	 'uses' => 'SaController@affiliate',
	 'roles' => ['Sale Associate']
]);
Route::get('my-aff-account', [
	'middleware' => ['auth', 'roles'],
	'uses' => 'SaController@my_aff_account',
	'roles' => ['Sale Associate']
]);
Route::controller('sa', 'SaController', [
	'middleware' => ['auth', 'roles'],
	'roles' => ['Sale Associate']
]);













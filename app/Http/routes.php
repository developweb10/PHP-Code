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





if( config("is_mobile") && !config('is_Ipad') ){



	Route::get('/', 'WelcomeController@myMeter');



}else{



	Route::get('/', 'WelcomeController@index');



}


Route::get('/sample-meter','WelcomeController@sampleMeter');

Route::get('/my-meter', 'WelcomeController@myMeter');



Route::get('/rent-out-a-parking-space', 'WelcomeController@index'); //rent_out_parking_space


;


Route::post('/home/{method}', function($method){

	return App::call('\App\Http\Controllers\HomeController@' . $method);

});

Route::get('/home/signage/signageZip',function(){

	return App::call('\App\Http\Controllers\HomeController@signageZip');

});
Route::get('/signage', 'HomeController@getsignage' );

//Route::get('groups/(:any)', array('as' => 'home', 'uses' => 'groups@show'));


Route::get('/meterID/{id}','WelcomeController@myMeter');


Route::get('/inspections/{user_id}', 'UtilsController@inspections');

Route::get('/referred/{user_id?}', 'UtilsController@referred');

Route::get('/at/{meter_id}', 'UtilsController@add_more_time');

Route::get('/notify', 'UtilsController@notifiy' );

Route::match(['get','post'],'/contact-us', 'UtilsController@contact_us' );



Route::get('/faq', 'UtilsController@faq' );

Route::get('/faq_sa', 'UtilsController@faq_sa' );

Route::get('/faq_landowner', 'UtilsController@faq_landowner' );



Route::get('/terms', 'UtilsController@terms' );

Route::get('/map', 'UtilsController@map' );

Route::get('/privacy', 'UtilsController@privacy' );

Route::get('/owner-agreement', 'UtilsController@owner_agreement' );

Route::get('/sa-agreement', 'UtilsController@sa_agreement' );

Route::get('/sm-agreement', 'UtilsController@sm_agreement' );

Route::get('/landing-home', 'UtilsController@landing_home' );





Route::get('/complete-payment', 'UtilsController@completePayment' );
Route::get('/sample-complete-payment', 'UtilsController@sampleCompletePayment' );

Route::get('/complete-newmeter-payment', 'HomeController@completeNewMeterPayment' );

Route::get('/unauth_completeNewMeterPayment', 'WelcomeController@unauth_completeNewMeterPayment' );
Route::get('/home/unauth_completeNewMeterPayment', 'HomeController@unauth_completeNewMeterPayment' );

Route::get('/third-party/facebook', 'SocialController@facebook' );

/* restricted pages */

Route::controller('admin', 'AdminController', [

	'middleware' => ['auth', 'roles'],

	'roles' => ['Administrator']

]);


Route::get('home', [

	 'middleware' => ['auth', 'roles'],

	 'uses' => 'LandownerController@index',

	 'roles' => ['Landowner','Administrator']

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



Route::match(['get','post'],'/sa-home/{tab?}', [

	 'middleware' => ['auth', 'roles'],

	 'uses' => 'SaController@index',

	 'roles' => ['Sales Associate']

]);


Route::controller('sa', 'SaController', [

	'middleware' => ['auth', 'roles'],

	'roles' => ['Sales Associate']

]);


//Route::get('/sm-home', 'SmController@index' );

Route::match(['get','post'],'/sm-home/{tab?}', [

	 'middleware' => ['auth', 'roles'],

	 'uses' => 'SmController@index',

	 'roles' => ['Sales Manager'] 

]);


Route::controller('sm', 'SmController', [

	'middleware' => ['auth', 'roles'],

	'roles' => ['Sales Manager']

]);
//Route::Resource("admin","AdminController");
Route::post('updateStatus','AdminController@updatestatus');
Route::post('payoutHistory','AdminController@payouthistory');

//Route::get('payoutsemt', 'MaatwebsiteDemoController@importExport');
Route::get('payoutsemt/{type}', 'MaatwebsiteDemoController@downloadExcel');

Route::get('/slider', function () {
    return view('slider');
});
Route::get('/redirect', 'SocialiteController@redirect');
Route::get('/callback', 'SocialiteController@callback');

Route::get('/applepay', 'WelcomeController@applepay');
Route::get('/apple_pay_do', 'WelcomeController@apple_pay_do');

//Route::get('/admin/payoutsemt', 'AdminController@payoutsemt');
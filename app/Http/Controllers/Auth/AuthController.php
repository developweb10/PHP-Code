<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Cookie; use URL;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	protected $redirectPath = '/home';
	protected $loginPath = '/account/login';
	


	use AuthenticatesAndRegistersUsers;
	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		//$this->loginPath = URL::previous();
		$this->auth = $auth;
		$this->registrar = $registrar;
		$this->middleware('guest', ['except' => 'getLogout']);
	}
	
	
	public function redirectPath()
	{
		if( \Auth::guest() ){
			return redirect()->intended($this->redirectPath());
		}else{
			if(Cookie::get('referred_by') !== null) Cookie::queue(Cookie::forget('referred_by'));
			$role = $this->auth->user()->role_id;
			
			if( $role == 1 ){
				return "/admin/managecontent"; // /admin/dashboard
			}else if( $role == 2 ){
				return "/sa-home";
			}else if( $role == 3 ){
				return "/home";
			}else if( $role == 5 ){
				return "/sm-home";
			}else{
				return "/my-meter";
			}
		}
	
	}
}

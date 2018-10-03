<?php namespace App\Services;

use App\User; use App\Referral; use Cookie;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		$referred_by = "";
		if( Cookie::get('referred_by') !== null ){
			$promo_code = Cookie::get('referred_by');
			$user = User::where("promo_code",$promo_code)->get()->first(); 
			$referred_by = $user->id;
		}
		$user_created = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'role_id' => isset($data['role_id'])?$data['role_id']:3,
			'referred_by' => (!empty($referred_by))?$referred_by:null,
		]);
		
		if( !empty($referred_by) ){
			//insert referral entry
			Referral::create([
				'user_id' => $user_created->id,
				'referred_by' => $referred_by,
				'commission' => config('commission'),
				'referral_medium'	=> 'URL'
			]);
		}
		
		return $user_created;
	}

}

<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;

class RedirectIfAuthenticated {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->check())
		{
			$role = $this->auth->user()->role_id;

			if( $role == 1 ){
				$ru =  "/admin/dashboard";
			}else if( $role == 2 ){
				$ru =  "/sa-home";
			}else if( $role == 3 ){
				$ru =  "/home";
			}else if( $role == 5 ){
				$ru =  "/sm-home";
			}else{
				$ru =  "/my-meter";
			}
			return new RedirectResponse(url($ru));
		}

		return $next($request);
	}

}

<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password', 'role_id', 'referred_by','last_name','street','is_agreed','country','state','zip','city','towing_company_number','security_answer','towing_company'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token', 'authToken'];

	public function role()
	{
		return $this->hasOne('App\Role', 'id', 'role_id');
	}


	public function hasRole($roles)
	{
		$this->have_role = $this->getUserRole();

		// Check if the user is a root account
		if($this->have_role->name == 'Root') {
			return true;
		}

		if(is_array($roles)){
			foreach($roles as $need_role){
				if($this->checkIfUserHasRole($need_role)) {
					return true;
				}
			}
		} else{
			return $this->checkIfUserHasRole($roles);
		}
		return false;
	}

	private function getUserRole()
	{
		return $this->role()->getResults();
	}
	private function checkIfUserHasRole($need_role)
	{
		return (strtolower($need_role)==strtolower($this->have_role->name)) ? true : false;
	}
	public function sendmail_or_text($param){
		/*Share Live Feed*/
		if(isset($param['email'])){
			
			$data['inspections_url'] = $param["inspections_url"];
			$to = $param['email'];
			
			$message = "
				<p>Inspections URL: ".$data['inspections_url']."</p>
				<br>
				Thanks<br>
				My-meter.com Support Team
			";
			
			$subject =  "My-meter.com Inspections link!";

			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			
			$headers .= "X-Mailer: PHP/" . phpversion();
			$headers .= "Content-Transfer-Encoding: 8bit\r\n";
			
			// More headers
			$headers .= 'From: My-meter.com <info@my-meter.com>' . "\r\n";
			//$headers .= 'Cc: gurpreet.webtek@gmail.com' . "\r\n";
			
			mail($to,$subject,$message,$headers);	
				
			return 1;
		}
	}
}

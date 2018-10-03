<?php namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Redirect;



use Hash; use DB; use Auth; use Input; use DateTime; use View; use URL; use Cookie; use HTML;

use App\Lot; use App\Meter; use App\Payment; use App\PaymentItem; use App\User; use App\Referral; use App\ReferralCommission; use App\Role; use App\Payout; use App\PageContent;

use App\Settings; use App\Country; use App\City;

use App\towing_companies; 

use PDF;

use App\Groups;

//use Excel;
use Maatwebsite\Excel\Facades\Excel;



class AdminController extends Controller {

	

	public function __construct()

	{

		$this->middleware('auth');

		if( Auth::user() && Auth::user()->role_id != 1 ) {

			Redirect::to('/')->send();

			exit;

		}
		

		$this->admin = 1;

		$this->sales_associate = 2;

		$this->landowner = 3;

		$this->sales_manager = 5;

	}

	

	public function index()

	{

		//

	}



	public function getDashboard(){

		$vars = array();

		$vars["statistics"] = DB::select( DB::raw("SELECT * FROM

			(	

				(select count(*) as sm from users where role_id = $this->sales_manager ) as sm,

				(select count(*) as sa from users where role_id = $this->sales_associate ) as sa,

				(select count(*) as lo from users where role_id = $this->landowner  ) as lo,

				(select count(*) as groups from lots) as groups,

				(select count(*) as meters from meters) as meters,

				(select count(*) as trans from payments where payment_type = 'meter_hire') as trans,

				(select sum(amount) as trans_amnt from payments where payment_type = 'meter_hire') as trans_amnt,

				(select count(*) as meter_trans from payments where payment_type = 'meter_buy') as meter_trans,

				(select sum(amount) as meter_trans_amnt from payments where payment_type = 'meter_buy' ||  payment_type = 'sign_buy') as meter_trans_amnt,

				(select count(*) as payouts from payouts) as payouts,

				(select sum(amount) as paid_amnt from payouts ) as paid_amnt

			)") );

		

		$vars["statistics"] = $vars["statistics"][0];
		

		$vars["statistics"]->total_revenue = ( $vars["statistics"]->trans_amnt + $vars["statistics"]->meter_trans_amnt );

		$vars["statistics"]->total_profit = $vars["statistics"]->total_revenue - $vars["statistics"]->paid_amnt;

		

		return view('admin.dashboard',compact('vars'));

	}
	
	public function getInternal(){
		
		//$landowner_users = User::where("role_id",3)->where("created_by_admin",1)->get();
	
		
		$landowner_users = User::leftJoin("cities","users.city","=","cities.id")
							
							->leftJoin("lots","lots.user_id","=","users.id")
							//->leftJoin("meters","meters.lot_id","=","lots.id")
							->leftJoin("payments",function($join)
							 {
							   $join->on("payments.lot_id","=","lots.id")
							   		->on("payments.payment_type","=",DB::raw("'meter_hire'"));
							
							 })
							->where("users.role_id",3)->where("users.created_by_admin",1)
							->select( "users.*","cities.city_name",DB::raw("( select count(meters.meter_id) from meters where user_id = users.id) as meter_count  "), DB::raw("sum(payments.amount) as trans_amnt"))
							->groupBy("users.id")->get();
							//dd($landowner_users);
		return view('admin.internal',compact('landowner_users'));
	}

	public function getUsers(Request $request){

		$input = $request->all();

		$role_id = $request->has("role") ? $input["role"] : "";

		$status = $request->has("status") ? $input["status"] : "";

		//get landowners and sales associates

		$users = User::join("roles","users.role_id","=","roles.id")->whereIn( "users.role_id",[$this->sales_associate,$this->landowner,$this->sales_manager] )->where( function( $obj ) use( $status ){

		} )->select("users.*",DB::raw("roles.name as role_name"));



		if( $role_id > 0 ) $users = $users->where("users.role_id",$role_id);



		if (  !empty($status) and ($status==1 or $status==0) ) $users->where( "users.deleted","=",$status );

		$users = $users->get();



		//get roles

		$roles = Role::whereIn( "id",[$this->sales_associate,$this->landowner,$this->sales_manager] )->get();

		$vars = array( "role_id"=>$role_id, "total_paid"=>array(), "status"=>$status );

		

		return view('admin.users', compact(['users','roles','vars']));

	}

	

	public function getEdituser( Request $request ){



		$input = $request->all();
	

		$oldInputs = Input::old();



		$user_id = ($request->has("id") && $input["id"] > 0) ? $input["id"] : 0;

		if( $user_id == 0 ) return Redirect::to("/admin/users"); 

		

		$user = ( count($oldInputs) ) ? $oldInputs : User::find($user_id);

		$vars['inspectionsURL'] = app('App\Http\Controllers\UtilsController')->inspectionsURL($user_id);

		$vars['user_id'] = $user_id;

		

		$countries = Country::where("status",1)->get();



		$selected_country = ($user['country'] > 0) ? $user['country'] : $countries[0]->id;

		$states = City::where("country_id",$selected_country)->groupBy("state_code")->get();



		$cities = City::where("country_id",$user['country'])->where("state_code",$user['state'])->get();
		
		$roles = Role::whereIn( "id",[$this->sales_associate,$this->landowner,$this->sales_manager] )->get();


		return view('admin.edituser', compact('user','vars','countries','states','cities','roles'));

	}

	

	public function getCreateuser( Request $request ){

		//Input::old();

		$inputs = $request->all();
		
		$user_type = (isset($inputs["admin"])?$inputs["admin"]:0);
		
		$vars['user_type'] = $user_type;
		
		
		
		$user_id = 0;

		$oldInputs = Input::old();

		$user = ( count($oldInputs) ) ? $oldInputs : User::find($user_id);
	
		$vars['inspectionsURL'] = "";

		$vars['user_id'] = $user_id;

		$vars['show_layout'] = Input::has("show_layout")?$inputs["show_layout"]:true;



		$countries = Country::where("status",1)->orderBy('id', 'desc')->get();

		$selected_country = ($user['country'] > 0) ? $user['country'] : $countries[0]->id;

		$states = City::where("country_id",$selected_country)->groupBy("state_code")->get();

		$settings = Settings::orderBy("id","desc")->first();

		$cities = City::where("country_id",$user['country'])->where("state_code",$user['state'])->get();
		
		$roles = Role::whereIn( "id",[$this->sales_associate,$this->landowner,$this->sales_manager] )->get();
	
		return view('admin.edituser', compact('user','vars','countries','states','cities','roles','settings'));

	}



	public function postCreatenewuser( Request $request ){
		
		$input = $request->all();
		
		$created_by_admin = (isset($input["created_by_admin"])?$input["created_by_admin"]:0);
		
		$user_id = 0;

		//,"email_payments"
		

		$user_fields = array("name","email","city","country","state","role_id","password");
		
		if($input['role_id'] != 3)
		array_push($user_fields,"commission");
	
		if($created_by_admin == 1){
			array_push($user_fields,"last_name");
			array_push($user_fields,"business_name");
			array_push($user_fields,"phone");
			//array_push($user_fields,"commission");
			array_push($user_fields,"zip");
			array_push($user_fields,"created_by_admin");
			
		}
		
		//add validation rules

		$rules = array(

			'name' => 'required|max:255',

			'email' => 'required|max:255|email|unique:users',

			'role_id' => 'required|numeric',

			'city' => 'max:255',

			'country' => 'max:255',

        );

		

		//validate input data

		$validator = Validator::make($input, $rules);

		

		//if validation fails

		if($validator->fails()){

			//get error messages

			$messages = $validator->messages()->all();

			//return with error message
			
			if($created_by_admin == 1){
				return Redirect::to('/admin/internal')->withInput($request->except('_token'))->withErrors($messages);
			}else{
				return Redirect::to('/admin/users')->withInput($request->except('_token'))->withErrors($messages);
			}

			exit;

		}

		else{

			$generatePassword = str_random(6);

			$input["password"] = bcrypt($generatePassword);

			

			$user = new User;

			foreach($user_fields as $field){

				$user->{$field}= $input[$field] ;

			};
			
			$user->security_answer = uniqid() ;
			
			//update records

			$user->save();

			/* Don't send email if admin is creating a landowner account to manage it himself*/
			if($created_by_admin == 1){
				$headers = "MIME-Version: 1.0" . "\r\n";
	
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
				$headers .= "X-Mailer: PHP/" . phpversion();
	
				$headers .= "Content-Transfer-Encoding: 8bit\r\n";
	
				// More headers
	
				$headers .= 'From: My-meter.com <info@my-meter.com>' . "\r\n";
				
				mail("gurpreet.webtek@gmail.com","Landowner account details","<p>Email: ".$user->email."</p>
	
					<p>Password: ".$generatePassword."</p>",$headers);	
				return Redirect::to('/admin/internal')->with('success', 'User created.');
			}else{

				$get_content = PageContent::where( "page_name", "automated_emails" )->get();
	
				$get_content = json_decode($get_content[0]->page_content,true);
	
				$to = $user->email;

				$message = "

					<h3>Greetings!</h3>
	
					<p>Your account with my-meter.com has been created. Below are the login credentials:</p>
	
					<p>Email: ".$to."</p>
	
					<p>Password: ".$user->password."</p>
	
					<a href='" .URL::to('/account/login'). "' target='_blank'>Click here</a> to Login.
	
					<br><br><br>
	
					Thanks<br>
	
					My-meter.com Support Team
	
				";			

				//if sales associate, create promocode

				if( $user->role_id == 2 ){

					app('App\Http\Controllers\HomeController')->createPromoCode($user->id);
	
					$subject = isset($get_content['registration']['subject']) ? $get_content['registration_sa']['subject']  : "Your account has been created!";
	
					$body = isset($get_content['registration']['body']) ? $get_content['registration_sa']['body']  : "";
	
				}else if( $user->role_id == 5 ){
	
					app('App\Http\Controllers\HomeController')->createPromoCode($user->id);
	
					$subject = isset($get_content['registration']['subject']) ? $get_content['registration_sm']['subject']  : "Your account has been created!";
	
					$body = isset($get_content['registration']['body']) ? $get_content['registration_sm']['body']  : "";
					
	
				}
				else{
	
					$subject = isset($get_content['registration']['subject']) ? $get_content['registration']['subject']  : "Your account has been created!";
	
					$body = isset($get_content['registration']['body']) ? $get_content['registration']['body']  : "";

				}

				if( !empty($body) ){
	
					$body = str_ireplace("[[user_email]]",$to,$body);
	
					$body = str_ireplace("[[user_password]]",$generatePassword,$body);
	
					$body = str_ireplace("[[click_here_link]]","<a href='" .URL::to('/account/login'). "' target='_blank'>Click here</a>",$body);
	
					$body = str_ireplace("[[user_name]]",$user->name,$body);
	
				}else{
	
					$body = $message;	
	
				}

				// Always set content-type when sending HTML email
	
				$headers = "MIME-Version: 1.0" . "\r\n";
	
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
				$headers .= "X-Mailer: PHP/" . phpversion();
	
				$headers .= "Content-Transfer-Encoding: 8bit\r\n";
	
				// More headers
	
				$headers .= 'From: My-meter.com <info@my-meter.com>' . "\r\n";
	
				//	$headers .= 'Cc: gurpreet.webtek@gmail.com' . "\r\n";
			
				mail($to,$subject,$body,$headers);	
		

				//redirect with success message
			
				return Redirect::to('/admin/users')->with('success', 'User created. An email with credentials has been sent.');
			}

		}

	}

	

	public function postCreateuser( Request $request ){

		$input = $request->all();

		$user_id = 0;

		//,"email_payments"

		$user_fields = array("name","email","street","city","country","role_id");

		//add validation rules

		$rules = [

			'name' => 'required|max:255',

			'email' => 'required|max:255|email|unique:users',

			'role_id' => 'required|numeric',

			'street' => 'max:255',

			'city' => 'max:255',

			'country' => 'max:255',

			/*'email_payments' => 'max:255|email',*/

			'password' => 'required|min:3|confirmed',

			'password_confirmation' => 'required|min:3'

        ];

		

		//validate input data

		$validator = Validator::make($input, $rules);

		

		//if validation fails

		if($validator->fails()){

			//get error messages

			$messages = $validator->messages()->all();

			//return with error message

			return Redirect::to('/admin/createuser')->withInput($request->except('password','password_confirmation','_token'))->withErrors($messages);

			exit;

		}

		else{

			$user = new User;

			foreach($user_fields as $field){

				$user->{$field}= $input[$field] ;

			};

			//encrypt password

			$user->password = Hash::make($request->password);
			$user->security_answer = uniqid() ;
			//update records

			$user->save();

			

			//if sales associate, create promocode

			if( $user->role_id == 2 ){

				app('App\Http\Controllers\HomeController')->createPromoCode($user->id);

			}

			

			//redirect with success message

			return Redirect::to('/admin/users')->with('success', 'User created.');

		}

	}

	

	

	public function postEdituser( Request $request ){

		$input = $request->all();

		$user_id = ($request->has("id") && $input["id"] > 0) ? $input["id"] : 0;

		if( $user_id == 0 ) return Redirect::to("/admin/users"); 

		//,"email_payments"

		$user_fields = array("name","last_name","street","city","country","commission");

		//add validation rules

		$rules = [

			'name' => 'required|max:255',
			
			'last_name' => 'required|max:255',

			'street' => 'max:255',

			'city' => 'max:255',

			'country' => 'max:255',

			/*'email_payments' => 'max:255|email',*/

        ];

		

		//add passwords to validation rule if submitted

		if($request->has('password')){

			$rules2 = ['password' => 'required|min:3|confirmed',

				'password_confirmation' => 'required|min:3'];

			$rules = array_merge($rules, $rules2);

		}

		//validate input data

		$validator = Validator::make($input, $rules);

		

		//if validation fails

		if($validator->fails()){

			//get error messages

			$messages = $validator->messages()->all();

			//return with error message

			return Redirect::to('/admin/edituser?id='.$user_id)->withInput($request->except('password','password_confirmation','_token'))->withErrors($messages);

			exit;

		}

		else{

			$user = User::find($user_id);

			

			foreach($user_fields as $field){

				$user->{$field}= $input[$field] ;

			};

			//update password if submitted

			if($request->has('password')){

				//encrypt password

				$password = Hash::make($request->password);

				$user->password = $password;

			}

			//update records

			$user->update();

			//redirect with success message

			return Redirect::to('/admin/edituser?id='.$user_id)->with('success', 'Data updated.');

		}

	}

	

	public function getUserstatus(Request $request){

		$input = $request->all();

		$user_id = ($request->has("id") && $input["id"] > 0) ? $input["id"] : 0;

		$deleted = ($input["status"] == 1 || $input["status"] == 0)?$input["status"]:0;

		$user = User::find($user_id);

		$user->deleted = $deleted;

		$user->update();

		return Redirect::to('admin/users')->with('success','User Status updated successfully.');

	}

	

	public function getUserdelete(Request $request){

		$input = $request->all();

		$user_id = ($request->has("id") && $input["id"] > 0) ? $input["id"] : 0;



		$user = User::find($user_id);

		$user->delete();

		//delete Lots
		Lot::where("user_id",$user_id)->delete();

		//delete meters
		Meter::where("user_id",$user_id)->delete();

		//$deleted = DB::raw('delete from payouts_EMT where user_id = $user_id');
		//$deleted = DB::delete('delete from payouts_EMT')->where("user_id","=",$user_id);
		
		//DB::table('payouts_EMT')->where('user_id', '=', $user_id)->delete();
		
		return Redirect::to('admin/users')->with('success','User deleted successfully.');

	}



	public function getPages(Request $request){

		return view('admin.pages',compact('vars'));

	}



	public function getReports( Request $request ){



		$input = $request->all();



		$vars = array( "role_id"=>"", "total_paid"=>array(), "total_received"=>array(), "total_spent"=>array(), "referred"=>array() );

		$vars['report_type'] = $request->has("report_type") ? $input["report_type"] : "report_by_lo";

		$vars['start_date'] = $request->has("start_date") ? $input["start_date"] : "";

		$vars['end_date'] = $request->has("end_date") ? $input["end_date"] : "";

		$vars['referred_by'] = $request->has("referred_by") ? $input["referred_by"] : "";

		$vars['lo'] = $request->has("lo") ? $input["lo"] : "";

		$vars['group_id'] = $request->has("group_id") ? $input["group_id"] : "";

		

		$vars['export']	=	$request->has("export") ? $input["export"] : "";



		$vars['country'] = $request->has("country") ? $input['country'] : 1;

		$vars['state'] = $request->has("state") ? $input['state'] : "";

		$vars['city'] = $request->has("city") ? $input['city'] : "";

		

		///if report by user

		if( $vars['report_type'] == "report_by_lo" ){

		

		

		

		

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			//get users

			$vars['users'] = User::where( "users.role_id", $this->landowner )->where( "users.deleted","!=","1" )->select("users.*",DB::raw("( select count(id) from lots where user_id = users.id) as lots, ( select count(id) from meters where user_id = users.id) as meters"));

			

			if($vars['referred_by'] > 0) $vars['users'] = $vars['users']->where("referred_by",$vars['referred_by']);

			$vars['users'] = $vars['users']->get();

			

			foreach( $vars['users'] as $user ){

				

				$vars["total_paid"][$user->id] = Payment::join("lots","payments.lot_id","=","lots.id")->where( function( $obj ) use( $vars, $user ) {

					//where conditions start

					$obj->where( "lots.user_id", "=", $user->id );

					$obj->where( "payments.payment_type", "=", "meter_hire" );

					if( $vars['start_date'] != '' )		$obj->where( "payments.created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );

					if( $vars['end_date'] != '' )		$obj->where( "payments.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

					//where conditions end

				} )->select(DB::raw("sum(payments.amount) as total_amount"))->pluck("total_amount");

				

				$vars["total_spent"][$user->id] = Payment::join("lots","payments.lot_id","=","lots.id")->where( function($obj) use( $vars, $user ){

					//where conditions start

					$obj->where( "lots.user_id", "=", $user->id );

					$obj->where( "payments.payment_type", "=", "meter_buy" );

					if( $vars['start_date'] != '' )		$obj->where( "payments.created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );

					if( $vars['end_date'] != '' )		$obj->where( "payments.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

					//where conditions end

				} )->select(DB::raw("sum(payments.amount) as total_spent"))->pluck("total_spent");

				

				$vars["total_received"][$user->id] = Payout::where( function( $obj ) use( $vars, $user ) {

					//where conditions start

					$obj->where( "user_id", "=", $user->id );

					if( $vars['start_date'] != '' )		$obj->where( "created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );

					if( $vars['end_date'] != '' )		$obj->where( "created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

					//where conditions end

				} )->select( DB::raw("sum(amount) as total_received") )->pluck("total_received");

			}

			

			$vars["sales_assoicates"] = User::where( "users.role_id", $this->sales_associate )->where( "users.deleted","!=","1" )->get();

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





		///if report by Sales Managers

		}elseif( $vars['report_type'] == "report_by_sm" ){







			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			//get users

			$vars['users'] = User::where( "users.role_id", $this->sales_manager )->where( "users.deleted","!=","1" )->get();



			foreach( $vars['users'] as $user ){



				$vars["referred"][$user->id] = Referral::where( function( $obj ) use ( $user,$vars ) {

					$obj->where( "referred_by", "=", $user->id );

					if( $vars['start_date'] != '' )		$obj->where( "created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );

					if( $vars['end_date'] != '' )		$obj->where( "created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

				} )->count();



				$vars["total_paid"][$user->id] = ReferralCommission::join("referrals","referrals.id","=","referral_commissons.referral_id")->where( function( $obj ) use ( $user,$vars ) {

					$obj->where( "referrals.referred_by", "=", $user->id );

					if( $vars['start_date'] != '' )		$obj->where( "referral_commissons.created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );

					if( $vars['end_date'] != '' )		$obj->where( "referral_commissons.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

				} )->select(DB::raw("sum(referral_commissons.manager_commission_amount) as total_commission"))->pluck("total_commission");



				$vars["total_received"][$user->id] = Payout::where( function( $obj ) use ( $user,$vars ) {

					$obj->where( "user_id", "=", $user->id );

					if( $vars['start_date'] != '' )		$obj->where( "created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );

					if( $vars['end_date'] != '' )		$obj->where( "created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

				} )->select( DB::raw("sum(amount) as total_received") )->pluck("total_received");

			}

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////













		

		///if report by Sales Associates

		}elseif( $vars['report_type'] == "report_by_sa" ){













			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			//get users

			$vars['users'] = User::where( "users.role_id", $this->sales_associate )->where( "users.deleted","!=","1" )->get();



			foreach( $vars['users'] as $user ){



				$vars["referred"][$user->id] = Referral::where( function( $obj ) use ( $user,$vars ) {

					$obj->where( "referred_by", "=", $user->id );

					if( $vars['start_date'] != '' )		$obj->where( "created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );

					if( $vars['end_date'] != '' )		$obj->where( "created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

				} )->count();



				$vars["total_paid"][$user->id] = ReferralCommission::join("referrals","referrals.id","=","referral_commissons.referral_id")->where( function( $obj ) use ( $user,$vars ) {

					$obj->where( "referrals.referred_by", "=", $user->id );

					if( $vars['start_date'] != '' )		$obj->where( "referral_commissons.created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );

					if( $vars['end_date'] != '' )		$obj->where( "referral_commissons.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

				} )->select(DB::raw("sum(referral_commissons.commission_amount) as total_commission"))->pluck("total_commission");



				$vars["total_received"][$user->id] = Payout::where( function( $obj ) use ( $user,$vars ) {

					$obj->where( "user_id", "=", $user->id );

					if( $vars['start_date'] != '' )		$obj->where( "created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );

					if( $vars['end_date'] != '' )		$obj->where( "created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

				} )->select( DB::raw("sum(amount) as total_received") )->pluck("total_received");

			}

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////











		///if report by lots

		}elseif( $vars['report_type'] == "report_by_lots" ){







			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			$payments_where = "";

			if( $vars['start_date'] != '' ) 		$payments_where .= " AND created_at >= '". $vars['start_date'] ."' ";

			if( $vars['end_date'] != '' ) 			$payments_where .= " AND created_at <= '". $vars['end_date'] ."' ";



			$vars['lot_details'] = Lot::join("meters","meters.lot_id","=","lots.id")->join("users","users.id","=","meters.user_id")->where( function( $obj ) use( $vars ){



				if( $vars['end_date'] != '' )		$obj->where( "lots.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

				if( $vars['lo'] > 0 )				$obj->where( "lots.user_id", "=", $vars['lo'] );



			} )->select("lots.id","lots.lot_name","lots.price","users.name",DB::raw("count(meters.id) as meter_count,( select count(id) from payments where lot_id = lots.id AND payment_type='meter_hire' ". $payments_where ." ) as transactions,( select sum(amount) from payments where lot_id = lots.id AND payment_type='meter_hire' ". $payments_where ." ) as trans_amount"))->groupBy("lots.id")->get();



			$vars["landowners"] = User::where( "users.role_id", $this->landowner )->where( "users.deleted","!=","1" )->get();

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





		

		///if report by meters

		}elseif( $vars['report_type'] == "report_by_meters" ){







			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			$payments_where = "";

			if( $vars['start_date'] != '' ) 		$payments_where .= " AND payment_items.created_at >= '". $vars['start_date'] ."' ";

			if( $vars['end_date'] != '' ) 			$payments_where .= " AND payment_items.created_at <= '". $vars['end_date'] ."' ";

			

			

			$vars['meter_details'] = Meter::join("lots","meters.lot_id","=","lots.id")->join("users","users.id","=","meters.user_id")->where( function( $obj ) use( $vars ){



				if( $vars['end_date'] != '' )		$obj->where( "meters.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

				if( $vars['lo'] > 0 )				$obj->where( "meters.user_id", "=", $vars['lo'] );

				if( $vars['group_id'] != "" )		$obj->where( "meters.lot_id", "=", $vars['group_id'] );

			

			} )->select("meters.meter_id","lots.lot_name","users.name",DB::raw("

				(select count(payment_items .id) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id ".$payments_where." ) as transactions,

				(select sum(payment_items .total_price) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id  ".$payments_where." ) as trans_amount, 

				(select sum(payment_items.hours) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id  ".$payments_where." ) as total_hours"))->get();

			

			$vars["landowners"] = User::where( "users.role_id", $this->landowner )->where( "users.deleted","!=","1" )->get();

			

			if( $vars['lo'] > 0 )

				$vars["groups"] = Lot::where( "user_id", $vars['lo'] )->get();

			else

				$vars["groups"] = Lot::all();

			

			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





	

		}elseif( $vars['report_type'] == "report_by_city" ){



			$vars['countries'] = Country::where("status",1)->get();

			$vars['country'] = ($vars['country'] > 0) ? $vars['country'] : $vars['countries'][0]->id;

			$vars['states'] = City::where("country_id",$vars['country'])->groupBy("state_code")->get();

			$vars['cities'] = City::where("country_id",$vars['country'])->where("state_code",$vars['state'])->get();



			$vars['city_list'] = array();



			$cities = User::join("cities","cities.city_id","=","users.city")->where(function($obj) use($vars){

				$obj->where("role_id",">",1);

				$obj->where("country","=",$vars['country']);

				if( !empty($vars['state']) ){

					$obj->where("state","=",$vars['state']);

				}

				if( !empty($vars['city']) ){

					$obj->where("city","=",$vars['city']);

				}

			})->groupBy("city")->select("users.city","cities.city_name")->get();



			foreach( $cities as $city ){

				$this_city = array();

				$this_city["city"] = empty($city->city_name)?'NULL':$city->city_name;



				//landowners

				$this_city["lo"] = User::where("city",$city->city)->where("role_id",3)->count();



				//associates

				$this_city["sa"] = User::where("city",$city->city)->where("role_id",2)->count();



				//active meters

				$this_city["active_meters"] = Meter::leftJoin("users","users.id","=","meters.user_id")->where("city",$city->city)->where("meters.updated_at", ">=", DB::raw("CURDATE() - INTERVAL 30 DAY"))->where("meters.updated_at", "<=", DB::raw("CURDATE()"))->where("meters.hour_price",">",0)->count();



				//revenue

				$this_city["revenue"] = Payment::leftJoin("lots","lots.id","=","payments.lot_id")->join("users","users.id","=","lots.user_id")->where("users.city",$city->city)->sum("payments.amount");





				//payouts

				$this_city["payouts"] = Payout::join("users","users.id","=","payouts.user_id")->where("users.city",$city->city)->sum("payouts.amount");



				$this_city["profit"] = $this_city["revenue"] - $this_city["payouts"];

				

				$vars['city_list'][$this_city["city"]] = $this_city;

			

			}

			

		} elseif( $vars['report_type'] == "report_by_country" ){



			$vars['country_list'] = array();

			$countries = Country::where("status",1)->groupBy("name")->select("name","id")->get();



			foreach( $countries as $country ){

				$this_country = array();

				$this_country["country"] = empty($country->name)?'NULL':$country->name;



				//landowners

				$this_country["lo"] = User::where("country",$country->id)->where("role_id",3)->count();



				//associates

				$this_country["sa"] = User::where("country",$country->id)->where("role_id",2)->count();



				//active meters

				$this_country["active_meters"] = Meter::leftJoin("users","users.id","=","meters.user_id")->where("users.country",$country->id)->where("meters.updated_at", ">=", DB::raw("CURDATE() - INTERVAL 30 DAY"))->where("meters.updated_at", "<=", DB::raw("CURDATE()"))->where("meters.hour_price",">",0)->count();



				//revenue

				$this_country["revenue"] = Payment::leftJoin("lots","lots.id","=","payments.lot_id")->join("users","users.id","=","lots.user_id")->where("users.country",$country->id)->sum("payments.amount");





				//payouts

				$this_country["payouts"] = Payout::join("users","users.id","=","payouts.user_id")->where("users.country",$country->id)->sum("payouts.amount");



				$this_country["profit"] = $this_country["revenue"] - $this_country["payouts"];

				

				$vars['country_list'][$this_country["country"]] = $this_country;

			}



			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



		

		

		//report by mass payments

		}elseif( $vars['report_type'] == "report_by_mass_payments" ){







			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





		

		}

		

		if( $vars['report_type'] == "report_by_lots" )

			$report_view = "admin.report_by_lots";

		elseif( $vars['report_type'] == "report_by_meters" )

			$report_view = "admin.report_by_meters";

		elseif( $vars['report_type'] == "report_by_sm" )

			$report_view = "admin.report_by_sa";

		elseif( $vars['report_type'] == "report_by_sa" )

			$report_view = "admin.report_by_sa";

		elseif( $vars['report_type'] == "report_by_city" )

			$report_view = "admin.report_by_city";

		elseif( $vars['report_type'] == "report_by_country" )

			$report_view = "admin.report_by_country";

		else

			$report_view = "admin.report_by_lo";

				

		if( $vars['export'] == "Excel" ){

			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");

			header("Content-Disposition: attachment; filename=".($vars['report_type']!=''?$vars['report_type']:'report').".xls");

			header("Expires: 0");

			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

			header("Cache-Control: private",false);

			

			$data = View::make($report_view)->with('vars',$vars);

			echo $data;

			exit;

		}if( $vars['export'] == "PDF" ){

			$this_report = $vars['report_type'];

			$vars = array('vars'=>$vars);

			

			$pdf = PDF::loadView($report_view, $vars);

			//return $pdf->stream();

			return $pdf->download(($this_report!=''?$this_report:'report').'.pdf');

		}

		

		

		

		return view('admin.reports',compact('vars'));

	}



	public function getPayments(Request $request){

		$input = $request->all();

		$vars = array();

		$vars["pay"] = ($request->has("pay") && ( $input["pay"] == "lo" || $input["pay"] == "sa" || $input["pay"] == "sm" || $input["pay"] == "exported" ) ) ? $input["pay"] : "";

		$vars["confirm"] = ( isset($input["confirm"])?$input["confirm"]:'' );

		$vars['export']	=	$request->has("export") ? $input["export"] : "";

		

		$vars["payout_details"] = array( );

		$filter = "";

		if( $vars["pay"] == "lo" )

			$vars["role_id"] = $this->landowner;

		elseif( $vars["pay"] == "sa" )

			$vars["role_id"] = $this->sales_associate;

		elseif( $vars["pay"] == "sm" )

			$vars["role_id"] = $this->sales_manager;
			
		elseif( $vars["pay"] == "exported" )

			$filter = "exported";

		else

			$vars["role_id"] = 0;

		

		if($filter == ''){

			$get_users = User::where(function ($obj) use ($input,$vars) {
	
				if( $vars["role_id"] > 0 ) $obj->where("role_id", '=', $vars["role_id"]);
	
				$obj->where("balance", '>', 50);
	
			})->select("id","role_id","name","email","email_payments","balance")->get()->toArray();

			//select()->pluck("total_commission");
			$sum_earned  = 0;
			$sum_paid    = 0;
			$sum_payable = 0;
		
			foreach( $get_users as $this_user ){

			//if sales associate

			if( $this_user['role_id'] == $this->sales_associate ){ 

				$this_user["total_paid"] = ReferralCommission::join("referrals","referrals.id","=","referral_commissons.referral_id")->where("referrals.referred_by",$this_user['id'])->select(DB::raw("sum(referral_commissons.commission_amount) as total_commission"))->pluck("total_commission");

			}

			elseif( $this_user['role_id'] == $this->sales_manager ){ 

				$this_user["total_paid"] = ReferralCommission::join("referrals","referrals.id","=","referral_commissons.referral_id")->join("users","users.id","=","referrals.referred_by")->where("users.created_by",$this_user['id'])->select(DB::raw("sum(referral_commissons.manager_commission_amount) as total_commission"))->pluck("total_commission");

			}

			else{

				$this_user["total_paid"] = Payment::join("lots","payments.lot_id","=","lots.id")->where( "lots.user_id",$this_user['id'] )->where( "payments.payment_type", "meter_hire" )->select(DB::raw("sum(payments.amount) as total_amount"))->pluck("total_amount");

			}

			$this_user["total_received"] = Payout::where("user_id",$this_user['id'])->select( DB::raw("sum(amount) as total_received") )->pluck("total_received");

			$sum_earned = $sum_earned+$this_user["total_paid"];
			
			$sum_paid 	= $sum_paid+$this_user["total_received"];
			
			$sum_payable = $sum_payable+$this_user['balance'];

			array_push($vars["payout_details"], $this_user);
			$vars["sum"] = array();
			array_push($vars["sum"],$sum_earned,$sum_paid,$sum_payable);

		}


			$export_view = "admin.payments-table";

			if( $vars['export'] == "Excel" ){

			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");

			header("Content-Disposition: attachment; filename=payables.xls");

			header("Expires: 0");

			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

			header("Cache-Control: private",false);

			

			$data = View::make($export_view)->with('vars',$vars);

			echo $data;

			exit;

		}if( $vars['export'] == "PDF" ){

			$vars = array('vars'=>$vars);

			

			$pdf = PDF::loadView($export_view, $vars);

			//return $pdf->stream();

			return $pdf->download('payables.pdf');

		}

			return view('admin.payments', compact('vars'));
		}else{
			
			/********************* Exported Payables **********************/
			$payouts_history = DB::table('payouts_EMT')->get();
			$payouts = array();
			$i = 0;
			foreach($payouts_history as $payout_history){
				$payouts[$i]["id"] = $payout_history->id;
				$payouts[$i]["balance"] = $payout_history->amount;
				$payouts[$i]["email"] = $payout_history->payee_email;
				$user_id = $payout_history->user_id;
				$security_answer = DB::table('users')->where('id',$user_id)->select('security_answer')->get();
				$payouts[$i]["security_answer"] = $security_answer[0]->security_answer;
				$payouts[$i]["name"] = ( isset($payout_history->PayeeName)?$payout_history->PayeeName:''  );
				$i++;
			}
			$payouts["exported"] = 1;
			if(isset($vars["confirm"]) && $vars["confirm"] == 1 ) {
				$payouts["confirm"] = 1;
			}
			//$payouts = DB::table('payouts_EMT')->select('balance','name', 'email')->get();
			 
			//echo "<pre>"; print_r($payouts); echo "</pre>"; exit();
			return view('admin.payments', compact('payouts',$payouts) );
		}

	}

	

	public function getPayouts( Request $request ){

		$input = $request->all();

		$vars = array();

		$vars["pay"] = ($request->has("pay") && ( $input["pay"] == "lo" || $input["pay"] == "sa") ) ? $input["pay"] : "";

		if( $vars["pay"] == "lo" )

			$vars["role_id"] = $this->landowner;

		elseif( $vars["pay"] == "sa" )

			$vars["role_id"] = $this->sales_associate;

		else

			$vars["role_id"] = 0;



		$payment_data = array();

		

		$users = User::where(function ($obj) use ($input,$vars) {

				if( $vars["role_id"] > 0 ) $obj->where("role_id", '=', $vars["role_id"]);

				$obj->where("balance", '>', 0);

			})->select("id","role_id","name","email","email_payments","balance")->get()->toArray();

			

		

		

		foreach( $users as $user ){

			$payment_data[$user["id"]]["email_payments"] = $user["email_payments"];

			$payment_data[$user["id"]]["balance"] = $user["balance"];

		}



		$response = $this->processMassPayments($payment_data);



		if( isset($response["ACK"]) && $response["ACK"] == "Success" ){

			$trans_id = "";

			$trans_status = $response["ACK"];

			$trans_response = serialize($response);

			

			foreach( $payment_data as $id=>$arr ){

				$payout = new Payout;

				$payout->user_id = $id;

				$payout->amount = $arr["balance"];

				$payout->payment_email = $arr["email_payments"];

				$payout->trans_id = $trans_id;

				$payout->trans_status = $trans_status;

				$payout->trans_response = $trans_response;

				$payout->save();

				

				$this_user = User::find($id);

				$this_user->balance = ($this_user->balance - $arr["balance"]);

				$this_user->last_paid = date('Y-m-d H:i:s');

				$this_user->update();

			}

			

			return Redirect::to("admin/payments")->with('success','All payouts done successfully.');

			

		}else{

			$messages[] = isset($response["L_LONGMESSAGE0"])?$response["L_LONGMESSAGE0"]:"There experienced some problem, please try again.";

			return Redirect::to("admin/payments")->withErrors($messages);

		}

	

	}

	

	private function processMassPayments($payment_data){



		$sandbox = TRUE;

 

		$api_version = '2.3';

		$api_endpoint = $sandbox ? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp';

		$api_username = $sandbox ? 'gurpreet.webtech2_api1.gmail.com' : 'LIVE_USERNAME_GOES_HERE';

		$api_password = $sandbox ? 'WD7V9DCYAMSGUR4N' : 'LIVE_PASSWORD_GOES_HERE';

		$api_signature = $sandbox ? 'AFcWxV21C7fd0v3bYYYRCpSSRl31Ast.zf1hxkpCJDTCAw6o3fXf3UeP' : 'LIVE_SIGNATURE_GOES_HERE';

		

		$users_arr = array();

		$count = 0;

		foreach( $payment_data as $id=>$arr ){

			$users_arr["L_EMAIL".$count] = $arr["email_payments"];

			$users_arr["L_AMT".$count] = $arr["balance"];

			$count++;

		}

			

		$request_params = array

			(

			'METHOD' => 'MassPay', 

			'USER' => $api_username, 

			'PWD' => $api_password, 

			'SIGNATURE' => $api_signature, 

			'VERSION' => $api_version, 

			'RECEIVERTYPE' => 'EmailAddress',

			'CURRENCYCODE' => 'USD', 

			);

		

		$request_params = array_merge($request_params, $users_arr);

		

		// Loop through $request_params array to generate the NVP string.

		$nvp_string = '';

		foreach($request_params as $var=>$val)

		{

			$nvp_string .= (($nvp_string == '')?'?':'&').$var.'='.urlencode($val);    

		}



		$url = $api_endpoint . $nvp_string;



		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url); 

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$result = curl_exec($ch); 



		return app('App\Http\Controllers\HomeController')->parseNVP($result);

		

	}

	

	

	public function getManagecontent( $tab='' )

	{

	

		$oldInputs = Input::old();

		

		$vars = array('tab'=>$tab);

		$fields = array();

		$pages = array("home","terms","owner_agreement","sa_agreement","sm_agreement","privacy","faq","footer","testimonials","faq_landowner","faq_sa","sa_dashboard","sa_marketing","social_sharing","owner_content","automated_emails"); //,"companies"

		

		$fields["home"] = array("section0","section1","section2","section3","section4","section5","section6");

		$fields["terms"] = array("page_title","page_content");

		$fields["owner_agreement"] = array("page_title","page_content");

		$fields["sa_agreement"] = array("page_title","page_content");

		$fields["sm_agreement"] = array("page_title","page_content");

		$fields["privacy"] = array("page_title","page_content");

		$fields["faq"] = array("page_title","page_content","questions","answers");

		$fields["faq_landowner"] = array("page_title","page_content","questions","answers");

		$fields["faq_sa"] = array("page_title","page_content","questions","answers");

		$fields["footer"] = array("footer_text","meta_keywords","meta_description","meta_author");

		$fields["testimonials"] = array("testimonials");

		$fields["sa_dashboard"] = array("dashboard_text","dashboard_image");

		$fields["sa_marketing"] = array("marketing_text","brochure_image","business_card_image");

		$fields["social_sharing"] = array("title","description","image","image_linkedIn");

		$fields["owner_content"] = array("signage_content","signage_image");

		$fields["automated_emails"] = array("registration","registration_sa","registration_sm","registration_order","hire_meter");



		

		foreach( $pages as $page ){

			$thisPage	=	PageContent::where( "page_name", $page )->get();
		
			if( count($thisPage) ){

				$page_content = json_decode($thisPage[0]->page_content,true);

				$vars[$page] = $page_content;

			}

			else{

				$vars[$page] = array();

				foreach( $fields[$page] as $fld ){

					$vars[$page][$fld] = "";

				}

			}

		}

		

		if( count($oldInputs) ){

			$pg = $oldInputs["form_type"];

			foreach( $fields[$pg] as $fld ){

				$vars[$pg][$fld] = isset($oldInputs[$fld])?$oldInputs[$fld]:"";

			}

		}

		/* towing companies */
		$states = City::where("country_id",2)->groupBy("state_code")->get();
		
		$companies = towing_companies::where(["country_id"=>2])->select("id","city_name","company","contact_no","state_code")->get();
		$vars["companies"] = $companies;
		$vars["states"] = $states;


		return view('admin.pages',compact('vars'));

	

	}



	public function postManagecontent( Request $request )

	{
		

		$inputs = $request->all();

		
		
		$dest_path = public_path() . "/images/custom/";

		$extentions = array("png","jpg","jpeg","gif");

		$img_path = app_path();

		

		$page_name = $inputs["form_type"];

		

		$page_array = array();

		$fields = array();

		$valid = 0;



		if( $page_name == "terms" ){

			$fields = array("page_title","page_content");

			$valid = 1;

		}elseif( $page_name == "owner_agreement" ){

			$fields = array("page_title","page_content");

			$valid = 1;

		}elseif( $page_name == "sa_agreement" ){

			$fields = array("page_title","page_content");

			$valid = 1;

		}elseif( $page_name == "sm_agreement" ){

			$fields = array("page_title","page_content");

			$valid = 1;

		}elseif( $page_name == "privacy" ){

			$fields = array("page_title","page_content");

			$valid = 1;

		}elseif( $page_name == "faq" || $page_name == "faq_landowner" || $page_name == "faq_sa" ){

			$fields = array("page_title","page_content","questions","answers");

			$valid = 1;

		}elseif( $page_name == "footer" ){

			$fields = array("footer_text","meta_keywords","meta_description","meta_author");

			$valid = 1;

		}elseif( $page_name == "home" ){

			$fields = array("section0","section1","section2","section3","section4","section5","transition","section6");
			//
			$image_fields = array("section2_image"=>"section2","section3_image"=>"section3","section5_image"=>"section5","section6_image"=>"section6");
			//"section0_image"=>"section0",
			$valid = 1;

			//upload images
			//count($inputs["section0_image"]); // Number of images uploaded
			//echo "<pre>";
			//print_r($inputs["section0"]);
			//echo "SECTIOn 0 <br>";
			//print_r($inputs["section0_image"]);
		
			$files = Input::file('section0_image');
			//echo "<pre>";
			//dd($files);
			
			$uploadcount = 0;
			
			/*------------------------------------- For Single Image --------------------------------------------------------*/
			
			if(isset($files)){
				if( !empty($files) && !is_array($files) )
					{
						$filename = $files->getClientOriginalName();
						if($filename != "")
						{
							$img_ext = $files->getClientOriginalExtension();
							if( in_array( $img_ext, $extentions ) ){
								$filename = date("YmdHis").$filename;
								$upload_success = $files->move($dest_path, $filename);
								
								$inputs["section0"] = asset("images/custom/".$filename);
								$uploadcount ++;
							}
						}
					}
			}
			
			/*-------------------------------------For Slider--------------------------------------------------------*/
			
			/*if(isset($files) && count($files)>=1)
			{
				//$inputs["section0"]='';
				foreach($files as $file) {
					if(!empty($file))
					{
						$filename = $file->getClientOriginalName();
						if($filename != "")
						{
							$img_ext = $file->getClientOriginalExtension();
							if( in_array( $img_ext, $extentions ) ){
								$filename = date("YmdHis").$filename;
								$upload_success = $file->move($dest_path, $filename);
								
								$IMG_array[] = asset("images/custom/".$filename);
								$uploadcount ++;
							}
						}
					}
				}
				if(!empty($inputs["section0"]))
				{
					$comma = ",";
				}
				else
				{
					$comma = "";
				}
				if(isset($IMG_array))
				{
					$inputs["section0"] .= $comma.implode(",",$IMG_array);
				}
				//print_r($inputs["section0"]);
				//$inputs["section0"] = implode(",",$inputs["section0"][]);
			}
			*/
			/*foreach($inputs["section0_image"] as $key_image => $value_image)
			{
				echo "key_image -- ".$key_image."<br>";
				echo "value_image -- ".$value_image."<br>";
				echo "File Exists -- ".$request->hasFile($inputs["section0_image"][$key_image])."<br>";
				if( $request->hasFile($inputs["section0_image"][$key_image]) ){
					if ($request->file($inputs["section0_image"][$key_image])->isValid()) {

						$image_ext = $request->file($inputs["section0_image"][$key_image])->getClientOriginalExtension();
						echo "Valid <br> Extension -- ".$image_ext."<br>";
						if( in_array( $image_ext, $extentions ) ){

							$img_name = date("YmdHis")."section0".".".$image_ext;

							$request->file($inputs["section0_image"][$key_image])->move($dest_path,$img_name);

							$inputs["section0"][$key_image]["image"] = asset("images/custom/".$img_name);

						}

					}
				}
			}*/
			
			foreach( $image_fields as $img_fld=>$sec ){
				if( $request->hasFile($img_fld) ){

					if ($request->file($img_fld)->isValid()) {

						$ext = $request->file($img_fld)->getClientOriginalExtension();

						if( in_array( $ext, $extentions ) ){

							$name = date("YmdHis").$sec.".".$ext;

							$request->file($img_fld)->move($dest_path,$name);

							$inputs[$sec]["image"] = asset("images/custom/".$name);

						}

					}

				}

			}


			

		}elseif( $page_name == "testimonials" ){

			$fields = array("testimonials");

			$image_fields = array();



			foreach( $inputs["testimonials"] as $key=>$val ){

				$image_fields[$key] = "testimonial".$key."_image";

			}

			$valid = 1;

			//upload images

			foreach( $image_fields as $sec=>$img_fld ){

				if( $request->hasFile($img_fld) ){

					if ($request->file($img_fld)->isValid()) {

						$ext = $request->file($img_fld)->getClientOriginalExtension();

						if( in_array( $ext, $extentions ) ){

							$name = date("YmdHis").$sec.".".$ext;

							$request->file($img_fld)->move($dest_path,$name);

							$inputs["testimonials"][$sec]["image"] = asset("images/custom/".$name);

						}

					}

				}

			}

			

		}elseif( $page_name == "sa_dashboard" ){

		

			$fields = array("dashboard_text","dashboard_image");

			$valid = 1;

			//upload images

			if( $request->hasFile("image") ){

				if ($request->file("image")->isValid()) {

					$ext = $request->file("image")->getClientOriginalExtension();

					if( in_array( $ext, $extentions ) ){

						$name = date("YmdHis").".".$ext;

						$request->file("image")->move($dest_path,$name);

						$inputs["dashboard_image"] = asset("images/custom/".$name);

					}

				}

			}

		

		}elseif( $page_name == "sa_marketing" ){

		

			$fields = array("marketing_text","brochure_image","business_card_image");

			$image_fields = array("brochure_image"=>"brochure_img","business_card_image"=>"business_card_img");

			

			$valid = 1;

			//upload images

			foreach( $image_fields as $sec=>$img_fld ){

				if( $request->hasFile($img_fld) ){

					if ($request->file($img_fld)->isValid()) {

						$ext = $request->file($img_fld)->getClientOriginalExtension();



						if( in_array( $ext, $extentions ) ){

							$name = date("YmdHis").$sec.".".$ext;

							$request->file($img_fld)->move($dest_path,$name);

							$inputs[$sec] = asset("images/custom/".$name);

						}

					}

				}

			}

		

		}elseif( $page_name == "social_sharing" ){

		

			$fields = array("title","description","image", "image_linkedIn");

			$image_fields = array("image"=>"social_img", "image_linkedIn" => "social_img_linkedIn");

			

			$valid = 1;

			//upload images

			foreach( $image_fields as $sec=>$img_fld ){

				if( $request->hasFile($img_fld) ){

					if ($request->file($img_fld)->isValid()) {

						$ext = $request->file($img_fld)->getClientOriginalExtension();



						if( in_array( $ext, $extentions ) ){

							$name = date("YmdHis").$sec.".".$ext;

							$request->file($img_fld)->move($dest_path,$name);

							$inputs[$sec] = asset("images/custom/".$name);

						}

					}

				}

			}

		

		}elseif( $page_name == "owner_content" ){

		

			$fields = array("signage_content","signage_image");

			$image_fields = array("signage_image"=>"image");

			

			$valid = 1;

			//upload images

			foreach( $image_fields as $sec=>$img_fld ){

				if( $request->hasFile($img_fld) ){

					if ($request->file($img_fld)->isValid()) {

						$ext = $request->file($img_fld)->getClientOriginalExtension();



						if( in_array( $ext, $extentions ) ){

							$name = date("YmdHis").$sec.".".$ext;

							$request->file($img_fld)->move($dest_path,$name);

							$inputs[$sec] = asset("images/custom/".$name);

						}

					}

				}

			}

		

		}elseif( $page_name == "automated_emails" ){

			$fields = array("registration","registration_sa","registration_sm","registration_order","order_placement","order_replacement","hire_meter");

			$valid = 1;

		}

		

		if( !$valid ){

			return Redirect::to("admin/managecontent")->withInput($request->except('_token'))->withErrors( array("There was some problem, try again later.") );

			exit;

		}

		

		foreach( $fields  as $field ):

			$page_array[$field] = ( Input::has($field) or isset($inputs[$field]) ) ? $inputs[$field] : "";
			/*if($field == "section0")
			{
				$header_slider_urls = ( Input::has($field) or isset($inputs[$field]) ) ? $inputs[$field] : "";
				$header_slider_url = explode(",", $header_slider_urls);
				foreach($header_slider_url as $img_url)
				{
					$page_array["section0"][] = $img_url;
				}
			}*/

		endforeach;
		

		if( $page_name == "faq" || $page_name == "faq_landowner" || $page_name == "faq_sa" ){

			$questions = array();

			$answers = array();

			foreach( $page_array["questions"] as $key=>$ques ){

				if( trim($ques) != '' ){

					$questions[] = $ques;

					$answers[] = $page_array["answers"][$key];

				}

			}

			$page_array["questions"] = $questions;

			$page_array["answers"] = $answers;

		}



		$page_content = json_encode($page_array);

		

		$check = PageContent::where( "page_name", $page_name );



		if( $check->count() ):

			$check = $check->get();

			$page = PageContent::find($check[0]->id);

			$page->page_content = $page_content;

			$page->update();

		else:

			$page = new PageContent;

			$page->page_name = $page_name;

			$page->page_content = $page_content;

			$page->save();

		endif;



		return Redirect::to("admin/managecontent/".$page_name)->with( "success", "Data saved." );

		

	}

	

	

	public function getSettings(){

		

		$settings = Settings::orderBy("id","desc")->first();



		return view("admin.settings",compact("settings"));

	}

	

	public function postSettings( Request $request ){

		$inputs = $request->all();
		$variable_rates 	= ( (isset($inputs['variable_rates']) && $inputs['variable_rates'] == "on")?1:0 );
		
		$email_feature 		= ( (isset($inputs['email_feature']) && $inputs['email_feature'] == "on")?1:0 );
		
		$sms_feature 		= ( (isset($inputs['sms_feature']) && $inputs['sms_feature'] == "on")?1:0 );

		$settings = new Settings;

		$settings->sm_commission = $inputs["sm_commission"];
		
		$settings->sa_commission = $inputs["sa_commission"];

		$settings->promo_code_discount_lo = $inputs["promo_code_discount_lo"];

		$settings->meter_price_lo = $inputs["meter_price_lo"];
		
		$settings->ship_price_lo = $inputs["ship_price_lo"];
		
		$settings->variable_rates = $variable_rates ;
		
		$settings->email_feature = $email_feature ;
		
		$settings->sms_feature = $sms_feature ;

		$settings->save();

		

		return Redirect::to("admin/settings")->with( "success", "Settings saved." );

		

	}

	
	public function commissions_list($inputs,$role_id){

		$vars = array();

		if( Input::has("id") )
			$vars["id"] = $inputs["id"];
		else
			$vars["id"] = 0;

		$vars['users'] = User::where( "role_id",$role_id )->where("deleted",0)->get();

		if( $role_id == 2 ){
			$vars['user_type'] = 'Sales Associate';
			$vars['ref_type'] = 'Landowners';
			$vars['form_action'] = URL::to('/admin/commissions');
		}else{
			$vars['user_type'] = 'Sales Manager';
			$vars['ref_type'] = 'Sales Associates';
			$vars['form_action'] = URL::to('/admin/sm-commissions');
		}

		if( $vars["id"] > 0 ){

			$vars["referrals"]	= Referral::join("users","users.id","=","referrals.user_id")->where("referrals.referred_by",$vars["id"])->where("users.deleted",0)->select("referrals.*","users.name")->get();

		}
		$vars['settings'] = Settings::orderBy("id","desc")->first();
		return view("admin.manage-commissions",compact("vars"));
	}

	

	public function getCommissions( Request $request ){

		//return $this->commissions_list($request->all(),$this->sales_associate); // Manage Commission System have been removed. As Admin can manage the commissions of users with the edituser functionality

		/*$inputs = $request->all();

		

		$vars = array();

		

		if( Input::has("sa_id") )

			$vars["sa_id"] = $inputs["sa_id"];

		else

			$vars["sa_id"] = 0;

		

		$vars['sales_associates'] = User::where( "role_id",$this->sales_associate )->where("deleted",0)->get();

		

		if( $vars["sa_id"] > 0 ){

			$vars["referrals"]	= Referral::join("users","users.id","=","referrals.user_id")->where("referrals.referred_by",$vars["sa_id"])->where("users.deleted",0)->select("referrals.*","users.name")->get();

		}

		

		return view("admin.manage-commissions",compact("vars"));*/

	}

	public function getSmCommissions( Request $request ){

		return $this->commissions_list($request->all(),$this->sales_manager);

	}

	

	public function postCommissions( Request $request ){ // Manage Commission System have been removed. As Admin can manage the commissions of users with the edituser functionality
		/*

		$inputs = $request->all();

		$message = array("Something went wrong. Please try agian.");

		
		$redirect_to = Input::has("form_action") ? $inputs["form_action"] : "admin/commissions";
		

		if( Input::has("id") and $inputs["id"] > 0 ){

			$id = $inputs["id"];

			$referrals	= Referral::join("users","users.id","=","referrals.user_id")->where("referrals.referred_by",$id)->where("users.deleted",0)->select("referrals.*","users.name")->get();

			foreach( $referrals as $ref ){

				if( Input::has("commission_".$ref->id) ){

					$thisRef = Referral::find($ref->id);

					$thisRef->commission = $inputs["commission_".$ref->id];

					$thisRef->update();

				}	

			}

			return Redirect::to($redirect_to."?id=".$id)->with( "success", "Commissions updated." );

		}

		

		return Redirect::to($redirect_to)->withErrors( $message )->withInput($request->except('_token'));

	*/}
	
	public function postSingleorder(Request $request){
		$inputs = $request->all(); 
		
		//$order_info = Payment::find($inputs["order_id"]);
		
			
		$order_info = Payment::join("users","users.id","=","payments.user_id")->where(['payments.id' => $inputs["order_id"]])->select("payments.shipping_address","payments.shipped_meters","users.referred_by","payments.shipping_city","payments.shipping_postal","payments.shipping_province")->get();
		if($order_info[0]->referred_by != 0){
			$refer_by = User::find($order_info[0]->referred_by);
			$order_info[0]->referred_by = $refer_by["name"];
		}
		return $order_info;
	}

	public function getOrders(Request $request){
		$input = $request->all();
		//$placed_orders = Payment::where("payment_type","sign_buy")->get();
		
		//$m_id = Meter::join("users","users.id","=","meters.user_id")->where( [ 'meters.meter_id'=>$meter_id, "meters.status"=>1, "users.deleted"=>0 ])->select("meters.id","meters.lot_id","meters.expiry")->get();
		
		$placed_orders = Payment::join("users","users.id","=","payments.user_id")->where(['payments.payment_type' => "sign_buy"])->select("payments.amount","payments.created_at","users.name","users.email","payments.id","payments.shipping_status")->orderBy('payments.id', 'desc')->get(); 
		//dd($placed_orders);
		//SELECT payments.amount,payments.created_at,users.name,users.email,payments.id FROM `payments` join `users` on payments.user_id = users.id and payments.payment_type = "sign_buy"
		$vars = array( );
		return view('admin.orders', compact(['placed_orders','vars']));
	}
	
	public function postUpdatestatus ( Request $request ){
		
		$inputs = $request->all(); 
		$order = Payment::find($inputs["order"]);
		$order->shipping_status = $inputs["shipping_status"];
		$order->update();	
		return "Order Updated!";	
	}
	
	/* Display Towing companies in Admin Dashboard */
	public function getCompanies(Request $request){
		
		$states = City::where("country_id",2)->groupBy("state_code")->get();
		
		$companies = towing_companies::where(["country_id"=>2])->select("id","city_name","company","contact_no","state_code")->get();
		$vars = array( );
		$vars["companies"] = $companies;
		$vars["states"] = $states;
		return view('admin.companies', compact(['vars','vars'])); 
		//print_r($companies);
		
	}
	
	/*public function posttowing_companies(Request $request){
		$inputs = $request->all();
	}*/
	
	/* Add a new Towing Company*/
	public function postCreatenewcompany( Request $request ){
		
		$inputs = $request->all();
		
		//add validation rules

		$rules = array(

			'company' => 'required|max:255',

			'contact_no' => 'max:20',

			'state' => 'required',

			'city' => 'required|max:255'

        );

		//validate input data

		$validator = Validator::make($inputs, $rules);
		
		//if validation fails

		if($validator->fails()){

			//get error messages

			$messages = $validator->messages()->all();

			//return with error message

			return Redirect::to('/admin/managecontent')->withInput($request->except('_token'))->withErrors($messages);

			exit;

		}else{
			if(isset($inputs["city"]) && !empty($inputs["city"])){
				$cities = City::where("id",$inputs['city'])->get(); 
				$city_name = $cities[0]->city_name;
			}else{
				$city_name = "";
			}
			
			$inserted = DB::insert('insert into towing_companies (city_id, city_name, state_code, company, contact_no, country_id) values ("'.$inputs["city"].'","'.$city_name.'","'.$inputs["state"].'","'.$inputs["company"].'","'.$inputs["contact_no"].'",2)');
			if($inserted){
				//redirect with success message
				return Redirect::to('/admin/managecontent/companies')->with('success', 'Towing Company has been added.');
			}
		}
	}
	public function getEditcompany( Request $request ){
		$inputs = $request->all();
		$company_id = ($request->has("id") && $inputs["id"]>0)?$inputs["id"]:0;
		if($company_id == 0){ return Redirect::to('admin/companies'); }
		$result = towing_companies::find($company_id);
		
		$company_data = towing_companies::find($result['id']);
		$states = City::where("country_id",2)->groupBy("state_code")->get();
		$cities = City::where("country_id",2)->where("state_code",$company_data['state_code'])->get();
		
		return view('admin.editCompany',compact('states','company_data','cities')); 
	}
	public function getPayoutsemt(Request $request){
		$input = $request->all();
		$user_role = $input["pay"];
		$role_id = "";
		if(!empty( $user_role )){
			if($user_role == "lo"){
				$role_id = 3;
			}elseif($user_role == "sa"){
				$role_id = 2;
			}elseif($user_role == "sm"){
				$role_id = 5;
			}else{
			}
		}
		//echo "<pre>"; print_r($input["pay"]); echo "</pre>"; //exit();
		$payouts = User::whereIn("users.role_id",[2,3,5])->whereRaw('users.balance > 50')->select(DB::raw("users.id,users.email,users.balance,users.name,users.security_answer"))->get();
		
		$count = DB::table('payouts_EMT')->where('STATUS','pending')->count();
		
		//echo "<pre>"; print_r($payouts); echo "</pre>";  
		//DB::table('payouts_EMT')->delete();
		
		if( $count>0 ){
			
			//echo "reexported"; 
			
			return Redirect::to('/admin/payments?pay=exported&confirm=1');
			
		}else{
		
			foreach($payouts as $payout){
			
			//$payout[];
			/* Drop the table to prevent from duplicate entries : temp solution untill we implement the back excel uploading to deduct the paid amount */
			
			$exists = DB::table('payouts_EMT')->where('user_id',$payout["id"])->exists();
			
			if($exists){
				
				$data = DB::table('payouts_EMT')->select('amount')->where('user_id',$payout["id"])->get();
				
				$amount = ( isset($data[0]->amount)?$data[0]->amount:0 );
				
				if($amount != $payout["balance"]){
					/* Update if balance unmatched */
					DB::table('payouts_EMT')->where('user_id',$payout["id"])->update([ 'amount'=>$payout["balance"] ]);
				}
			}else{
			
				//DB::statement('drop table payouts_EMT');			/* Insert downloaded payouts details */
				$inserted = DB::insert('insert into payouts_EMT (user_id, payee_email, amount, status, created_at , PayeeName) values ("'.$payout["id"].'","'.$payout["email"].'","'.$payout["balance"].'","pending", "'. date('Y-m-d H:i:s') .'","'.$payout["name"].'" )');
			
				if($inserted){
				}
			}
		}
		
			 
		
			if($role_id != ""){
				$payouts = User::where("users.role_id",$role_id)->whereRaw('users.balance > 50')->select(DB::raw("users.id,users.email,users.balance,users.name,users.security_answer"))->get();
			}
			//echo "<pre>"; print_r($payouts); exit();
			$data = view('admin.payoutsemt',compact('payouts',$payouts)); 
			
			
			
			Excel::create(Auth::user()->id, function($excel) use($payouts){
				$excel->sheet('Payouts', function($sheet) use($payouts){ 
					 $sheet->loadView('admin.payoutsemt', array('pageTitle' => 'Monthly Payouts', 'payouts' =>$payouts));
					});
			})->export('xls');
						
		}
		//echo $data;
		exit();
	}

	
	public function postEditcompany( Request $request ){
		$input = $request->all();
		
		$company_id = ($request->has("id") && $input["id"] > 0) ? $input["id"] : 0;
		if( $company_id == 0 ) return Redirect::to("/admin/companies"); 
		
		$rules = array(

			'company' => 'required|max:255',

			'contact_no' => 'max:20',

			'state' => 'required',

			'city' => 'required|max:255'

        );
		
		//validate input data

		$validator = Validator::make($input, $rules);
		
		//if validation fails

		if($validator->fails()){

			//get error messages

			$messages = $validator->messages()->all();

			//return with error message

			return Redirect::to('/admin/editcompany?id='.$company_id)->withInput()->withErrors($messages);

			exit;

		}
		
		$companies_fields = array("name","last_name","street","city","country","commission");
		
		if(isset($input["city"]) && !empty($input["city"])){
			$cities = City::where("id",$input['city'])->get(); 
			$city = $cities[0]->city_name;
		}else{
			$city = "";
		}
		
		$if_inserted = DB::table('towing_companies')
            ->where('id', $company_id)
            ->update(array('state_code' => $input["state"] , 'contact_no' => $input["contact_no"] , 'company' => $input["company"] , 'city_id' => $input["city"] , 'city_name' => $city));
		
		if($if_inserted){
			//redirect with success message
			return Redirect::to('/admin/managecontent/companies')->with('success', 'Towing Company has been updated.');
		}
			
		//dd($input);
	}
	
	public function getCompanydelete(Request $request){

		$input = $request->all();

		$company_id = ($request->has("id") && $input["id"] > 0) ? $input["id"] : 0;

		$company = towing_companies::find($company_id);

		$company->delete();

		return Redirect::to('admin/managecontent/companies')->with('success','Company deleted successfully.');

	}
	
	public function getClient(Request $request){

		$input = $request->all();
		$vars = array();
		$vars['export'] = '';
		if(isset($input["id"]) && !empty($input["id"])){
				
			$landowner_users = User::where("role_id",3)->where("created_by_admin",1)->get();
			$vars['landowner_users'] = $landowner_users;
			$vars["inspectionsURL"] = app("App\Http\Controllers\UtilsController")->inspectionsURL($input["id"]);
		}else{
			$vars["inspectionsURL"] = '';
		}
		$where = array( "meters.user_id" => $input["id"] , "meters.status" => 1 );
		$payment_where = "";
			//if( $vars['start_date'] != '' )	$payment_where .= " AND payments.created_at >= '". date("Y-m-d H:i:s",strtotime($vars['start_date'])) ."' ";
			//if( $vars['end_date'] != '' )	$payment_where .= " AND payments.created_at <= '". date("Y-m-d H:i:s",strtotime($vars['end_date'])) ."' ";
		
		$user_id = $input["id"];
		
		$client = User::find($user_id);
		
		$countries = Country::where("status",1)->orderBy('id', 'desc')->get();

		$selected_country = ($client['country'] > 0) ? $client['country'] : $countries[0]->id;

		$states = City::where("country_id",$selected_country)->groupBy("state_code")->get();

		$settings = Settings::orderBy("id","desc")->first();

		$cities = City::where("country_id",$client['country'])->where("state_code",$client['state'])->get();
		
		/* Fetch groups allocated to the meters of a landowner */
		$meter = new Meter;
		$group_ids = $meter->landowners_groups($user_id);
		
		$where_col = 'id';
		$group_obj = new Groups;
		$landowners_groups = $group_obj->Group_whereIn($where_col,$group_ids);
		
		$Groups = Groups::orderBy("id","asc")->get();
		
		$towing_detail = towing_companies::where('city_id',$client['city'])->get();
		//echo "<pre>"; print_r($towing_detail); echo "</pre>";
		//dd($client);
		/* filters */
		$vars['group_id'] = $request->has("group_id") ? $input["group_id"] : "";
		$vars['start_date'] = $request->has("start_date") ? $input["start_date"] : "";
		$vars['end_date'] = $request->has("end_date") ? $input["end_date"] : "";
		$vars['export']	=	$request->has("export") ? $input["export"] : "";
		
		if( $vars['group_id'] > 0 )	$where["meters.group_id"] = $vars['group_id'];

		$payment_where = "";
		if( $vars['start_date'] != '' )	$payment_where .= " AND payments.created_at >= '". date("Y-m-d H:i:s",strtotime($vars['start_date'])) ."' ";
		if( $vars['end_date'] != '' )	$payment_where .= " AND payments.created_at <= '". date("Y-m-d H:i:s",strtotime($vars['end_date'])) ."' ";
		
		$vars['meter_details'] = Meter::join("lots","meters.lot_id","=","lots.id")
						->where($where)
						->select("meters.id","meters.expiry","meters.meter_id","meters.lot_id","meters.group_id","lots.lot_name","meters.hour_price","lots.price",
							DB::raw("
								(select count(payment_items .id) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id  ". $payment_where ." ) as transactions, 
								
								(select sum(payment_items .total_price * payment_items .payment_commission / 100) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id  ". $payment_where ."  ) as landowner_revenue, 
								
								(select sum( ( 100.00 - payment_items .payment_commission ) * payment_items .total_price / 100) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id  ". $payment_where ."  ) as my_meter_revenue, 
								
								(select sum(payment_items.hours) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id   ". $payment_where ."  ) as total_hours")
						)->get();
		
		if( $vars['export'] == "PDF" ){
			$vars = array('vars'=>$vars);
			$paper_size = array(0,0,611.71,792);
			$pdf1 = PDF::setPaper($paper_size, 'portrait');
		
			
			$pdf = $pdf1->loadView('includes.clients_reports', $vars);

			return $pdf->download('Meter Report.pdf');
			
		}
		
		return view('admin.client',compact('vars','countries','states','cities','client','towing_detail','Groups','landowners_groups')); // 

	}
	
	public function getSignagezip(Request $request)
	{
		$inputs = $request->all(); //echo "<pre>"; print_r($inputs); echo "</pre>"; exit();
		//public_path('My-Meter Signage'.$vars['lot_id'].'.zip')
		//$download_response =  response()->download('/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public/My-Meter Signage'.$inputs['lot_id'].'.zip')->deleteFileAfterSend(true);
		
		$public_path = public_path(); 
		//$public_path = "/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public";  
		$folder_name = '/My-Meter Signage'.$inputs['lot_id'];
		
		$dir_path = $public_path."/".$folder_name;
		
		if(is_dir($dir_path)) {
		
			$files = glob($dir_path.'/*.jpg'); // get all file names
			
			foreach($files as $file){ // iterate files
				if(is_file($file))
				{
					
					unlink($file); // delete file
				}
				
			}
			//rmdir($dir_path);
			
			if(!rmdir($dir_path))
			{
				echo ("Could not remove $path");
				exit();
			}
			
		}
		$public_path = public_path(); 
		return response()->download($public_path.'/My-Meter Signage'.$inputs['lot_id'].'.zip')->deleteFileAfterSend(true);
		//echo "download_response : ".$download_response; exit();
		/*if(!empty($download_response)){
			return Redirect::to("/admin/client?id=".$inputs['client_id']); 
		}*/

	}
	
	
	public function postClient(Request $request){
		$input = $request->all();
		$client_id = $input['client_id'];
		$base_price = config("meter_base_price");
		//echo "<pre>"; print_r($input); echo "</pre>"; exit(); 
		
		if(isset($input['grouping_method']) && $input['grouping_method'] == 1){
			
			/* Add a group */
			
			$groups = new Groups;
	
			$groups->group_name = $input['group_name'];
	
			$groups->commission = $input['commission'];
	
			$groups->save();
			
			$group_id = $groups->id;
			
		}else{
			
			/* Use existing group */
			
			$group_id = $input['group_id'];
		}
		
		/* Create a Default lot for the user if doesn't exists */
		$lot_detail = Lot::where("user_id",$client_id)->get();
		//print_r($lot_detail); exit();
		if(isset($lot_detail) && count($lot_detail) > 0){
			if(isset($lot_detail[0]["id"]) && !empty($lot_detail[0]["id"])){
				$lot_id = $lot_detail[0]["id"];
			}
		}else{
			$lot = new Lot;
			$lot->lot_name = "Group 1";
			$lot->user_id = $client_id;
			$lot->price = $input["price"];
			if( Input::has('city') ){
				
				$city =  City::find($input["city"]);
				$lot->lot_city = $city->city_name;
			}
			$lot->save();
			$lot_id = $lot->id;
		}
		
		/* Add a meter */
		$meter_IDs = array();
		for( $i=0; $i<$input["meter_count"]; $i++ ){
			$meter = new Meter;
			$meterID = Meter::max('meter_id');
			$meterID = ($meterID > 0 && strlen($meterID) == 6)?$meterID+1:100001;
			$meter->meter_id = $meterID;
			$meter_IDs[] = $meter->meter_id;
			$meter->lot_id = $lot_id;
			$meter->user_id = $client_id;
			$meter->base_price = $base_price;
			$meter->hour_price = $input["price"];
			$meter->status = 1;
			$meter->group_id = $group_id;
			
			$meter->save();
			//$vals[$i] = array('meter_id'=>$meter->id,'hour_price'=>$meter->hour_price);
		}
		
		$meter_ids = implode(',',$meter_IDs);
		
		$data = array();
		
		$data["meter_id"] = $meter_ids;
		$data["lot_id"] = $lot_id;
		$data["price"] = $input["price"];
		$data["towing_contact"] = $input["towing_contact"];
		
		
		/* Request for signage */
		$response = app('App\Http\Controllers\HomeController')->generateSignage($request,$data);
		
		if($response == 1){
			//app('App\Http\Controllers\HomeController')->signageZip();
			return Redirect::to("/admin/signagezip?lot_id=".$lot_id."&client_id=".$client_id);
			//echo "TEst"; //exit();
		}
		
		//$gh = HomeController::generateSignage($request,$data);
		//return Redirect::to("/admin/client?id=".$client_id);
		
	}
	
	public function postSharefeed(Request $request){
		
		$inputs = $request->all();
		$user = new User;
		$share_feed = $user->sendmail_or_text($inputs);
		if($share_feed == 1){
			return Redirect::to("/admin/client?id=".$inputs['client_id'])->with('success', 'Message Sent Successfully.');
		}
	}
	
	public function postUpdatemetergroup(Request $request){
		$input = $request->all();
		$userid = $input["client_id"];

		$checkMeterExists = Meter::where([ 'user_id'=>$userid, 'id'=>$input["meter_id"] ])->count();
		if( $checkMeterExists ){
			Meter::where('user_id',$userid)->wherein('id', explode(",",$input["meter_id"]))->update(['group_id' => $input["group_id"]]);
			return Redirect::to('/admin/client?id='.$input['client_id'])->with('success', "Data saved.");
		}
		else{
			return Redirect::to('/admin')->withErrors(["Invalid data found."]);
		}

	}
	
	public function postPayouthistory(Request $request){
		$input = $request->all();
		$html = "";
		
		/************* Fetch Payouts *************/
		
		if(isset( $input["user_id"] )){
			$user_id = $input["user_id"];
			$payouts = Payout::where("user_id",$user_id)->select('amount','created_at')->get()->toArray();
		}
		
		$html .= "
			<script>
			
			initDatatable('#payout_history_datatable');
			</script>
			<div><table id='payout_history_datatable' class='datatable display compact ' style='width:100%;'>";
		
		$html .= "<thead><tr> <th> Date </th> <th> Amount </th> </tr></thead>";
		$total_amount = 0;
			foreach( $payouts as  $payout ){
				$html .= "<tr>";
					
					$html .= "<td>".$payout['created_at']."</td>";
					
					$html .= "<td>".$payout['amount']."</td>";
					
					$total_amount = $total_amount+$payout['amount'];
					
				$html .= "</tr>";
			}
			
		$html .= "<tfoot> <tr> <td></td>  <td>$".number_format($total_amount,2)."</td> </tr> </tfoot></table></div>";
		
		return $html;
		//echo "<pre>"; print_r($payouts); echo "</pre>";
		//echo "<pre>"; print_r($input); echo "</pre>";
	}
	
	public function postUpdatepayoutstatus ( Request $request ){
		
		$inputs = $request->all(); 
		if( isset($inputs["payout_id"]) ){
			$payout_id = $inputs["payout_id"];
			if( isset($inputs["payout_status"]) && $inputs["payout_status"] == "Paid" ){
				
				$data = DB::table('payouts_EMT')->where('id', '=', $payout_id)->get();
				
				DB::table('payouts_EMT')->where('id', '=', $payout_id)->delete();
				
				$inserted = DB::insert("INSERT INTO payouts ( user_id, amount, trans_id, trans_status, trans_response, created_at ) VALUES( '". $data[0]->user_id ."', '".$data[0]->amount ."', '', 'success', 'success', '". date('Y-m-d H:i:s') ."' )");
				
				/* Deduct paid amount from current balance of user */
				$user_id = $data[0]->user_id; 
				$paid_amount = $data[0]->amount;
				$user_balance = DB::table('users')->where('id',$user_id)->select('balance')->get(); //echo "<pre>"; print_r($user_balance); exit();
				if($user_balance[0]->balance >= $paid_amount){
					
					$balance = $user_balance[0]->balance - $paid_amount; //exit();
					//DB::table('payouts_EMT')->where('user_id',$payout["id"])->update('amount',$payout["balance"]);
					DB::table('users')->where('id',$user_id)->update(['balance' => $balance]);
				}
					
			}
			
		}
		return "Updated!";
		//echo "<pre>"; print_r($inputs); echo "</pre>"; exit(); 
		//$order = Payment::find($inputs["order"]);
		//$order->shipping_status = $inputs["shipping_status"];
		//$order->update();	
		//return "Order Updated!";	
	}
	
}



		
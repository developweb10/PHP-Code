<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Hash; use DB; use Auth; use Input; use DateTime; use View; use URL; use Cookie; use App;
use App\Lot; use App\Meter; use App\Payment; use App\PaymentItem; use App\User; use App\Referral; use App\ReferralCommission; use App\Country; use App\SmsQueue;  use App\Role; use App\Payout;  use App\PageContent; use App\City;
use PDF, Crypt;

class SmController extends Controller {
	
	public function __construct()
	{
		$this->middleware('auth');

		if( Auth::user() && Auth::user()->role_id != 5 ) {
			Redirect::to('/')->send();
			exit;
		}
		
		$this->user = Auth::user();
		//force to accept terms at first login
		if( $this->user->is_agreed == 0 ){
			$data = PageContent::where("page_name","sm_agreement")->get();
			$data = json_decode($data[0]->page_content);
			$terms = View::make("sm.terms",compact("data"));
			echo $terms;
			exit;
		}
		
	}
	
	public function index(Request $request,$tab=''){

		$user = $this->user;
		$action = 'decrypt';

		if(!empty($user->account_no))
			$user->account_no = app("App\Http\Controllers\UtilsController")->encrypt_decrypt($action,$user->account_no);	

		if(!empty($user->transit_no))
			$user->transit_no = app("App\Http\Controllers\UtilsController")->encrypt_decrypt($action,$user->transit_no);		

		$input = $request->all();
		$oldInputs = Input::old();

		$vars = array( "tab"=>$tab );

		if( $tab == 'account' && count($oldInputs) ){
			$temp_arr = $user->toArray();
			$temp_arr = array_merge($temp_arr,$oldInputs);
			$user = (object)$temp_arr;
			unset($temp_arr);
		}else{
			$user = (object)$user->toArray();
		}
		
		$vars['start_date'] = $request->has("start_date") ? $input["start_date"] : "";
		$vars['end_date'] = $request->has("end_date") ? $input["end_date"] : "";

	
		///////////////////////////////////////////////FOR OVERVIEW TAB////////////////////////////////////////////////////////////////////
		//get current user referral details
		$referrals = Referral::where( function( $obj ) use( $user,$vars ) {
			$obj->where("referred_by",$user->id);
			if( $vars['start_date'] != '' )  $obj->where("created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );
			if( $vars['end_date'] != '' )  $obj->where("created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );
		} )->get();
		
		
	/*	$getTotalUsers = User::where( function( $obj ) use( $user,$vars ) {
			$obj->where("created_by",$user->id);
		})->get();
		*/

		//get referal comission details		
		$net_comm = ReferralCommission::join("referrals","referrals.id","=","referral_commissons.referral_id")->where( function( $obj ) use( $user,$vars	 ){
			$obj->where("referrals.referred_by",$user->id);
			if( $vars['start_date'] != '' )  $obj->where("referral_commissons.created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );
			if( $vars['end_date'] != '' )  $obj->where("referral_commissons.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );
		} )->select(DB::raw("sum(referral_commissons.commission_amount) as total_commission"))->pluck("total_commission");
		

		/*set variables*/
		
		$vars["role"] = $this->user->role()->pluck("name");
		$vars["affilliateURL"] = app("App\Http\Controllers\UtilsController")->affilliateURL($user->promo_code);		
		$vars["next_pay"] = $user->balance;
		
		///////////////////////////////////////////////END OVERVIEW TAB/////////////////////////////////////////////////////////////////////	
		
		////////////////////////////////////////////////////////////////////////////////START REPORT TAB///////////////////////////////////////////////////////////
		$vars['referrals'] = Referral::join("users","referrals.user_id","=","users.id")
				->leftJoin("referral_commissons","referrals.id","=","referral_commissons.referral_id")
				->where(  function( $obj ) use($vars,$user) {
					$obj->where( "referrals.referred_by", "=", $user->id );
					if( $vars['start_date'] != '' )		$obj->where( "referral_commissons.created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );
					if( $vars['end_date'] != '' )		$obj->where( "referral_commissons.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );
				} )
				->select("referrals.created_at","referrals.commission","users.name",DB::raw("sum(referral_commissons.commission_amount) as total_commission"))
				->groupBy("referrals.user_id")
				->get();
		////////////////////////////////////////////////////////////////////////////////END REPORT TAB//////////////////////////////////////////////////////////////////
		
		
		////////////////////////////////////////////////////////////////////////////////START PAYMENTS TAB///////////////////////////////////////////////////////////
		$vars['user'] = (object) array();
		$vars['user']->security_answer = $user->security_answer;
		
		$vars['payments'] = Payout::where( function( $obj ) use( $user ) {
				$obj->where( "user_id","=",$user->id );
			} )->get()->toArray();
		////////////////////////////////////////////////////////////////////////////////END PAYMENTS TAB//////////////////////////////////////////////////////////////////
		
		
		////////////////////////////////////////////////////////////////////////////////START ACCOUNT TAB///////////////////////////////////////////////////////////
		$countries = Country::where("status",1)->get();
		//$selected_country = ($user->country > 0) ? $user->country : $countries[0]->id;
		$selected_country = ($user->country > 0) ? $user->country :2;  
		
		$selected_country_info = Country::where("id",$selected_country)->get();
		$states = City::where("country_id",$selected_country)->groupBy("state_code")->get();

		$cities = City::where("country_id",$user->country)->where("state_code",$user->state)->get();

		$banks = app("App\Http\Controllers\HomeController")->get_payment_banks($request,'',$selected_country_info[0]->iso);
		

		////////////////////////////////////////////////////////////////////////////////END ACCOUNT TAB//////////////////////////////////////////////////////////////////		
		
		$vars["overview_data"] = PageContent::where( "page_name", "sa_dashboard" )->get();
		if( count($vars["overview_data"]) > 0 ){
			$vars["overview_data"] = json_decode($vars["overview_data"][0]->page_content);
		}else{
			$vars["overview_data"]->dashboard_text = "";
			$vars["overview_data"]->dashboard_image = "";
		}
	
	
		$vars["agreement_data"] = PageContent::where( "page_name", "sm_agreement" )->get();
		if( count($vars["agreement_data"]) > 0 ){
			$vars["agreement_data"] = json_decode($vars["agreement_data"][0]->page_content);
		}else{
			$vars["agreement_data"]->page_title = "";
			$vars["agreement_data"]->page_content = "";
	}
	
		$dateObj   = DateTime::createFromFormat('!m', date("m")+1);
		$vars["next_month"] = $dateObj->format('F');
		$role_id = $request->has("role") ? $input["role"] : "";

		$status = $request->has("status") ? $input["status"] : "";

		//get sales associates

		$sa_users = User::leftJoin("cities","cities.id","=","users.city")->where( "users.created_by",Auth::user()->id)->select("users.*","cities.city_name")->get();	
		$vars["signups"] = $sa_users->count();
		$vars['sa_sinups'] = array();
		$vars['sa_revenue'] = array();
		$vars['sa_commissions'] = array();
		$net_commissions =0.00;
		foreach( $sa_users as $sa){
			
			//signups column	
			$vars['sa_sinups'][$sa->id] = Referral::where("referred_by",$sa->id)->get()->count();
			
			// revenue column
			$vars['sa_revenue'][$sa->id] = ReferralCommission::join("referrals","referrals.id","=","referral_commissons.referral_id")->where("referrals.referred_by",$sa->id)->select(DB::raw("sum(referral_commissons.commission_amount) as total_commission"))->pluck("total_commission");
			
			//commession column
			$vars['sa_commissions'][$sa->id] = ReferralCommission::join("referrals","referrals.id","=","referral_commissons.referral_id")->where("referrals.referred_by",$sa->id)->select(DB::raw("sum(referral_commissons.manager_commission_amount) as total_manager_commission"))->pluck("total_manager_commission");
			$net_commissions += $vars['sa_commissions'][$sa->id];
		}
		$vars["net_commissions"] = $net_commissions ;	

		return view('manager-home', compact('user','countries','data','states','cities','sa_users','banks'))->with("vars",$vars);

	}

	
	public function getCreateuser( Request $request ){

		$inputs = $request->all();

		$vars['show_layout'] = Input::has("show_layout")?$inputs["show_layout"]:true;
		$countries = Country::where("status",1)->get();

		/*$selected_country = ($user['country'] > 0) ? $user['country'] : $countries[0]->id;

		$states = City::where("country_id",$selected_country)->groupBy("state_code")->get();
		$cities = City::where("country_id",$user['country'])->where("state_code",$user['state'])->get();*/
			
		return view('sm.edituser', compact('user','vars','countries'));

	}	
	
	public function postCreatenewuser( Request $request ){

		$input = $request->all();

		$user_id = 0;

		//,"email_payments"

		$user_fields = array("name","email","city","country","state","password");

		//add validation rules

		$rules = array(

			'name' => 'required|max:255',

			'email' => 'required|max:255|email|unique:users',

        );

		

		//validate input data

		$validator = Validator::make($input, $rules);

		

		//if validation fails

		if($validator->fails()){

			//get error messages

			$messages = $validator->messages()->all();

			//return with error message

			return Redirect::to('/sm-home')->withInput($request->except('_token'))->withErrors($messages);

			exit;

		}

		else{

			$generatePassword = str_random(6);

			$input["password"] = bcrypt($generatePassword);

			

			$user = new User;

			foreach($user_fields as $field){

				$user->{$field}= $input[$field] ;

			};
			
			$user->role_id = 2;
			$user->security_answer = uniqid() ;
			$user->created_by = Auth::user()->id;
		
			//update records
			$user->save();		


			Referral::create([
				'user_id' => $user->id,
				'referred_by' => Auth::user()->id,
				'commission' => config('sm_commission'),
				'referral_medium'	=> 'Sales Manager'
			]);


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

				app('App\Http\Controllers\HomeController')->createPromoCode($user->id);

				$subject = isset($get_content['registration']['subject']) ? $get_content['registration_sa']['subject']  : "Your account has been created!";

				$body = isset($get_content['registration']['body']) ? $get_content['registration_sa']['body']  : "";

			

			

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

			//$headers .= 'Cc: gurpreet.webtek@gmail.com' . "\r\n";

			

			mail($to,$subject,$body,$headers);	

		
				//redirect with success message

			return Redirect::to('/sm-home')->with('success', 'User created. An email with credentials has been sent.');

		}

	}

}

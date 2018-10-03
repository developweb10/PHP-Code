<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Settings;
use Hash; use DB; use Auth; use Input; use DateTime; use View; use URL; use Cookie; use Session;
use App\Lot; use App\Meter; use App\Payment; use App\PaymentItem; use App\User; use App\Referral; use App\ReferralCommission; use App\Country; use App\SmsQueue; use App\PageContent; use App\City; use App\PaymentAccount;
use App\towing_companies; 
use PDF, Crypt;
use Zipper;
use QrCode;
use App\Offer;
use App\VariableRate;
use App\LandownersSettings;

class HomeController extends Controller {

	 
	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */

	public function account(Request $request){
		$redirectURL = '/home/account';

		if( Auth::user()->role_id == 2 ){
			$redirectURL =  "/sa-home/account";
		}

		if( Auth::user()->role_id == 5 ){
			$redirectURL =  "/sm-home/account";
		}
		
		$input = $request->all();
		
		$userid = Auth::user()->id;
		
		$user_fields = array("name","street","city","state","last_name","zip");// "account_no","transit_no","bank_code","country",,"phone"
		//add validation rules
		$rules = [
			'name' => 'required|max:255',
			'street' => 'required|max:255',
			'city' => 'required|max:255',
			'state' => 'required|max:255',
			'zip' => 'required',
			'last_name' => 'required|max:255',
        ];
		/*
		'account_no' => 'required|max:255',
		'transit_no' => 'required',
		'bank_code' => 'required',
		'country' => 'required|numeric',
		'phone' => 'required|max:20'
		*/
		//add passwords to validation rule if submitted
		if($request->has('password')){
			$rules2 = [
				'old_password' => 'required',
				'password' => 'required|min:3|confirmed',
				'password_confirmation' => 'required|min:3'
			];
			$rules = array_merge($rules, $rules2);
		}

		//validate input data
		$validator = Validator::make($input, $rules);
		//if validation fails
		if($validator->fails()){
			//get error messages
			$messages = $validator->messages()->all();
			//return with error message
			return Redirect::to($redirectURL)->withInput($request->except('password','password_confirmation','_token'))->withErrors($messages);
			exit;
		}
		else{

			$user = User::find($userid);

			if($request->has('password')){
				if( !Hash::check($input['old_password'], $user->password) ){
					$messages[] = "Incorrect Old password.";
					return Redirect::to($redirectURL)->withInput($request->except('old_password','password','password_confirmation','_token'))->withErrors($messages);
					exit;
				}
			}
			
			foreach($user_fields as $field){
				$user->{$field} = $input[$field] ;
			};
			
			//update password if submitted
			if($request->has('password')){
				//encrypt password
				$password = Hash::make($request->password);
				$user->password = $password;
			}
		
			$action = 'encrypt';
		
			//Encrypt Transit Number if submitted	
			if($request->has('transit_no')){
				$user->transit_no = app("App\Http\Controllers\UtilsController")->encrypt_decrypt($action,$request->transit_no);
			}
			
			//Encrypt Account Number if submitted
			if($request->has('account_no')){			
				$user->account_no = app("App\Http\Controllers\UtilsController")->encrypt_decrypt($action,$request->account_no);
			}
			
			//update records
			$user->update();
			
			
			/**************************** Set Landowner's Settings *****************************/
			
			$Lo_Settings = new LandownersSettings();
			
			$Lo_Settings_id = $Lo_Settings->settings_exists($userid);
			
			if( $Lo_Settings_id == 0){
				// Insertion
				$Lo_Settings->user_id = $userid;
				
			}else{
				// Updation
				$Lo_Settings = LandownersSettings::find($Lo_Settings_id);
				
			}
			
			$variable_rates 	= ( (isset($input['variable_rates']) && $input['variable_rates'] == "on")?1:0 );
			$email_feature 		= ( (isset($input['email_feature']) && $input['email_feature'] == "on")?1:0 );
			$sms_feature 		= ( (isset($input['sms_feature']) && $input['sms_feature'] == "on")?1:0 );
			$recipient_email 	= ( (isset($input['recipient_email']) && !empty($input['recipient_email']))?$input['recipient_email']:'' );
			$recipient_mobile 	= ( (isset($input['recipient_mobile']) && !empty($input['recipient_mobile']))?$input['recipient_mobile']:'' );
			
			$Lo_Settings->variable_rates 	= $variable_rates;
			$Lo_Settings->email_feature 	= $email_feature;
			$Lo_Settings->sms_feature 		= $sms_feature ;
			$Lo_Settings->recipient_email 	= $recipient_email;
			$Lo_Settings->recipient_mobile 	= $recipient_mobile;
			
			$Lo_Settings->save();
		
			//redirect with success message
		
			return Redirect::to($redirectURL)->with('success', 'Account details updated.');
		}
	}


	
	public function third_party(Request $request){
		
	}
	public function newlot(Request $request){
		$input = $request->all();
		$userid = Auth::user()->id;

		$lot = new Lot;
		if (!$lot->validate($input)){
			$messages = $lot->errors();
			return Redirect::to('/home')->withInput($input)->withErrors($messages);
			exit;
		}
		else{
			$lot->lot_name = $input["lot_name"];
			$lot->lot_address = $input["lot_address"];
			$lot->user_id = $userid;
			$lot->price = $input["price"];
			
			if( Input::has('city') ){
				$lot->lot_city = $input["city"];
			}
			
			$lot->save();
			
			//create free meter if not assigned so far
			if( !Auth::user()->free_meter_assigned ){
				$meter_count = Meter::where("user_id",$userid)->count();
				if( !$meter_count ){
					$data = array(
						"lot_id"	=>	$lot->id,
						"price"	=>	$input["price"]
					);
					$this->newmeter( $request, 1, $data );	
					$msg = "";//"Congrats! You have been awarded your first free meter.";
					
					if( Input::has('city') ){
						$user = User::find($userid);
						$user->city = $input["city"];
						$user->country = $input["country"];
						$user->state = $input["state"];
						$user->update();
					}
					return Redirect::to('/home');
				
				}
			}
			else{
				$msg	= 'Group created.';
			}
			
			//redirect with success message
			return Redirect::to('/home')->with('success', $msg);
		}
	}


	public function updateLot(Request $request){
		$input = $request->all();
		$userid = Auth::user()->id;

		$lot = new Lot;
		if (!$lot->validate($input)){
			$messages = $lot->errors();
			return Redirect::to('/home')->withInput($input)->withErrors($messages);
			exit;
		}
		else{
			$lot = Lot::find($input["lot_id"]);
			$lot->lot_name = $input["lot_name"];
			$lot->lot_address = $input["lot_address"];
			$lot->price = $input["price"];
			//$lot->lot_city = $input["lot_city"];
			$lot->update();
			
			//redirect with success message
			return Redirect::to('/home')->with('success', "Group '".$input["lot_name"]."' updated.");
		}
	}

	public function newmeter_signup(Request $request){
			$input = $request->all();
			$meter_qty = $input["parking_meter_count"];
			$base_price = config("meter_base_price");
			
			if(!isset(Auth::user()->id) || (isset(Auth::user()->id) &&  Auth::user()->role_id == 2 ))
			{
				/* Register user only if guest user is purchasing meters OR Sales Associate is logged in and creating client account */
				$user_info = HomeController::register($request);
				
				if(!isset($user_info['email'])){
					return Redirect::to("/")->withErrors($user_info);
				}
				$password = $user_info["password"]; 
				$user_name = $user_info["name"]; 
				$user_email = $user_info['email'];
				$user_detail = User::where("email",$user_info['email'])->get();
				
				$userid = $user_detail[0]["id"];
			}else{
				
				/* If landowner is purchasing meters */
				$userid = Auth::user()->id;
			}
			/* Automatically add promo code if sale associate is creating client's accounts */
			
			if( isset(Auth::user()->id) &&  Auth::user()->role_id == 2 ){
				$sa_detail = User::find(Auth::user()->id);
				$promo_code = $sa_detail->promo_code;
				if(!empty($promo_code)){
					$input["promo_code"] = $promo_code;
				}
				
			    //$arguments = array( 'changed_by'=>'promo_code' , 'meter_count'=>$meter_qty );
				//$total_amount_paid = HomeController::update_order($arguments);
				
			}
			
			//if(!isset(Auth::user()->id))
			//{
				//echo "I am not authorized";
				/* Check if the user enter promo code*/
				
				if( Input::has("promo_code") ){
					//echo "Promo code exists -- ".$input["promo_code"];
					
					$user = User::where("promo_code",trim($input["promo_code"]))->get(); 
					
					/*echo "you entered my promo code ";
					echo "<pre>"; print_r($user); echo "</pre>";*/
					
					if( count( $user ) ){
						$referred_by = $user[0]->id;
						
						$thisUser = User::find( $userid );
						$thisUser->referred_by = $referred_by;
						$thisUser->update();
		
						//insert referral entry
						$check = Referral::where([ "user_id"=>$userid, "referred_by"=>$referred_by ])->count();
						//echo $check."<br>";
						if( $check <= 0 ){
							if($user[0]->commission != 0.00){
								$commssion = $user[0]->commission;
							}else{
								$commssion = config('commission');
							}
							//echo "commssion : ".$commssion."<br>";
							Referral::create([
								'user_id' => $userid,
								'referred_by' => $referred_by,
								'commission' => $commssion,
								'referral_medium'	=> 'PROMO CODE'
							]);
						}
						//exit();
					}
					else{
						Session::put('offer_promo_code', 1);
						Session::save();
						return Redirect::to("home")->with('promo_error','Promo Code not valid.');
						exit;
					}
				}
			//}
			
			if(!isset(Auth::user()->id))
			{
			
				/* Create a Default group for the registered user */
				$lot = new Lot;
				$lot->lot_name = "Group 1";
				$lot->user_id = $userid;
				$lot->price = $input["price"];
				if( Input::has('city') ){
					$lot->lot_city = $input["city"];
				}
				$lot->save();
			
			}
			
			$input["user_id"] = $userid;
			
			$lot_detail = Lot::where("user_id",$userid)->get();
			//echo "<pre>"; print_r($lot_detail); exit();
			if(isset($lot_detail) && count($lot_detail) > 0){
				$lot = Lot::find($lot_detail[0]["id"]);
				$input["lot_id"] = $lot_detail[0]["id"]; 
			}else{
				
				/* Create a Default group if doesn't exists for the user */
				$lot = new Lot;
				$lot->lot_name = "Group 1";
				$lot->user_id = $userid;
				$lot->price = $input["price"];
				if( Input::has('city') ){
					$lot->lot_city = $input["city"];
				}
				$lot->save();
				
				$input["lot_id"] = $lot->id; 
			}
			
			/* if Quantity of meter is less than 1 then set it to 1 */
			$meter_qty = ($meter_qty > 0 && is_numeric($meter_qty))?$meter_qty:1;
			$current_date = app("App\Http\Controllers\UtilsController")->formatDate(date("Y-m-d H:i:s"));
			
			/**/
			$meter = new Meter;
			$meter_discount = 0;
			
			//mail('gurpreet.webtek@gmail.com','session',Session::get('amount')."   ".Session::get('meter_discount'));
			
			$total_amount = Session::has('amount') ? Session::get('amount'):"";
			$sub_amount = Session::has('sub_amount') ? Session::get('sub_amount'):"";
			$meter_discount = Session::has('meter_discount') ? Session::get('meter_discount'):0;
			
			if( !$request->has("cc") ) $messages[] = "CC number is not valid.";
			if( !$request->has("expiry_month") ) $messages[] = "Expiry Month is not valid.";
			if( !$request->has("expiry_year") ) $messages[] = "Expiry Year is not valid.";
			if( isset($messages) && count($messages)) return Redirect::to('/home')->withInput($input)->withErrors($messages);
			
			$exp = ( (strlen($input["expiry_year"]) == 2)?$input["expiry_year"]:substr($input["expiry_year"],-2) ).( (strlen($input["expiry_month"]) == 1) ? "0".$input["expiry_month"] : $input["expiry_month"] )  ;
					
			//$total_amount = number_format(29.99*$input["parking_meter_count"],2) ; // Sign charges 29.99 
			//$total_amount = $total_amount + 15; // shipping charges - 15.00
			$desc = "Created meters:".$input["parking_meter_count"]. " by userID:".$userid;
			$redirect_url = '/';
			
			if(!isset(Auth::user()->id)){
				$return_url = URL::to("/unauth_completeNewMeterPayment");//completeNewMeterPayment
			}else{
				$return_url = URL::to("/home/unauth_completeNewMeterPayment");//completeNewMeterPayment
			}
			
			///pay with Monaris
			
			if(isset($password) && !empty($password)){
				$input["password"] = $password;
			}
			if($total_amount == "" && $meter_discount == 0){
				$total_amount = $input["payable_amount"]; 
			}
			$options = array( 
				"cc_number" 	=> 	$input["cc"], 
				"expiry" 		=> 	$exp, 
				"amount" 		=> 	$total_amount, 
				"desc" 			=> 	$desc, 
				"pay_type" 		=> 	"sign_buy", 
				"input"			=> 	$input,
				"base_price"	=>	$base_price,
				"cust_id"		=>	$userid,
				"order_id"		=>	'ord-'.date("dmy-G:i:s"),
				"redirect_url"	=>	$redirect_url,
				"return_url"	=>	$return_url,
				"meter_discount"=>	$meter_discount
			);
				//echo "<pre>"; print_r($options); echo "</pre>";
				///return Redirect::to("/")->with('register_order_success', $msg);
				return $this->processMonaris($options);
				
				 //return $this->completeNewMeterPayment( $request, 1, $input );
				//print_r($_SESSION['response']); exit();
				/*if(!empty($_SESSION['response']["TransID"])){
					return $this->completeNewMeterPayment( $request, 1, $input );
				}else{
					return false;
				}*/
				
			   //exit();
			
		
		
		
	}
	
	/*Update Order Summary in step 4 of Landowner UI on change of Meter Count*/
	public function update_order(Request $request){
		$input = $request->all();
		$changed_by = $input["changed_by"];
		$base_price = config("meter_base_price");
		$ship_price_lo = config("ship_price_lo");
		if($changed_by == "meter_count"){
			
			$total_amount["sub_total"] = $base_price*$input["meter_count"] ;
			//$total_amount["amount"] = $total_amount["sub_total"] + 15;
			
			$total_amount["amount"] = $total_amount["sub_total"] + $ship_price_lo; // $15.00 Shipping charges
			$total_amount["sub_total"] = number_format($total_amount["sub_total"],2);
			$total_amount["amount"] = number_format($total_amount["amount"],2);
			Session::put('amount', $total_amount["amount"]);
			Session::put('sub_amount', $total_amount["sub_total"]);
			Session::put('meter_count', $input["meter_count"]);
			Session::save();
			return $total_amount;
		}elseif($changed_by == "promo_code"){
			if( config("promo_code_discount") > 0 ){
				/*$meter_discount = (29.99 * config("promo_code_discount"))
				$per_sign_price = 29.99-$meter_discount;*/
				$per_sign_price = $base_price;
				$total_amount["sub_total"] = $per_sign_price *$input["meter_count"] ;
				
				
				
				
				$discount = ($total_amount["sub_total"] * config("promo_code_discount") )/100;
				$total_amount["amount"]= $total_amount["sub_total"] - $discount;

				$total_amount["amount"] = $total_amount["amount"] + $ship_price_lo; // $15.00 Shipping charges
				
				$total_amount["sub_total"] = number_format($total_amount["sub_total"],2);
				$total_amount["amount"] = number_format($total_amount["amount"],2);
				$discount = number_format($discount,2);
				Session::put('amount', $total_amount["amount"]);
				Session::put('sub_amount', $total_amount["sub_total"]);
				Session::put('meter_count', $input["meter_count"]);
				Session::put('meter_discount', $discount);
				Session::save();
				return $total_amount;
			}
			
			
		}
	}
	public function newmeter(Request $request, $free_meter=0, $free_meter_arr=array()){
		$input = $request->all();
		//echo "<pre>"; print_r($input); echo "</pre>";
		//echo "free_meter ".$free_meter;
		//echo "<pre>"; print_r($free_meter_arr); echo "</pre>";
		if(isset(Auth::user()->id)){
		$userid = Auth::user()->id;
		//echo "USER id - ".$userid."<br>";
		}
		//$input["lot_id"], $input["meter_count"]
		$base_price = config("meter_base_price");
		//echo "base_price - ".$base_price."<br>";
		$meter_discount = 0;
		
		
		if( $free_meter ){
			//echo "-- Free Meter -- ";
			$input["lot_id"] = $free_meter_arr["lot_id"];
			$input["price"] = $free_meter_arr["price"];
			$input["meter_count"] = 1;
			$base_price = 0;
		}
		
		
		//
		if(isset(Auth::user()->referred_by)){
			if( Auth::user()->referred_by > 0 and !$free_meter ){
				//echo "I am reffereed by someone but it is not my first meter.";
				$getSA = Referral::where("user_id",$userid)->where("referral_medium","PROMO CODE")->get();
				if( $getSA->count() && config("promo_code_discount") > 0 ){
					$meter_discount = number_format(($base_price*config("promo_code_discount"))/100,2);
					$base_price = $base_price-$meter_discount;
				}
			}
		}
		if(! Lot::where(array('user_id'=>$userid, 'id'=>$input["lot_id"]))->exists() ){
			//echo "iN valid GRourp";
			$messages[] = "Invalid lot selected.";
			return Redirect::to('/home')->withInput($input)->withErrors($messages);
			exit;
		}
		
		$input["user_id"] = $userid;
		
//exit();
		$meter = new Meter;
		if (!$meter->validate($input)){
			$messages = $meter->errors();
			return Redirect::to('/home')->withInput($input)->withErrors($messages);
			exit;
		}
		else{
			$input["meter_count"] = ($input["meter_count"] > 0 && is_numeric($input["meter_count"]))?$input["meter_count"]:1;
			$current_date = app("App\Http\Controllers\UtilsController")->formatDate(date("Y-m-d H:i:s"));
			
			$lot = Lot::find($input["lot_id"]);
			
			if( !$free_meter ){
			
				if( !$request->has("cc_number") ) $messages[] = "CC number is not valid.";
				if( !$request->has("expiry_month") ) $messages[] = "Expiry Month is not valid.";
				if( !$request->has("expiry_year") ) $messages[] = "Expiry Year is not valid.";
				if( isset($messages) && count($messages)) return Redirect::to('/home')->withInput($input)->withErrors($messages);
			
				$exp = ( (strlen($input["expiry_year"]) == 2)?$input["expiry_year"]:substr($input["expiry_year"],-2) ).( (strlen($input["expiry_month"]) == 1) ? "0".$input["expiry_month"] : $input["expiry_month"] )  ;
					
				$total_amount = number_format($base_price*$input["meter_count"],2) ;
				$desc = "Created meters:".$input["meter_count"]. " by userID:".$userid;
			
				$redirect_url = '/home';
				$return_url = URL::to("/complete-newmeter-payment");
			
				///pay with Monaris
				$options = array( 
					"cc_number" 	=> 	$input["cc_number"], 
					"expiry" 		=> 	$exp, 
					"amount" 		=> 	$total_amount, 
					"desc" 			=> 	$desc, 
					"pay_type" 		=> 	"meter_buy", 
					"input"			=> 	$input,
					"base_price"	=>	$base_price,
					"cust_id"		=>	$userid,
					"order_id"		=>	'ord-'.date("dmy-G:i:s"),
					"redirect_url"	=>	$redirect_url,
					"return_url"	=>	$return_url,
					"meter_discount"=>	$meter_discount
				);
				//print_r($options); $rwsult = $this->processMonaris($options);
				//print_r($rwsult); exit();
				return $this->processMonaris($options);
			
			}else{
				
				return $this->completeNewMeterPayment( $request, 1, $input );
				
			}
		
		}	
	}
	
	public function unauth_completeNewMeterPayment(Request $request, $free_meter=0, $free_meter_arr=array()){
		try{	
			
		session_start();
		$total_amount = Session::has('amount') ? Session::get('amount'):"";
		$sub_amount = Session::has('sub_amount') ? Session::get('sub_amount'):"";
		$m_count = Session::has('meter_count') ? Session::get('meter_count'):"";
		
		
		$settings = Settings::orderBy("id","desc")->first();
		
		$input = $request->all();
		
		if( !$free_meter ){
			
			if( !isset($_SESSION['response']) || !isset($_SESSION["all_options"]) ) exit("Sorry, We're experiencing technical difficulties. Please try again later.");
			
			if( Input::has("transaction") ){
				
				$response = $_SESSION['response'];
				$all_options = $_SESSION["all_options"];
	
				unset($_SESSION["request"]);
				unset($_SESSION["all_options"]);
			}
		}
		else{
			
			$response = array(
				"Message"		=>	"approved",
				"TransID"		=>	"",
				"Description"	=>	"First Free Meter"
			);
			$all_options = array(
				"input"			=>	array(
										"meter_count"	=>	1,
										"lot_id"		=>	$free_meter_arr["lot_id"]
									),
				"amount"		=>	0,
				"base_price"	=>	0,
				"cust_id"		=>  Auth::user()->id,
				"meter_discount"	=>	0,
				"pay_type"		=>	"meter_buy"
			);
		}

		$vals = array();
		$redirect_url = isset($all_options["redirect_url"])?$all_options["redirect_url"]:"/home";
		
		$user_detail = User::where("id",$all_options['cust_id'])->get();
			
		$user_email = $user_detail[0]["email"];
		if(isset($all_options["input"]["password"])){
			$password = $all_options["input"]["password"];
		}else{
			$password = "";
		}
		$user_name = $user_detail[0]["name"];
		
		/*echo "<pre>"; print_r($all_options["input"]); echo "</pre>";
		echo "<pre>"; print_r($user_detail[0]); echo "</pre>";
		echo "<pre>"; print_r($response); echo "</pre>"; 
		exit();*/
		
		if( isset($response["Message"]) && ( strtolower($response["Message"]) ==  "approved" || starts_with(strtolower($response["Message"]),"approved") )){
				
			$input = $all_options["input"];
			
			$total_amount = $all_options["amount"];
			$base_price	= $all_options["base_price"];
				
			$userid = $all_options["cust_id"];
	
			$trans_id = $response["TransID"];
			$trans_status = "approved";
			$trans_response = serialize($response);
			$meter_IDs = array();
			
			/* 1 represents purchasing and 0 represents replacement */
			if(!isset($all_options["input"]["order_type"]) || ( isset($all_options["input"]["order_type"]) && $all_options["input"]["order_type"] == 1 ) ){
				$msg = "purchase success"; 
				for( $i=0; $i<$input["parking_meter_count"]; $i++ ){
					$meter = new Meter;
					$meterID = Meter::max('meter_id');
					$meterID = ($meterID > 0 && strlen($meterID) == 6)?$meterID+1:100001;
					$meter->meter_id = $meterID;
					$meter_IDs[] = $meter->meter_id;
					$meter->lot_id = $input["lot_id"];
					$meter->user_id = $userid;
					$meter->base_price = $base_price;
					$meter->hour_price = $input["price"];
					$meter->discount = $all_options["meter_discount"];
					$meter->save();
					$vals[$i] = array('meter_id'=>$meter->id,'hour_price'=>$meter->hour_price);
				}
			}else{
				$meter_prices = array();
				$msg = "replace success";
				$meter_IDs = $all_options["input"]["meter_id"];
				$count_meter = 0;
				foreach($meter_IDs as $key => $meter_id){
					$meter_info = Meter::where('meter_id',$meter_id)->get();
					$meter_prices[$meter_id] = $meter_info[0]["hour_price"];
					$vals[$count_meter] = array('meter_id'=>$meter_id);
					$count_meter++;
				}
			}
				
			$meter_ids = implode(',',$meter_IDs);
			$data = array();
			$data["meter_id"]=$meter_ids;
			$data["lot_id"]=$input["lot_id"];
			/* if case works while replacing signs*/
			if(isset($meter_prices) && count($meter_prices)>0){
				$data["price"][] = $meter_prices;	
			}elseif($input["price"]){
				$data["price"] = $input["price"];
			}
			
			//echo "<pre>"; print_r($all_options["input"]); echo "</pre>";
			
			if(isset($input["towing_companies"]))
			$towing_detail = towing_companies::where('id',$input["towing_companies"])->get();
				
				
			if(isset($all_options["input"]["towing_contact"]))
			{
				$data["towing_contact"] = $all_options["input"]["towing_contact"];
			}elseif(isset($towing_detail[0]["conatct_no"])){
				$data["towing_contact"] = $towing_detail[0]["conatct_no"];
			}else{
				$data["towing_contact"] = "";
			}
			//echo "<pre>"; print_r($request); echo "</pre>";
			//echo "<pre>"; print_r($data); echo "</pre>";
			$gh = HomeController::generateSignage($request,$data);
			//echo "gh: ".$gh; exit();
			
			
			
			//create payment
			$pay = new Payment;
			$pay->user_id = $userid;
			$pay->lot_id = $input["lot_id"];
			$pay->amount = $total_amount;
			$pay->trans_id = $trans_id;
			$pay->trans_status = $trans_status;
			$pay->trans_response = $trans_response;
			$pay->payment_type = $all_options["pay_type"];
			
			$pay->shipping_address = $input["address"];
			$pay->shipping_country = $input["country_list"];
			$pay->shipping_province = $input["state"];
			$pay->shipping_postal = $input["postal_code"];
			$pay->shipping_city = $input["city"];
			$pay->shipped_meters = $meter_ids;
			
			(isset($input["towing_companies"])?$pay->towing_company = $towing_detail[0]["company"]:$pay->towing_company = "");
			
			$pay->towing_contact_no = $data["towing_contact"];
			$pay->save();
			$payment_id = $pay->id;
			
			//create payment items
			for( $i=0; $i<$input["parking_meter_count"]; $i++ ){
				$pay_item = new PaymentItem;
				$pay_item->payment_id = $payment_id;
				$pay_item->user_id = $userid;
				$pay_item->lot_id = $input["lot_id"];
				$pay_item->meter_id = $vals[$i]['meter_id'];
				$pay_item->base_price = $base_price;
				$pay_item->hour_price = $vals[$i]['hour_price'];
				$pay_item->total_price = $total_amount;
				$pay_item->save();
				
			}
			
			//set commissions if this meter owner is a referral of other user

			$getReferral = Referral::where("user_id",$userid)->get();
			
			//echo "get refeeral where userid = 42 (gurp)<br>";
			
			//echo "<pre>"; print_r($getReferral); echo "</pre>"; 

			
			if( $getReferral->count() ){

				$getReferral = $getReferral[0];

				$getSAReferral = Referral::where("user_id",$getReferral->referred_by)->get();                         // Commission for Sale Associate from referral
				
				//echo "<pre>"; print_r($getSAReferral); echo "</pre>";
				//$manager_commission = config('sm_commission'); //percent

				
				$getUserCommission = User::where("id",$getReferral->referred_by)->get();							  // Commission for Sale Associate from users
				//echo "<pre>"; print_r($getUserCommission); echo "</pre>";
				
				/*Set commission for user if exsits in user table*/
				if($getUserCommission[0]->commission != 0.00)
				{
					$sa_commision = $getUserCommission[0]->commission;   /*user table commission*/
				}
				else
				{
					$sa_commision = $settings["sa_commission"];          /* Global commission set by admin for Sale Associates*/
				}
				//echo  "Commision of SA (".$getReferral->referred_by.") -- ".$sa_commision."<br>";
				
				$amount = $total_amount;
				//echo "Amount : ".$amount;
				$comm = number_format(($amount * $sa_commision)/100,2);
				//echo  "Commision Amount of SA (".$getReferral->referred_by.") -- ".$comm."<br>";

				$if_manager = User::find($getReferral->referred_by);
				
				//echo "If Manager <br>";			
				//echo "<pre>"; print_r($if_manager); echo "</pre>"; 
				
				//create referral commission entry

				$ref_comm = new ReferralCommission;

				$ref_comm->referral_id = $getReferral->id;
				$ref_comm->payment_id = $payment_id;
				
				if($getUserCommission[0]->commission != 0.00)
				{
					$ref_comm->commission = $getUserCommission[0]->commission;   /*user table commission*/
				}
				else
				{
					$ref_comm->commission = $settings["sa_commission"];
				}
				
				
				$ref_comm->commission_amount = $comm;

				//if referral manager
				//echo "GetSAReferral Count".$getSAReferral->count();
				
				
				if( $getSAReferral->count() > 0 ){
					foreach($getSAReferral as $key=>$val){
						
						$getManagerCommission = User::where("id",$getSAReferral[$key]->referred_by)->get(); 
						if($getManagerCommission[0]->commission != 0.00)
						{
							$manager_commission = $getManagerCommission[0]->commission;
						}
						else
						{
					 		$manager_commission = $settings["sm_commission"];
						}
						$manager_comm = number_format((($amount * $manager_commission)/100) ,2);
						$ref_comm->manager_commission = $manager_commission;
						$ref_comm->manager_commission_amount = $manager_comm;
					}
				}
				
				
				//echo "Refe Comm -- <br>";
				//echo "<pre>"; print_r($ref_comm); echo "</pre>"; 
				$ref_comm->save();


				
				

				//update referred by user balance with commission

				$referred_by = User::find($getReferral->referred_by);
				//echo "Pre balance of : ".$getReferral->referred_by." : ".$referred_by->balance;
				$referred_by->balance = $referred_by->balance + $comm;
				//echo "After balance of : ".$getReferral->referred_by." : ".$referred_by->balance;
				$referred_by->update();
				//echo "REfered BY -- <br>";
				//echo "<pre>"; print_r($referred_by); echo "</pre>"; 
				
				if( $if_manager->created_by > 0 ){
					$manager = User::find($if_manager->created_by);
					//echo "Pre balance of manager  : ".$if_manager->created_by." : ".$manager->balance;
					$manager->balance = $manager->balance + $manager_comm;
					//echo "Pre balance of manager  : ".$if_manager->created_by." : ".$manager->balance;
					//print_r($manager);
					$manager->update();
				}



			}
			
			
			
			$order_summary = "<p style='font-size:12px;'> Quantity - ".$m_count." </p>
							  <p style='font-size:12px;'> Sub Total - $".$sub_amount."</p>
							  <p style='font-size:12px;'> Shipping Charges - $".$settings["ship_price_lo"]."</p>
							  <p style='font-size:12px;'> Total Charges - $".$total_amount."</p>
			";

			$get_content = PageContent::where( "page_name", "automated_emails" )->get();
			$get_content = json_decode($get_content[0]->page_content,true);
			
			
			/* Send a mail to the landowner*/
			
			if( !isset($all_options["input"]["order_type"]) ){
			
				/* Send login details to the landowner with order details if sale associate is creating client account */
				$subject = isset($get_content['registration_order']['subject']) ? $get_content['registration_order']['subject']  : "Your account has been created!";
				$body = isset($get_content['registration_order']['body']) ? $get_content['registration_order']['body']  : "";
				
			}else{
			
				/* 1 represents purchasing and 0 represents replacement */
				if( isset($all_options["input"]["order_type"]) && $all_options["input"]["order_type"]  == 1 ){
					$subject = isset($get_content['order_placement']['subject']) ? $get_content['order_placement']['subject']  : "Your account has been created!";
					$body = isset($get_content['order_placement']['body']) ? $get_content['order_placement']['body']  : "";
				}else{
					$subject = isset($get_content['order_replacement']['subject']) ? $get_content['order_replacement']['subject']  : "Your account has been created!";
					$body = isset($get_content['order_replacement']['body']) ? $get_content['order_replacement']['body']  : "";
				}
			
			}
			
			if( !empty($body) ){
				$order_summary_cust = "<p style='font-size:12px;'> Order ID - ".$payment_id." </p>".$order_summary;
				$body = str_ireplace("[[user_email]]",$user_email,$body);
				$body = str_ireplace("[[user_password]]",$password,$body);
				$body = str_ireplace("[[order_summary]]",$order_summary_cust,$body);
				$body = str_ireplace("[[click_here_link]]","<a href='" .URL::to('/account/login'). "' target='_blank'>Click here</a>",$body);
				$body = str_ireplace("[[user_name]]",$user_name,$body);
			}
				
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "X-Mailer: PHP/" . phpversion();
			$headers .= "Content-Transfer-Encoding: 8bit\r\n";
			$headers .= 'From: My-meter.com <info@my-meter.com>' . "\r\n";
			
			$to = $user_email;
			
			//echo $gh;
			
			if(mail( $to,$subject,$body,$headers)){ //'shaveta.webtech@gmail.com'
			
				//echo "Mail sent";
				$order_summary = "<p style='font-size:12px;'> Meter ID's - ".$meter_ids."</p>".$order_summary;
				$admin_mail_body = "<h3>Shipping Information</h3>
								<hr>
								<p style='font-size:12px;'> First Name - ".$user_detail[0]["name"]." </p>
								<p style='font-size:12px;'> Last Name - ".$user_detail[0]["last_name"]." </p>
								<p style='font-size:12px;'> Email - ".$user_detail[0]["email"]."</p>
								<p style='font-size:12px;'> Address - ".$input["address"]."</p>
								<p style='font-size:12px;'> City - ".ucfirst($input["city"])."</p>
								<p style='font-size:12px;'> Province - ".$input["state"]."</p>
								<p style='font-size:12px;'> Postal Code - ".$input["postal_code"]."</p>
								<h3>Order Summary</h3>
								<hr>"
								.$order_summary;
								
								
				if($gh == 1){
						$mail_data = ['lot_id' => $data["lot_id"] , 'pay_id' => $payment_id , 'mail_content' => $admin_mail_body];

						Mail::send([], ['mail_data'=>$mail_data], function ($message) use ($mail_data) {
							$message->to("gurpreet.webtek@gmail.com", ""); //  'shaveta.webtech@gmail.com' // matt@cibinel.com gurpreet.webtek@gmail.com matt@cibinel.com 
							$message->subject('My-Meter - Order # '.$mail_data["pay_id"]); 
							$message->setBody($mail_data["mail_content"], 'text/html');
							$message->attach("https://my-meter.com/dev/public/My-Meter Signage".$mail_data["lot_id"].".zip",array('as' => 'My-Meter Signage.zip','mime' => 'application/octet-stream'));
							
					
						}); 
				}
				$public_path = "/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public";  
				$folder_name = '/My-Meter Signage'.$mail_data["lot_id"];

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
					$zipped_dir = $dir_path.".zip";
					
					if(file_exists($zipped_dir))
					{ 
						unlink($zipped_dir);
					}
					if(!rmdir($dir_path))
					{
					  	echo ("Could not remove $path");
						exit();
					}
				 }
				 
				// $msg = $input["parking_meter_count"]." Meter". ($m_count>1?'s':'') ." created.";
				 
				//mail('trannum.webtech@gmail.com','My-Meter - Order # '.$payment_id,$admin_mail_body,$headers); // matt@cibinel.com
			}
			
			//exit();
			
			/*if( !$free_meter ){
				$msg = $input["parking_meter_count"]." Meter". ($input["parking_meter_count"]>1?'s':'') ." created.";
			}else{

				$user = User::find($userid);
				$user->free_meter_assigned = 1;
				$user->update();
				
				return true;

			}
			*/
			
			//////////////////////////////// Mail to custimer and admin //////////////////////////////////////////
			
			
			
			
			//////////////////////////////////////////////////////////////////////////
			
			/* Set the amount in session For facebook pixel conversion for "Purchase" event */ 
			if(isset(Auth::user()->id) &&  Auth::user()->role_id == 2 ){
				/* if sa is purchasing meters for clients */
				return Redirect::to("/sa-home")->with('register_order_success', $msg)->with('amount',$total_amount);
			}else{
				return Redirect::to("/home")->with('register_order_success', $msg)->with('amount',$total_amount);
			}
			
			
			
			//return Redirect::to("/home")->with('success', "Meter created");
			//return Redirect::to($redirect_url)->with('success', $msg);
		
		}
		else{
			$messages[] = $response["Message"];
			return Redirect::to($redirect_url)->withInput($input)->withErrors($messages);
		}	
	
	
		}catch(Exception $e){
			echo  $e->getMessage();
		}
	}

	public function completeNewMeterPayment( Request $request, $free_meter=0, $free_meter_arr=array() ){
		
		session_start();
		$input = $request->all();
		//dd($input);
		/*if(isset($input["meter_count"])){
			$meter_count = $input["meter_count"];
		}elseif(isset($input["parking_meter_count"])){
			$meter_count = $input["parking_meter_count"];
		}*/
		if( !$free_meter ){

			if( !isset($_SESSION['response']) || !isset($_SESSION["all_options"]) ) exit("Sorry, We're experiencing technical difficulties. Please try again later.");

			if( Input::has("transaction") ){
				
				$response = $_SESSION['response'];
				$all_options = $_SESSION["all_options"];
	
				unset($_SESSION["request"]);
				unset($_SESSION["all_options"]);
			}
		}
		else{
			
			$response = array(
				"Message"		=>	"approved",
				"TransID"		=>	"",
				"Description"	=>	"First Free Meter"
			);
			$all_options = array(
				"input"			=>	array(
										"meter_count"	=>	1,
										"lot_id"		=>	$free_meter_arr["lot_id"]
									),
				"amount"		=>	0,
				"base_price"	=>	0,
				"cust_id"		=>  Auth::user()->id,
				"meter_discount"	=>	0,
				"pay_type"		=>	"meter_buy"
			);
		}

		$vals = array();
		$redirect_url = isset($all_options["redirect_url"])?$all_options["redirect_url"]:"/home";
		if( isset($response["Message"]) && ( strtolower($response["Message"]) ==  "approved" || starts_with(strtolower($response["Message"]),"approved") )){
				
			$input = $all_options["input"];
			$total_amount = $all_options["amount"];
			$base_price	= $all_options["base_price"];
				
			$userid = $all_options["cust_id"];
	
			$trans_id = $response["TransID"];
			$trans_status = "approved";
			$trans_response = serialize($response);
	
			for( $i=0; $i<$input["meter_count"]; $i++ ){
				$meter = new Meter;
				$meterID = Meter::max('meter_id');
				$meterID = ($meterID > 0 && strlen($meterID) == 6)?$meterID+1:100001;
				$meter->meter_id = $meterID;
				$meter->lot_id = $input["lot_id"];
				$meter->user_id = $userid;
				$meter->base_price = $base_price;
				//$meter->hour_price = $free_meter_arr["price"];
				$meter->discount = $all_options["meter_discount"];
				$meter->save();
				$vals[$i] = array('meter_id'=>$meter->id);
			}
				
			//create payment
			$pay = new Payment;
			$pay->user_id = $userid;
			$pay->lot_id = $input["lot_id"];
			$pay->amount = $total_amount;
			$pay->trans_id = $trans_id;
			$pay->trans_status = $trans_status;
			$pay->trans_response = $trans_response;
			$pay->payment_type = $all_options["pay_type"];
			$pay->save();
				
			$payment_id = $pay->id;
			
			//create payment items
			for( $i=0; $i<$input["meter_count"]; $i++ ){
				$pay_item = new PaymentItem;
				$pay_item->payment_id = $payment_id;
				$pay_item->user_id = $userid;
				$pay_item->lot_id = $input["lot_id"];
				$pay_item->meter_id = $vals[$i]['meter_id'];
				$pay_item->base_price = $base_price;
				$pay_item->total_price = $base_price;
				$pay_item->save();
				
			}
			
			
			
			if( !$free_meter ){
				$msg = $input["meter_count"]." Meter". ($input["meter_count"]>1?'s':'') ." created.";
			}else{

				$user = User::find($userid);
				$user->free_meter_assigned = 1;
				$user->update();
				
				return true;

			}
			return Redirect::to($redirect_url)->with('success', $msg);
		
		}
		else{
			$messages[] = $response["Message"];
			return Redirect::to($redirect_url)->withInput($input)->withErrors($messages);
		}	
	}


	public function getCustMeters(Request $request){
		//echo "test"; exit;
		$input = $request->all();
		$meter_id = $input['meter_id'];
		
		/* Group system has been removed temporarily */
		/************************************* Activating Group Feature Back 9 Nov 2017 **********************************/
		
		$m_id = Meter::join("users","users.id","=","meters.user_id")->where( [ 'meters.meter_id'=>$meter_id, "meters.status"=>1, "users.deleted"=>0 ])->select("meters.id","meters.lot_id","meters.expiry")->get();
		
		//$m_id = Meter::join("users","users.id","=","meters.user_id")->where( [ 'meters.meter_id'=>$meter_id, "meters.status"=>1, "users.deleted"=>0 ])->select("meters.id","meters.lot_id","meters.expiry",'meters.hour_price')->get();
		
		$message = ["error"=>"","data"=>"","price"=>0.00];
		
		if( $m_id->count() ){
			//check if meter not active 
			//$expiry = app('App\Http\Controllers\UtilsController')->date_difference(date('Y-m-d H:i:s'),$m_id[0]->expiry);
			//if meter expired
			//if( $expiry["hours"] == 0 && $expiry["mins"] == 0 ) {
			
			/* use meter price instead of group price temporarily as Group system have been removed yet 
			
				$price_per_hour = $m_id[0]->hour_price;
				
				
				$message["data"] = "$".$price_per_hour." per hour";
				$message["price"] = $price_per_hour;
			*/
				
				$this_lot = Lot::where( ['id'=>$m_id[0]->lot_id, "status"=>1 ] )->get();
				
				if( $this_lot->count() ){
					$price_per_hour = $this_lot[0]->price;
				
					$message["data"] = "$".$price_per_hour." per hour";
					$message["price"] = $price_per_hour;
				}else{
					$message["error"] = "#".$meter_id." Not available";
				}
			
			/*}
			else{
				$message["error"] = "#".$meter_id." Occupied";
			}*/
			
		}else{
			$message["error"] = "#".$meter_id." Not valid";
		}

		return $message;
		exit;
	}
	
	public function getMeters(Request $request){
		$input = $request->all();
		$userid = Auth::user()->id;
		$lotID = $input['lotID'];
		$mylots = Lot::where('user_id',$userid)->where( ["id"=>$lotID,"status"=>1])->get();
		$mymeters = Meter::where( [ 'user_id'=>$userid, 'lot_id'=>$lotID, "status"=>1 ])->get();

		$vars = array( );
		if( count($mylots) ){
			$vars["lot_id"] = $mylots[0]->id;
			$vars["lot_address"] = $mylots[0]->lot_address;
			$vars["lot_price"] = $mylots[0]->price;
			$vars["lot_start_time"] = app("App\Http\Controllers\UtilsController")->formatTime($mylots[0]->start_time,"h:i a");
			$vars["lot_end_time"] = app("App\Http\Controllers\UtilsController")->formatTime($mylots[0]->end_time,"h:i a");
		}else{
			$vars["lot_id"] = 0;
			$vars["lot_address"] = "";
			$vars["lot_price"] = "";
			$vars["lot_start_time"] = "";
			$vars["lot_end_time"] = "";
		}

		$output = View::make('landowner/meter-sub', ['mymeters' => $mymeters, 'vars'=>$vars ]);
		$contents = $output->render(); // or $contents = (string) $output;
		
		
		



		echo $contents;
		exit;
	}

	public function editGroupHTML(Request $request){
		$input = $request->all();
		$userid = Auth::user()->id;
		$lotID = $input['lotID'];
		$mylots = Lot::where('user_id',$userid)->where("id",$lotID)->get();

		$vars = array( );
		if( count($mylots) ){
			$vars["lot_id"] = $mylots[0]->id;
			$vars["lot_name"] = $mylots[0]->lot_name;
			$vars["lot_address"] = $mylots[0]->lot_address;
			$vars["lot_price"] = $mylots[0]->price;
			$vars["lot_city"] = $mylots[0]->lot_city;
		}else{
			$vars["lot_id"] = 0;
			$vars["lot_name"] =  "";
			$vars["lot_address"] = "";
			$vars["lot_price"] = "";
			$vars["lot_city"] = "";
		}

		$output = View::make('includes/group-edit-form', ['vars'=>$vars ]);
		$contents = $output->render(); // or $contents = (string) $output;

		echo $contents;
		exit;

	}
	
	public function getMeterOptons(Request $request){
		$input = $request->all();
		$options = '';
		if( $request->has("lotID") ){
			$userid = Auth::user()->id;	
			$mymeters = Meter::where( [ 'user_id'=>$userid, 'lot_id'=>$input["lotID"] ])->get();

			if( $mymeters->count() ){
				$options .= '<table class="table table-stripe table-responsive"><tr>
								<td colspan="4">
									<label>
										<input type="checkbox" name="meter_id[]" value="">
										All
									</label>
								</td>
							</tr>';
				
				for( $i=0; $i<count($mymeters); $i++ ){
					$options .= '<tr>';
					$meter = $mymeters[$i];
					$options .= '<td>
							<label>
								<input type="checkbox" name="meter_id[]" value="'.$meter->meter_id.'">
								'.$meter->meter_id.'
							</label>
						</td>';
					$i++;
					if( isset($mymeters[$i]) ){
						$meter = $mymeters[$i];
						$options .= '<td>
								<label>
									<input type="checkbox" name="meter_id[]" value="'.$meter->meter_id.'">
									'.$meter->meter_id.'
								</label>															
							</td>';
					}
					$i++;
					if( isset($mymeters[$i]) ){
						$meter = $mymeters[$i];
						$options .= '<td>
								<label>
									<input type="checkbox" name="meter_id[]" value="'.$meter->meter_id.'">
									'.$meter->meter_id.'
								</label>															
							</td>';
					}
					$i++;
					if( isset($mymeters[$i]) ){
						$meter = $mymeters[$i];
						$options .= '<td>
								<label>
									<input type="checkbox" name="meter_id[]" value="'.$meter->meter_id.'">
									'.$meter->meter_id.'
								</label>															
							</td>';
					}
					$options .= '</tr>';
				}
				$options .= '</table>';
			}else{
				$options .= '<div class="alert alert-warning margin-bottom-0">No meter found for selected group!</div>';
			}

		}
		else{
			$options .= '<div class="alert alert-warning margin-bottom-0">No meter found for selected group!</div>';
		}
		echo $options;
		exit;
	}
	
	public function deleteGroup(Request $request){
		$input = $request->all();
		$userid = Auth::user()->id;
		$lotID = $input['lotID'];
		$lots = Lot::where('user_id',$userid)->where("id",$lotID)->count();
		if( $lots > 0 ){
			$lot = Lot::find($lotID);
			$lot->status = 0;
			$lot->update();
			echo 'deleted';
		}
		
		exit;

	}
	/*******************************************************************************************************************************************************************/
					// Now Landowners are not allowed to delete meters once purchased. Admin can delete meters of landowners who are managed by admin //
	/*******************************************************************************************************************************************************************/
	
	public function deleteMeter(Request $request){
		$input = $request->all();
		$userid = $input["client_id"];
		$meter_id = $input['meter_id'];
		$meter_id = explode(",",$meter_id);
		foreach( $meter_id as $mid ){
			$meters = Meter::where('user_id',$userid)->where("id",$mid)->count();
			if( $meters > 0 ){
				$m = Meter::find($mid);
				$m->status = 0;
				$m->update();
			}
			echo 'deleted';
		}
		exit;

	}
	
	public function getMeterDetails(Request $request){
		$input = $request->all();
		$meter = Meter::join("lots","lots.id","=","meters.lot_id")->where('meter_id',$input['meter_id'])->select("meters.expiry","lots.price","lots.lot_address")->get();
		if( $meter->count() ){
			//check if meter not active 
			$expiry = app('App\Http\Controllers\UtilsController')->date_difference(date('Y-m-d H:i:s'),$meter[0]->expiry);
			//if meter expired
			//if( $expiry["hours"] == 0 && $expiry["mins"] == 0 ) {
				echo '<div class="alert alert-success">Stall #'. $input['meter_id'] .' available. <Br>
					Location: '. $meter[0]->lot_address .'<br>
					Price: $'.number_format($meter[0]->price).' /hr</div>';
			/*}
			else{
				echo '<div class="alert alert-danger">Stall #'. $input['meter_id'] .' is occupied.<br>
						This meter will expire in '. (($expiry["hours"] > 0)? $expiry["hours"].' Hrs and ':''). $expiry["mins"] .' Mins </div>';
			}*/
		}else{
			echo '<div class="alert alert-danger">Stall #'. $input['meter_id'] .' is not valid.</div>';
		}

		exit;
	}
	
	public function report(Request $request){
		$input = $request->all();

		$userid = Auth::user()->id;
		
		$mymeters = Meter::where(function ($obj) use ($userid,$input) {
			$obj->where("user_id", '=', $userid);
			if( Input::has('meter_id') ) $obj->where("meter_id", '=', $input['meter_id']);
			if( Input::has('lot_id') ) $obj->where("lot_id", '=', $input['lot_id']);
			if( Input::has('start_date') ) $obj->where("created_at", '>=', app("App\Http\Controllers\UtilsController")->formatDate($input['start_date']));
			if( Input::has('end_date') ) $obj->where("created_at", '<=', app("App\Http\Controllers\UtilsController")->formatDate($input['end_date']));
		})->get();
		
		$meter_ids = $mymeters->lists("id");

		$payments = Payment::join("payment_items","payments.id","=","payment_items.payment_id")
			->join("lots","lots.id","=","payments.lot_id")
			->where( "lots.user_id",$userid )
			->where( "payments.payment_type","meter_hire" )
			->wherein( "payment_items.meter_id", $meter_ids )
			->select( DB::raw("count(*) as transactions, sum(payment_items.hours) as hours, sum(payment_items.total_price) as total_revenue") )->get()->toArray();
		
		$payments = $payments[0];
	
		$vars = array();
		$vars['meters'] = $mymeters->count();
		$vars = array_merge($vars,$payments);

		$output = View::make('includes/report-sub', ['vars' => $vars ]);
		$contents = $output->render(); // or $contents = (string) $output;

		$chart_details = Payment::join("payment_items","payments.id","=","payment_items.payment_id")
			->join("lots","lots.id","=","payments.lot_id")
			->where( "lots.user_id",$userid )
			->where( "payments.payment_type","meter_hire" )
			->wherein( "payment_items.meter_id", $meter_ids )
			->select( DB::raw("sum(payment_items.total_price) as total_per_day, Year(payments.created_at) as Yr, Month(payments.created_at) as Mn, Day(payments.created_at) as Dy"))
			->groupBy( DB::raw("Month(payments.created_at),Day(payments.created_at)") )
			->orderBy( DB::raw("Month(payments.created_at),Day(payments.created_at)") )
			->get();
			
		$chart_data = array("label"=>[],"dataset"=>[]);
		
		foreach( $chart_details as $chart ){
			$chart_data["label"][] = $chart->Dy . "/" . $chart->Mn;
			$chart_data["dataset"][] = $chart->total_per_day;
		}

		return ["contents"=>$contents,"chart_data"=>$chart_data];
		exit;
		//return Redirect::to('/home/report')->withInput($input);
	}
	
	/*******************************************************************************************************************************************************************/
				// Grouping meters in a lot feature have been removed. Only a single lot is being used for grouping whole list of meters of a landowners  //
	/*******************************************************************************************************************************************************************/
	/*public function updateMeterGroup(Request $request){
		$input = $request->all();
		$userid = Auth::user()->id;

		$checkGroup = Lot::where([ 'user_id'=>$userid, 'id'=>$input["lot_id"] ])->count();
		if( $checkGroup ){
			Meter::where('user_id',$userid)->wherein('id', explode(",",$input["meter_id"]))->update(['lot_id' => $input["lot_id"]]);
			return Redirect::to('/home')->with('success', "Data saved.");
		}
		else{
			return Redirect::to('/home')->withErrors(["Invalid data found."]);
		}

	}*/
	
	
	
	public function pay(Request $request){
		$comm_percent = config('commission');
		$input = $request->all();
		//echo "<pre>"; print_r($input); echo"</pre>"; exit();
		if(!isset($input["sample"]))
		{

			$current_date = app("App\Http\Controllers\UtilsController")->formatDate(date("Y-m-d H:i:s"));
			$add_time = $request->has("add_more_time");
			
			$meter_id = $input["meter_id"];
			session_start();
			$_SESSION['meter_id'] = $meter_id; 
			$hours = $input["expiry_time"]; //ceil((strtotime($expiry) - strtotime($current_date))/3600);
			$minutes = $hours * 60; 

			$meter = Meter::join("lots","meters.lot_id","=","lots.id")->where("meter_id",$meter_id)->select("meters.hour_price","lots.price","meters.lot_id","meters.id","meters.user_id","meters.expiry")->get();

			mail('trannum.webtech@gmail.com','Details',json_encode($meter));
			
			if( !$meter->count() ) $messages[] = "Stall # is not valid.";
			if( $hours <= 0 ) $messages[] = "Time is not valid.";
			//if( !$request->has("fname") ) $messages[] = "First Name is not valid.";
			//if( !$request->has("lname") ) $messages[] = "Last Name is not valid.";
			if( !$request->has("cc_number") ) $messages[] = "CC number is not valid.";
			if( !$request->has("expiry_month") ) $messages[] = "Expiry Month is not valid.";
			if( !$request->has("expiry_year") ) $messages[] = "Expiry Year is not valid.";
			//if( !$request->has("cvv") ) $messages[] = "CVV Number is not valid.";
			
			$redirect_url = $add_time ? "add-more-time/".base64_encode($meter_id) : "/my-meter";
			if( isset($messages) && count($messages)) return Redirect::to($redirect_url)->withInput($input)->withErrors($messages);
			
			$meter = $meter[0];
			
			if($add_time)
				$add_time = $meter->expiry >= date("Y-m-d H:i:s");
				
			//if add more time request then, get meter details and add time to existing record
			if( $add_time ){
				$expiry_meter = date("Y-m-d H:i:s", (strtotime($meter->expiry) + ( $minutes * 60 ))) ;
				$desc = "Added time ".$minutes." minutes to meter #".$meter_id;
			}else{
				$expiry_meter = date("Y-m-d H:i:s", strtotime("+".$minutes." minutes")) ;
				$desc = "Parking meter #".$meter_id." for ".$minutes." minutes";
			}

			$exp = ( (strlen($input["expiry_year"]) == 2)?$input["expiry_year"]:substr($input["expiry_year"],-2) ) . ( (strlen($input["expiry_month"]) == 1) ? "0".$input["expiry_month"] : $input["expiry_month"] ) ;

			//$amount = number_format($hours * $meter->price,2);
			$amount = number_format($hours * $meter->hour_price,2);
			
			$userid  =999999999;
			
			$return_url = URL::to('/complete-payment');
			
			///pay with Monaris
			$options = array( 
				"cc_number" 	=> 	$input["cc_number"], 
				"expiry" 		=> 	$exp, 
				/*"cvv" 			=> 	$input["cvv"], */
				"amount" 		=> 	$amount, 
				"desc" 			=> 	$desc, 
				"pay_type" 		=> 	"meter_hire", 
				"input"			=> 	$input,
				"meter"			=>	$meter,
				"redirect_url"	=>	$redirect_url,
				"add_time"		=>	$add_time,
				"expiry_meter"	=>	$expiry_meter,
				"cust_id"		=>	$userid,
				"order_id"		=>	'ord-'.date("dmy-G:i:s"),
				"return_url"	=>	$return_url
			);
			//echo "<pre>"; print_r($options); exit;
			return $this->processMonaris($options);

		}
		else
		{
			$add_time = $request->has("add_more_time");
			
			$meter_id = $input["meter_id"];
			$hours = $input["expiry_time"]; //ceil((strtotime($expiry) - strtotime($current_date))/3600);
			
			//echo "Expiry Meter -- ".$expiry_meter;
			$msg =  "Meter Started.";

			//return Redirect::to('/sample-complete-payment');
			return Redirect::to('/my-meter')->with('success', $msg)->with('pay_item_id',100)->with('expiry_time',$hours*60*60)->with('meter_id',$meter_id);		

		}
		
		
	}
	
	
	public function get_expiry_time(Request $request){
		$input = $request->all();
		$meter_id = $input["meter_id"];
		$meter = Meter::where("meter_id",$meter_id)->get();
		$meter = $meter[0];
		
		$time_remaining = strtotime($meter->expiry) - strtotime(date("Y-m-d H:i:s"));
		echo $time_remaining;
		exit;
	}
	
	
	
	public function add_opt_in(Request $request){
		$input = $request->all();
		//echo "<pre>";print_r($input); echo "</pre>";
		$notification_time = 5; //schedule sms before 5 minutes of expiry
		if( !$request->has("pay_item_id") ) $messages[] = "Sorry, We're experiencing technical difficulties. Please try again later.";
		if( isset($messages) && count($messages)) return Redirect::to('/my-meter')->withInput($input)->withErrors($messages)->with("opt_in_err",true);
		
		$pay_item = PaymentItem::find($input['pay_item_id']);
//		echo "<pre> pay Item";print_r($pay_item); echo "</pre>";
		//->join("countries","countries.id","=","users.country")
		$meter = Meter::join("users","users.id","=","meters.user_id")
			
			->where("meters.id",$pay_item->meter_id)->select("meters.meter_id","meters.expiry","meters.user_id")->get(); // ,"countries.phonecode"
		//echo "<pre> METER Before";print_r($meter); echo "</pre>"; //exit();
		if(isset($meter[0])){
			$meter = $meter[0];
		}else{
			$meter = $meter;
		}

		//echo "<pre> METER After";print_r($meter); echo "</pre>"; exit();
		$expiry = $meter->expiry;
		$time_remaining = strtotime($expiry) - strtotime(date("Y-m-d H:i:s"));
		
		if( !$request->has("opted_phone_number") ) $messages[] = "Enter mobile number.";
		if( isset($messages) && count($messages)){
			return Redirect::to('/my-meter')->withInput($input)->withErrors($messages)->with('pay_item_id',$input['pay_item_id'])->with("opt_in_err",true)->with('expiry_time',$time_remaining)->with('meter_id',$meter->meter_id);
		}
		
		$pay_item->opted_phone_number = $input['opted_phone_number'];
		$pay_item->update();
		
		
//		Don't need phone code as we are having only canada country
		//$to = $meter->phonecode . $input['opted_phone_number'];
		$to = "1" . $input['opted_phone_number'];
		

		$url = app('App\Http\Controllers\UtilsController')->add_time_url($meter->meter_id);
		/*$sms_body = "Your parking meter will expire in ".$notification_time." minutes. Click ".$url." if you'd like to extend your booking.";*/
		
		$sms_body = "Your parking meter will expire in ".$notification_time." minutes. To extend your booking, please follow the link: ".$url;
		
		$send_at = date("Y-m-d H:i:s", ( strtotime($expiry)-($notification_time*60) )  );

		//put sms in queue
		$sms_q = new SmsQueue;
		$sms_q->to = $to;
		$sms_q->sms_body = $sms_body;
		$sms_q->send_at = $send_at;
		$sms_q->meter_id = $meter->meter_id;
		$sms_q->meter_owner = $meter->user_id;
		$sms_q->save();
		
		
			
		return Redirect::to('/my-meter')->with('success', "A notification will be sent to ".$to." .")->with('expiry_time',$time_remaining)->with('meter_id',$meter->meter_id);
	}
	
	private function processMonaris( $options=array()  ){
		 @ob_start();
  		@session_start();
		//print_r($_SESSION);
		$_SESSION["request"]	=	array(
			"cust_id"				=>		$options["cust_id"],
			"order_id"				=>		$options["order_id"],
			"amount"				=>		$options["amount"],
			"pan"					=>		$options["cc_number"],
			"expiry_date"			=>		$options["expiry"],
			"dynamic_descriptor"	=>		$options["desc"]
		);
		$_SESSION["redirect"] = $options["return_url"];
		$_SESSION["all_options"] = $options;
		return Redirect::to(URL::to("/").'/monaris/do_transaction.php');
	}
	
	

	private function processPaypal($options){
		$user = Auth::user();
		
		
		$ch = curl_init();
		//$clientId = "AThy2wbqE7F_MFnnr_XtLnG0M2SlVh43K82Wx7Ay-99Q-DWbSc6zkF7IQ2JqBDeGK1ZohFtUOsOdulgy";
		//$secret = "EGodUUn590eilua64aCS8iAwT_3ITBL8tfp_nQaQsBhMP25RjA18XclHZl4UcidSvKdbKEt2CAzPk69d";
		$clientId = "AXI4d07yJlJgVoN7xw50vSORx4qYyvQRk6B7NlGVCi5FV9wnSDslIQcQwx_gKymZrVRIKESgKwJRxeL9";
		$secret = "EAE5Y4o1n8yx29Z3sgCW8BhyfVXs_JbqTFI1nA26pHpEt6dfPWp1wAnM0jAH5Rp03PljCDPO2dABTgCe";
		
		curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		
		$result = curl_exec($ch);
		
		if(empty($result))die("Error: No response.");
		else
		{
			$json = json_decode($result);
		  //  print_r($json);
			$token = $json->access_token;
		}
		
		curl_close($ch);
		
		$paypal_header_options = array();
		$paypal_header_options[] = "Content-Type:application/json ";
		$paypal_header_options[] = "Authorization:Bearer ".$token;
		
		  $data = '{
			"intent": "sale",
			"payer": {
			  "payment_method": "credit_card",
			  "funding_instruments": [
				{
				  "credit_card": {
					"number": "'.$options['cc_number'].'",
					"type": "'.$options['cardType'].'",
					"expire_month": '.$options['expiry_month'].',
					"expire_year": '.$options['expiry_year'].',
					"cvv2": '.$options['cvv'].',
					"first_name": "'.$options['fname'].'",
					"last_name": "'.$options['lname'].'"
				  }
				}
			  ]
			},
			"transactions": [
			  {
				"amount": {
				  "total": "'.$options['amount'].'",
				  "currency": "USD"
				},
				"description": "'.$options['desc'].'"
			  }
			]
		  }';
		
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/payments/payment");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $paypal_header_options);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		$result = curl_exec($ch);
		//print_r($result);
		
		if(empty($result))die("Error: No response.");
		else
		{
			$json = json_decode($result);
			//echo $json->state;
			//print_r($json);
			
		}
		return $json;
	}
	
	
	public function parseNVP($nvpstr)
	{
	  $paypalResponse = array();
	  parse_str($nvpstr,$paypalResponse);
	  return $paypalResponse;
	}	
	
	
	public function accpetterms( Request $request ){
		$inputs = $request->all();
		$user = Auth::user();
		$redirect_to = ( $user->role_id == 3 ) ? '/home' : ( ( $user->role_id == 2 ) ? '/sa-home' : '/sm-home' );
		
		if( Input::has("is_agreed") ){
			$thisUser = User::find($user->id);
			$thisUser->is_agreed = 1;
			$thisUser->update();
			if( $user->role_id == 3 ){
				Session::put('offer_promo_code', 1);
				Session::save();
			}
		}
		return Redirect::to($redirect_to);
	}
	public function validate_promo_code_landowner_UI( Request $request ){
		$inputs = $request->all();
		if( Input::has("promo_code") ){
			$user = User::where("promo_code",$inputs["promo_code"])->get(); 
			if( count( $user ) ){
				$data["referred_by"] = $user[0]->id;
				$data["commission"] = config('commission');
				
			}else{
				$data["error"] = "Invalid Promo Code";
			}
			echo json_encode($data); exit();
		}
	}
	public function validate_promo_code( Request $request ){
		$inputs = $request->all();
		
		if( Input::has("promo_code") ){
			$user = User::where("promo_code",$inputs["promo_code"])->get(); 
			if( count( $user ) ){
				$referred_by = $user[0]->id;
				$thisUser = User::find( Auth::user()->id );
				$thisUser->referred_by = $referred_by;
				$thisUser->update();

				//insert referral entry
				$check = Referral::where([ "user_id"=>Auth::user()->id, "referred_by"=>$referred_by ])->count();
				if( $check <= 0 ){
					Referral::create([
						'user_id' => Auth::user()->id,
						'referred_by' => $referred_by,
						'commission' => config('commission'),
						'referral_medium'	=> 'PROMO CODE'
					]);
				}
			}
			else{
				Session::put('offer_promo_code', 1);
				Session::save();
				return Redirect::to("home")->with('promo_error','Promo Code not valid.');
				exit;
			}
		}
		return Redirect::to("/");
	}
	


	public function createPromoCode($user_Id){
		$this_user = User::find($user_Id);
		if( empty($this_user->promo_code) ){
			$name_len = ( 4 - strlen($user_Id) );
			$promoCode = strtoupper( substr($this_user->name,0,$name_len) ) . $user_Id;
			$this_user->promo_code = $promoCode;
			$this_user->update();
		}
	}
	public function getsignage()
	{	
		$vars['show_layout'] = Input::has("show_layout")?$inputs["show_layout"]:true;
		return View::make('includes.purchasedSignage',compact('vars'));
	}
	
	public function signageZip()
	{
		//$inputs = $request->all();
		return response()->download('/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public/My-Meter Signage.zip');

	}
	public function test(Request $request){
		print_r($request);
		$a = "TESTing";
		return $a;
	}
	public function generateSignage( Request $request, $meter_info='' ){
				
		try{
				$inputs = $request->all();
				//echo "TEST";
				//echo "<pre>"; print_r( $inputs); print_r($meter_info); exit();
				$public_path = public_path();  // /home/mymeter9/public_html/my_meter_production/trunk/public/dev/public
				
				$vars = array();
				if(isset($inputs["lot_id"])){
					$vars['lot_id'] = $inputs["lot_id"];
				}elseif(isset($meter_info["lot_id"])){
					$vars['lot_id'] = $meter_info["lot_id"];
				}
				$lot = Lot::find($vars['lot_id']); //->pluck("price");
				
				if(isset($meter_info["price"]) && !is_array($meter_info["price"])){
					$price = $meter_info["price"];
				}elseif(isset($meter_info["price"]) && is_array($meter_info["price"])){
					$multiple_meter_price = array();
					$multiple_meter_price = $meter_info["price"];
				}else{
					$vars['lot_price'] = $lot->price;
					$price = $vars['lot_price'];
				}
				
				if(isset($meter_info["towing_contact"])){
					$vars['towing_company_number'] = $meter_info["towing_contact"];
				}else{
					$vars['towing_company_number'] = Auth::user()->towing_company_number;
				}
				if(isset($meter_info["meter_id"])){
					//$meter_info["meter_id"] = explode(',',$meter_info["meter_id"];
					if( count($meter_info["meter_id"]) > 0  ){ //and !in_array("",$meter_info["meter_id"])
						//$vars['meter_ids'] = explode(',',$meter_info["meter_id"];
						$vars['meter_ids'] = explode(',',$meter_info["meter_id"]);
					}else{
						$meters = Meter::where("lot_id",$vars['lot_id'])->lists("meter_id");
						if( count($meters) > 0 ){
							$vars['meter_ids'] = $meters;
						}else{
							return Redirect::to('/home/signage');
						}
					}
				}
				elseif( Input::has("meter_id") ){
					if( Input::has("meter_id") and count($inputs["meter_id"]) > 0 and !in_array("",$inputs["meter_id"]) ){
						$vars['meter_ids'] = $inputs["meter_id"];
					}else{
						$meters = Meter::where("lot_id",$vars['lot_id'])->lists("meter_id");
						if( count($meters) > 0 ){
							$vars['meter_ids'] = $meters;
						}else{
							return Redirect::to('/home/signage');
						}
					}
				}
				
				$signage_content = PageContent::where( "page_name", "owner_content" )->get();
				$vars["signage_img"] = "";
				if( count($signage_content) > 0 ){
					$page_content = json_decode($signage_content[0]->page_content);
					$vars["signage_img"] = $page_content->signage_image ;
				}
				if( empty($vars["signage_img"]) ){
					echo $vars["signage_img"] = asset('/images/sign template.jpg');
				}
		
				$contact_no=  $vars['towing_company_number'];
				
				//print_r($vars['meter_ids']);
				//exit;
		
		
				// Using imagepng() results in clearer text compared with imagejpeg()
				//$folder_name = 'METER_id_'.$vars['meter_ids'][0];
				if(isset($meter_info["meter_id"])){
					$folder_name = 'My-Meter Signage'.$vars['lot_id'];
				}else{
					$folder_name = 'My-Meter Signage'.$vars['lot_id'];
				}
		
				$dir_path = $public_path."/".$folder_name;
		
					
				// list($width, $height) = getimagesize('https://my-meter.com/images/custom/20160517082012signage_image.jpg');
				mkdir($dir_path);	
				
				// print_r($vars['meter_ids']);
				// dd($multiple_meter_price);
				 
				 foreach ($vars['meter_ids'] as $meter_id_selected) {
					//ob_start();
						// Set the content-type
					//header('Content-Type: image/png');
					
					
					header("Content-Transfer-Encoding: binary");
					header('Content-Description: File Transfer');
		
		
		
					// Create the image
					//$im = imagecreatetruecolor(400, 30);
					$im = imagecreatefromjpeg('https://my-meter.com/dev/public/images/custom/20160517082012signage_image.jpg');
					
					
					
					
					// Create some colors
					$white = imagecolorallocate($im, 255, 255, 255);
					$grey = imagecolorallocate($im, 128, 128, 128);
					$black = imagecolorallocate($im, 112, 218, 18); 
		
		
					// The text to draw
					if(isset($multiple_meter_price) && is_array($multiple_meter_price)){
						if (array_key_exists($meter_id_selected,$multiple_meter_price[0]))
						  {
							$price = $multiple_meter_price[0][$meter_id_selected];
						  }
					}
					$price_splitted = explode('.',$price);
					// Replace path by your own font path
					$font = $public_path.'/fonts/ufonts.com_impact.ttf';
					
					$simple_font = $public_path.'/fonts/eurostile-bold-1361505679.ttf';
					
					
					//dirname(__FILE__) . '/arial.ttf';
					// Add some shadow to the text
					//imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);
					//$hr = "/hr.";
					
					/* Generate QR code for specific meter id  */
					QrCode::color(112,228,18); 
					QrCode::format('png')->size(860)->margin(0)->color(99,191,25)->generate('https://my-meter.com/dev/public/meterID/'.$meter_id_selected,'/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public/images/qrcodes/'.$meter_id_selected.'.png');
					
					//QrCode::merge('/public/images/favicons/apple-icon-114x114.png')->generate('https://my-meter.com/dev/public/meterID/'.$meter_id_selected,'/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public/images/qrcodes/'.$meter_id_selected.'.png'); 
					
					//https://my-meter.com/dev/public/images/qrcodes/100683.png
					///home/mymeter9/public_html/my_meter_production/trunk/public/dev/public
					
					//$dest = imagecreatefromjpeg('images/blank.jpg');
					$src = imagecreatefrompng('/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public/images/qrcodes/'.$meter_id_selected.'.png');
					
					////imageflip($src, IMG_FLIP_HORIZONTAL);
					imagecopymerge($im, $src, 265, 370, 0, 0, 860, 840, 100);
					
					////$src1 = imagecreatefrompng($public_path.'/images/apple-icon-114x114.png');
					
					//imagecopyresampled($im, $src1, 230, 640, 0, 0, 114, 114, 100,100);
					////imagecopymerge($im, $src1, 167, 647, 0, 0, 114, 114, 100);   
		
					// Add the text
					imagettftext($im, 1200, 0, 1650, 2300, $black, $font, $price_splitted[0]);
					imagettftext($im, 380, 0, 2500, 1450, $black, $font, '.');
					imagettftext($im, 380, 0, 2650, 1450, $black, $font, $price_splitted[1]);
		
		
					//imagettftext($im, 400, 0, 2500, 1910, $black, $font, $hr);
					//imagettftext($im, 400, 0, 2500, 1910, $black, $font, $hr);
		
					////imagettftext($im, 20, 0, 500, 1220,  $black, $simple_font, $contact_no);
					imagettftext($im, 43, 0, 760, 1958, $black, $simple_font, $contact_no);
					//
					$meter_id_with_space = "";
						// $path_image = $meter_id_selected.'saved-example.jpeg';
						//echo "Meter id -- ".$meter_id_selected."<br>";
						 for ($i = 0; $i < strlen($meter_id_selected); $i++)
						{
							$meter_id_with_space .= $meter_id_selected[$i];
							if($i == 2)
							{
								$meter_id_with_space .= " ";
							}else{
								$meter_id_with_space .= "";
							}
						}
				
						////imagettftext($im, 100, 0, 85, 980, $white, $simple_font,$meter_id_with_space );
						
						
						$bbox = imagettfbbox ( 146 , 0 , $simple_font ,$meter_id_with_space);
						$x = $bbox[0] + (imagesx($im) / 2) - ($bbox[4] / 2) - 242;
        				$y = $bbox[1] + (imagesy($im) / 2) - ($bbox[5] / 2) + 505;
				
					 	imagettftext($im, 230, 0, $x, $y, $white, $simple_font,$meter_id_with_space);
						
						//imagettftext($im, 150, 0, 27, 995, $white, $simple_font,$meter_id_with_space );
				
						//imagettftext($im, 600, 0, 250, 3510, $white, $simple_font, $meter_id_with_space );
						//imagettftext($im, 600, 0, 250, 3510, $white, $simple_font, $meter_id_with_space );
						
						
						//header('Content-Disposition: attachment; filename=meter.png');   
						$getImage = ob_get_contents();
						//imagecopymerge($im, $src, 160, 230, 0, 0, 540, 540, 100);
						imagejpeg($im,$dir_path."/".$meter_id_selected.".jpg");
						
						imagedestroy($im);
						
						//ob_end_clean();
				}
		
				//$files = glob('*');
		
				$files = glob($public_path."/".$folder_name.'/*.jpg');
				
				
				//sleep(30);
				if(isset($meter_info["meter_id"])){
					Zipper::make(public_path('My-Meter Signage'.$vars['lot_id'].'.zip'))->add($files)->close();
					////echo "Zipped and download";
					$headers = array(
							'Content-Type' => 'application/octet-stream',
						);
						
					 //return Response()->download($public_path.'/My-Meter Signage'.$vars['lot_id'].'.zip','My-Meter Signage'.$vars['lot_id'].'.zip',$headers)->deleteFileAfterSend(true);
					// echo public_path('/My-Meter Signage'.$vars['lot_id'].'.zip');
					/// echo "<br>".$public_path.'/My-Meter Signage'.$vars['lot_id'].'.zip';
					/// echo "<br>".public_path('My-Meter Signage'.$vars['lot_id'].'.zip'); 
					/// return Response()->download(public_path('My-Meter Signage'.$vars['lot_id'].'.zip'));
					 //return Response()->download($public_path.'/My-Meter Signage'.$vars['lot_id'].'.zip');
					 
					 //return response()->download($public_path.'/My-Meter Signage'.$vars['lot_id'].'.zip','My-Meter Signage'.$vars['lot_id'].'.zip',$headers);
					//exec('zip -r ' . $public_path.'/My-Meter Signage'.$vars['lot_id'].'.zip' . ' ' . $public_path.'/My-Meter Signage'.$vars['lot_id']);
					return 1;
				}else{
					Zipper::make($public_path.'/My-Meter Signage'.$vars['lot_id'].'.zip')->add($files)->close();
					/*return Redirect::to('/home/signage/signageZip')->with('lot_id', $vars['lot_id']);;*/
					$dir_path = '/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public/My-Meter Signage'.$vars['lot_id'];
					if(is_dir($dir_path)) {
		
						$files = glob($dir_path.'/*.jpg'); // get all file names
						
						foreach($files as $file){ // iterate files
							if(is_file($file))
							{
								
								unlink($file); // delete file
							}
							
						}
						rmdir($dir_path);
						
					 }
					 
					return response()->download('/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public/My-Meter Signage'.$vars['lot_id'].'.zip')->deleteFileAfterSend(true);
				}
				
				//return View::make('includes.signage',compact('vars'));
					//return View::make('landowner.signpage_pdf',compact('vars'));
				//return $pdf = PDF::loadView('landowner.signpage', array('vars'=>$vars, "pdfname"=>$ZIPfolder));
				
				//return $pdf->stream();
				//return $pdf->download('Parking Sign.pdf');
				
				
				
		
				//return 1;
						 
			//sleep(30);
			//	echo date("h:i:s");
			//	usleep(60000000);
		//echo date("h:i:s");
		//exit();
				//return response->download($dir_path.".zip");
		
				//return response()->download('/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public/'.$ZIPfolder.'.zip');
		
				//HomeController::zipDir($dir_path, $_SERVER['DOCUMENT_ROOT'].'/'.$folder_name.'.zip');
					/*
					$sourcePath = $dir_path;
					$outZipPath = $_SERVER['DOCUMENT_ROOT'].'/'.$folder_name.'.zip';
					$pathInfo = pathInfo($sourcePath); 
					$parentPath = $pathInfo['dirname']; 
					$dirName = $pathInfo['basename']; 
		
					$z = new ZipArchive(); 
					$z->open($outZipPath, ZIPARCHIVE::CREATE); 
					$z->addEmptyDir($dirName); 
					self::folderToZip($sourcePath, $z, strlen("$parentPath/")); 
					$z->close(); 		
					*/
				
						//return View::make('landowner.signpage_pdf',compact('vars'));
				//$pdf = PDF::loadView('landowner.signpage_pdf', array('vars'=>$vars));
				
				//return $pdf->stream();
				//return $pdf->download('Parking Sign.pdf');
				
				
		}catch(Exception $e){
			echo  $e->getMessage();
		}
	}
	
	public function register( Request $request ){
		$inputs = $request->all();
		
		$generatePassword = str_random(6);
		
		$redirectURL = "/";
		
		$rules = array(
			'name' => 'required',
			'email' => 'required|email|unique:users'
		);
		//validate input data
		$validator = Validator::make($inputs, $rules);
		//if validation fails
		if($validator->fails()){
			//get error messages
			$messages = $validator->messages()->all();
			//echo "Redirect Url -- ".$redirectURL."<br>";
			//echo "<pre>"; print_r($messages); echo "</pre>"; //exit();
			//return with error message
			if(isset($inputs["parking_meter_count"])){
				return $messages;
			}
			return Redirect::to($redirectURL)->withInput($request->except('_token'))->withErrors($messages);
			exit;
		}
		else{

			

			$data['name'] = $inputs['name'];
			$data['email'] = $inputs['email'];	
				
			$role_id = 3;
			
			$referred_by = "";
			if( Cookie::get('referred_by') !== null ){
				$promo_code = Cookie::get('referred_by');
				$user = User::where("promo_code",$promo_code)->get()->first(); 
				$referred_by = $user->id;
			}
			
			$generatePassword = str_random(6);
			
			if(!isset($inputs["parking_meter_count"])){
				$user_created = User::create([
					'name' => $data['name'],
					'email' => $data['email'],
					'password' => bcrypt($generatePassword),
					'security_answer' => uniqid(),
					'role_id' => $role_id,
					'referred_by' => (!empty($referred_by))?$referred_by:'null',
				]);
				
			}else{
				$data['is_agreed'] = $inputs['is_agreed'];
				$data['l_name'] = $inputs['l_name'];
				$data['address'] = $inputs['address'];
				$data['country_list'] = $inputs['country_list'];
				$data['state'] = $inputs['state'];
				$data['postal_code'] = $inputs['postal_code'];
				/*Towng companies detail*/
				if(isset($inputs['towing_contact']) && !empty($inputs['towing_contact'])){
					$data['towing_company_number'] = $inputs['towing_contact'];	
				}else{
					$data['towing_company_number'] = "";	
				}
				
				if(isset($inputs['towing_companies']) && !empty($inputs['towing_companies'])){
					
					$towing_detail = towing_companies::where('id',$inputs['towing_companies'])->get();
					
					if(count($towing_detail) > 0){
						$data['towing_company'] = ( isset($towing_detail[0]["company"])?$towing_detail[0]["company"]:'' );	
					}else{
						$data['towing_company'] = "";
					}
					
				}else{
					
					$data['towing_company'] = "";	
				}
				
				$city = City::where("city_name",$inputs['city'])->where("state_code",$inputs['state'])->where("country_id",$inputs['country_list'])->get();
				
				if(count($city)>0){
					$data['city_id'] = (isset($city[0]->id)?$city[0]->id:'');
				}else{
					$data['city_id'] = "";
				}
	 
				$user_created = User::create([
					'name' 					=> $data['name'],
					'email' 				=> $data['email'],
					'is_agreed' 			=> $data['is_agreed'],
					'last_name' 			=> $data['l_name'],
					'password'				=> bcrypt($generatePassword),
					'role_id' 				=> $role_id,
					'referred_by' 			=> (!empty($referred_by))?$referred_by:'null',
					'street' 				=> $data['address'],
					'country' 				=> $data['country_list'],
					'state' 				=> $data['state'],
					'zip' 					=> $data['postal_code'],
					'city'	 				=> $data['city_id'],
					'towing_company' 		=> $data['towing_company'],
					'towing_company_number' => $data['towing_company_number'],
					'security_answer' 		=> uniqid()
				]);
				
				//echo "<pre>"; print_r($user_created); echo "</pre>";exit();
				
			}
			
			if( !empty($referred_by) ){
				//insert referral entry
				Referral::create([
					'user_id' => $user_created->id,
					'referred_by' => $referred_by,
					'commission' => config('commission'),
					'referral_medium'	=> 'URL'
				]);
			}
			
			$get_content = PageContent::where( "page_name", "automated_emails" )->get();
			$get_content = json_decode($get_content[0]->page_content,true);
			
			$to = $data['email'];
			
			$message = "
				<h3>Greetings!</h3>
				<p>Your account with my-meter.com has been created. Below are the login credentials:</p>
				<p>Email: ".$data['email']."</p>
				<p>Password: ".$generatePassword."</p>
				<a href='" .URL::to('/account/login'). "' target='_blank'>Click here</a> to Login.
				<br><br><br>
				Thanks<br>
				My-meter.com Support Team
			";
			
			$subject = isset($get_content['registration']['subject']) ? $get_content['registration']['subject']  : "Your account has been created!";
			$body = isset($get_content['registration']['body']) ? $get_content['registration']['body']  : "";
			
			if( !empty($body) ){
				$body = str_ireplace("[[user_email]]",$data['email'],$body);
				$body = str_ireplace("[[user_password]]",$generatePassword,$body);
				$body = str_ireplace("[[click_here_link]]","<a href='" .URL::to('/account/login'). "' target='_blank'>Click here</a>",$body);
				$body = str_ireplace("[[user_name]]",$data['name'],$body);
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
			
			
			//$data['email']
			
			
			
			
			if(!isset($inputs["parking_meter_count"])){
				mail($to,$subject,$body,$headers);	
				return Redirect::to($redirectURL)->with("register_success","true");
			}else{
				$data['password'] = $generatePassword;
				return $data;
			}
		}
	

	}
	
	public function save_towing_number(Request $request){
		$inputs = $request->all();
		$userid = Auth::user()->id;
		if( Input::has("towing_company_number") ){
			$user = User::find($userid);
			$user->towing_company_number = $inputs["towing_company_number"];
			$user->update();	
		}
		return Redirect::to("/home/signage");
	}

	public function get_meters_by_day(Request $request){
		$inputs = $request->all();
		$meter_id = $inputs["meter_id"];

		if( ( isset($inputs['report_type']) and $inputs['report_type'] == "month" ) ){
			$groupBy =  "month(payments.created_at), year(payments.created_at)";
		}
		else{
			$groupBy =  "day(payments.created_at), month(payments.created_at), year(payments.created_at)" ;
			$inputs['report_type'] = "day";	
		}
		$meter_details = array();
		$meter_details_array = array();
		if( !empty($inputs["start_date"]) || !empty($inputs["end_date"])){
			$meter_details[] = PaymentItem::join("payments","payments.id","=","payment_items.payment_id")->join("meters","meters.id","=","payment_items.meter_id")->where("meters.meter_id",$meter_id)->where("payments.payment_type",'meter_hire')->where( function( $obj ) use($inputs) {

				if( !empty($inputs["start_date"]) ){
					$obj->where("payments.created_at",">=",date("Y-m-d H:i:s",strtotime($inputs["start_date"]." 00:00:00")));
				}
				if( !empty($inputs["end_date"]) ){
					$obj->where("payments.created_at","<=",date("Y-m-d H:i:s",strtotime($inputs["end_date"]." 23:59:00")));
				}
				

		} )->groupBy(DB::raw($groupBy))->select(DB::raw("payments.created_at as trans_day, sum(payment_items.hours) as hours, sum(payment_items .total_price * payment_items .payment_commission / 100) as trans_amount,
count(payments.id) as transactions "))->get();
		}else{
			for($i=1; $i<= date('d'); $i++){
				if($i <= 9){		
					$i = "0".$i; 
				}
				$inputs["i"] = $i;
				$meter_details[]  = PaymentItem::join("payments","payments.id","=","payment_items.payment_id")->join("meters","meters.id","=","payment_items.meter_id")->where("meters.meter_id",$meter_id)->where("payments.payment_type",'meter_hire')->where( function( $obj ) use($inputs) {
	
					
						$obj->where("payments.created_at",">=",date('Y-m-').$inputs['i']." 00:00:00");
					
					
						$obj->where("payments.created_at","<=",date('Y-m-').$inputs['i']." 23:59:00");
					
					
					} )->groupBy(DB::raw($groupBy))->select(DB::raw("payments.created_at as trans_day, sum(payment_items.hours) as hours, sum(payment_items .total_price * payment_items .payment_commission / 100) as trans_amount,
	count(payments.id) as transactions "))->get();
					
				///$meter_details = array_merge($meter_details_array);
				//echo "<pre>" ; print_r($meter_details); echo "</pre>";
			}
		}
		
		/*	
				$meter_details = PaymentItem::join("payments","payments.id","=","payment_items.payment_id")->join("meters","meters.id","=","payment_items.meter_id")->where("meters.meter_id",$meter_id)->where("payments.payment_type",'meter_hire')->where( function( $obj ) use($inputs) {

				if( !empty($inputs["start_date"]) ){
					$obj->where("payments.created_at",">=",date("Y-m-d H:i:s",strtotime($inputs["start_date"]." 00:00:00")));
				}else{
					//$inputs["start_date"] = date('Y-m-01 ')."00:00:00";
					//$obj->where("payments.created_at",">=",date('Y-m-01 ')."00:00:00");
				}
				if( !empty($inputs["end_date"]) ){
					$obj->where("payments.created_at","<=",date("Y-m-d H:i:s",strtotime($inputs["end_date"]." 23:59:00")));
				}else{
					//$inputs["end_date"] = date('Y-m-d H:i:s ');
					//$obj->where("payments.created_at","<=",date('Y-m-d H:i:s '));
				}
				

		} )->groupBy(DB::raw($groupBy))->select(DB::raw("payments.created_at as trans_day, sum(payment_items.hours) as hours, sum(payments.amount) as trans_amount,
count(payments.id) as transactions "))->orderBy('payments.created_at', 'asc')->get();
		*/
		$user = PaymentAccount::where("user_id",Auth::user()->id)->get()->first();
		$created_on = date('m/Y',strtotime($user["created_at"]));
		//echo "<pre>"; print_r($meter_details); echo "</pre>";
	
		//exit();
		if( count($meter_details) ){ // 
			$html = View::make("landowner.meters_by_date",compact("meter_details","inputs","created_on"))->render();
			echo $html;
		}else{
			echo '<div class="alert alert-warning">There have been no transactions to date. Please check again after your parking space has been booked.</div>';
		}
		exit;
	}
	

	public function get_states_by_country(Request $request){
		$inputs = $request->all();
		$country_id = $inputs["country_id"];
		$states = City::where("country_id",$country_id)->groupBy("state_code")->get();
		$html = '<option value="test"> Province</option>'; 
		if( count($states) ){
			foreach($states as $state){
				$html .= '<option value="'.$state->state_code.'">'.$state->state_code.'</option>';
			}
		}
		echo $html;
		exit;
	}
	
	public function auto_suggest_cities(Request $request){
		
		$return = array();
		
		$inputs = $request->all();
		$country_id = $inputs["country_id"];
		$state_code = $inputs["state_code"];
		
		if(!empty($state_code)){
			
			$cities = City::where("country_id",$country_id)->where("state_code",$state_code)->get();
		}else{
			
			$cities = City::where("country_id",$country_id)->get();
		}
		
		//return $cities[];
		if( count($cities) ){
			foreach( $cities as $city ){

				//$obj = (object)array();
				//$obj->city_id	= $city->id;
				//$obj->city_name	= $city->city_name;
				$return[] = $city->city_name;
			}
		}

		return $return;
	}
	
	public function get_cities($country_id,$state_code){

		$return = array();
		$cities = City::where("country_id",$country_id)->where("state_code",$state_code)->get();
		
		if( count($cities) ){
			foreach( $cities as $city ){

				$obj = (object)array();
				$obj->city_id	= $city->id;
				$obj->city_name	= $city->city_name;
				$return[] = $obj;
			}
		}

		return $return;
	}
	
	public function get_cities_by_state(Request $request){

		$inputs = $request->all();
		if(isset($inputs["country_id"])){
			$country_id = $inputs["country_id"];
		}else{
			$country_id = 2;
		}
		$cities = $this->get_cities($country_id,$inputs["state_id"]);

		$html = "";
		if( count($cities) ){
			foreach( $cities as $city ){
				$html .= " <option value='". $city->city_id ."'>". $city->city_name ."</option> ";	
			}
		}else{
			$html .= " <option value='0'>No City found.</option> ";
		}
		echo $html;
		exit;
	}
	
	
	public function add_payment_details(){
		/*$data = array(
			"endpoint" 		=>		"/api/catalogs/countries",
		);
		$countries = $this->transpay_curl($data);
		$countries = $countries["Countries"];*/
		
		
		/* Commented for now as per client's requirement */
		/*$user = PaymentAccount::where("user_id",Auth::user()->id)->get()->first();

		if( !count($user) ){

			$user = (object)array();
			$name = Auth::User()->name;
			if( preg_match('/\s/',$name) ){
				$name = explode(" ",$name);
				$user->FirstName = $name[0];
				$user->LastName = $name[1];
			}else{
				$user->FirstName = $name;
				$user->LastName = "";
			}
			
			$user->CompleteAddress = Auth::User()->street;
			$user->CityId = Auth::User()->city;
			$user->CountryIsoCode = Country::where("id",Auth::User()->country)->pluck("iso");
			$user->StateId = Auth::User()->state;
			$user->TownId = "";
			$user->ReceiveCurrencyIsoCode = "";
			$user->BankId = "";
			$user->PaymentModeId = "";
			$user->Account = "";
			$user->BankBranchId = "";
		}

		if( !empty($user->Account) )
			$user->Account = Crypt::decrypt($user->Account);
		if( !empty($user->BankBranchId) )
			$user->BankBranchId = Crypt::decrypt($user->BankBranchId);
		

		$countries = array(
			array(
				"IsoCode"		=>	"CA",
				"Name"			=>	"Canada",
				"HasTown"		=>	"I"
			),
			array(
				"IsoCode"		=>	"US",
				"Name"			=>	"United States",
				"HasTown"		=>	"I"
			)
		);

		$html = View::make("includes.add_payment_details", compact('countries','user'));
		echo $html;*/
	}

	public function get_payment_states(Request $request){
		$inputs = $request->all();
		$data = array(
			"endpoint" 		=>		"/api/catalogs/states?countryisocode=".$inputs["country"],
		);
		$selected_state = Input::has("selected_state") ? $inputs["selected_state"] : "";
		$states = $this->transpay_curl($data);

		$html = "";
		foreach( $states["States"] as $state ){
			if( isset($state["Id"]) and isset($state["Name"]) )
				$html .= " <option value='". $state["Id"] ."' data-IsoCode='". $state["IsoCode"] ."' ". (($selected_state==$state["Id"] or strtolower($selected_state)==strtolower($state["Name"])) ? 'selected="selected"' : '') ." >". $state["Name"] ."</option> ";	
		}
		echo $html;
	}

	public function get_payment_cities(Request $request){
		
		$inputs = $request->all();

		/*
		import cities script to db
		$countries = Country::where("status",1)->get();

		$count = 0;
		foreach( $countries as $country ){
			$states = City::where("country_id",$country->id)->groupBy("state_code")->get();
			foreach ($states as $state) {

				$data = array(
					"endpoint" 		=>		"/api/catalogs/cities?countryisocode=".$country->iso."&stateid=".$state->state_code,
				);
				$cities = $this->transpay_curl($data);
				dump($cities);
				if( count($cities) ){
					foreach( $cities["Cities"] as $city ){
						if( isset($city["Id"]) and isset($city["Name"]) ){
							$city_ = new City;
							$city_->country_id = $country->id;
							$city_->state_code = $state->state_code;
							$city_->city_name  = $city["Name"];
							$city_->city_id  = $city["Id"];
							$city_->save();
						}
						$count++;
					}
				}
				
			}
		}
		dd($count);*/

		/*$data = array(
			"endpoint" 		=>		"/api/catalogs/cities?countryisocode=".$inputs["country"]."&stateid=".$inputs["state"],
		);*/
		$data = City::where("country_id",$country_id)->where("state_code",$state_code)->get();

		$selected_city = Input::has("selected_city") ? $inputs["selected_city"] : "";
		$cities = $this->transpay_curl($data);

		$html = "";
		foreach( $cities["Cities"] as $city ){
			if( isset($city["Id"]) and isset($city["Name"]) )
				$html .= " <option value='". $city["Id"] ."' ". ( ( $selected_city == $city["Id"] or strtolower($selected_city) == strtolower($city["Name"]) ) ? 'selected="selected"' : '' ) ." >". $city["Name"] ."</option> ";	
		}
		echo $html;

		
	}

	public function get_payment_towns(Request $request){
		$inputs = $request->all();
		$data = array(
			"endpoint" 		=>		"/api/catalogs/towns?countryisocode=".$inputs["country"]."&stateid=".$inputs["state"]."&cityId=".$inputs["city"],
		);
		
		$selected_town = Input::has("selected_town") ? $inputs["selected_town"] : "";

		$towns = $this->transpay_curl($data);


		$html = "";
		if( count($towns["Towns"]) ){
			foreach( $towns["Towns"] as $town ){
				if( isset($town["Id"]) and isset($town["Name"]) )
					$html .= " <option value='". $town["Id"] ."' data-Status='". $town["Status"] ."' ". ( ( $selected_town == $town["Id"] ) ? 'selected="selected"' : '' ) ." >". $town["Name"] ."</option> ";	
			}
		}
		echo $html;
	}
	
	public function get_payment_modes(Request $request){
		$inputs = $request->all();
		$data = array(
			"endpoint" 		=>		"/api/catalogs/paymentmodes?countryisocode=".$inputs["country"]."&cityId=".$inputs["city"],
		);
		
		$selected_mode = Input::has("selected_mode") ? $inputs["selected_mode"] : "";
		
		$data = $this->transpay_curl($data);


		$html = "";
		if( count($data["PaymentModes"]) ){
			foreach( $data["PaymentModes"] as $pm ){
				if( isset($pm["Id"]) and isset($pm["Name"]) )
				$html .= " <option value='". $pm["Id"] ."' data-RequieresBank='". $pm["RequieresBank"] ."' data-RequieresAccount='". $pm["RequieresAccount"] ."' data-Status='". $pm["Status"] ."' ". ( ( $selected_mode == $pm["Id"] ) ? 'selected="selected"' : '' ) ." >". $pm["Name"] ."</option> ";
			}
		}
		echo $html;
	}

	public function get_payment_currency(Request $request){
		$inputs = $request->all();
		$data = array(
			"endpoint" 		=>		"/api/transaction/receivercurrencies?countryisocode=".$inputs["country"]."&StateId=".$inputs["state"]."&cityId=".$inputs["city"]."&PaymentModeId=".$inputs["payment_mode"],
		);
		
		$selected_Currency = Input::has("selected_Currency") ? $inputs["selected_Currency"] : "";
		
		$data = $this->transpay_curl($data);


		$html = "";
		if( count($data["Currencies"]) ){
			foreach( $data["Currencies"] as $c ){
				if( isset($c["IsoCode"]) )
					$html .= " <option value='". $c["IsoCode"] ."' data-IsPaymentCurrency='". $c["IsPaymentCurrency"] ."' ". ( ( $selected_Currency == $c["IsoCode"] ) ? 'selected="selected"' : '' ) ." >". $c["IsoCode"] ."</option> ";
			}
		}
		echo $html;
	}
	
	public function get_payment_banks(Request $request,$selected_country=NULL,$country_iso=NULL){
		$inputs = $request->all();
		if(!empty($country_iso)){
			$selectedCountry_iso = $country_iso;
			}
		else {$selectedCountry_iso = $inputs['country_iso']; }

		$data = array(
			"endpoint" 		=>		"/api/catalogs/banks?countryisocode=".$selectedCountry_iso,
		);

		if(!isset($selected_bank)) {
			$selected_bank = "";
		}
		//$selected_bank = Input::has("selected_bank") ? $inputs["selected_bank"] : "";
		$data = $this->transpay_curl($data);
		
		
		$return = array();
		$html='';
		if(!empty($data["Banks"]))
		{
			if( count($data["Banks"]) ){
			foreach( $data["Banks"] as $bank ){
				if( isset($bank["Id"]) and isset($bank["Name"]) )
				if($country_iso == NULL){
					$html .= " <option value='". $bank["Id"] ."' ". ( ( $selected_bank == $bank["Id"] ) ? 'selected="selected"' : '' ) ." >". $bank["Name"] ."</option> ";
				}
				else {
					$obj = (object)array();
					$obj->Id	= $bank["Id"];
					$obj->Name	= $bank["Name"];
					$return[] = $obj;
				}
			}
		}
		}
		if($country_iso == NULL){ return $html; } 
		else 
		return $return;
	}


	public function transpay_curl($data = array()){

		if( !count($data) ) return;

		$token = "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxBdXRoZW50aWNhdGlvbj4NCiAgPElkPjI4Yzk1OTk4LTRhZTEtNGUxNi05ZWM5LTU5NTlhYzMzNzY1MTwvSWQ+DQogIDxVc2VyTmFtZT40L2FHM3Iwd0JUT1lzeXFsYXRDT0hWSTErTm1DN1VqSVZZRzFPUnc1M2VKd2xPZFowVTZEcjJxNmt5QTBYSjRLZmdIYUsvbnROaXE4a3AzN245VUpaNFNQYXBlM2szNDU0TmxFRzdjY2xlWFBvVjl0SFljWTVIN1RMNllkVGh0bXlHN2lUYlYyNlk5TjlaSHUwZUpQVEtwaVphc2tWMi9wZzZqRFZKZmZ0MXM9PC9Vc2VyTmFtZT4NCiAgPFBhc3N3b3JkPk1kVFpZR2QrMGUyOEdkaHRZQWlvRlAxSXNGVDJyNVRWQ252Y1ZUL29SUVJBdVZBckJab001UTRGWWhWaWZhSk82NkMvdXN2NmlXMWpuTzJuVmJiZ3N4Rit6NFpHWDM5M2hCWVFyMFBJUWgzdHc2VjNYZ2RCRFRKRm54VFRyTXJNZ1ZKVWRScFpuZWZObWhINU42MEhLU28zc2R4RG9SRUZ5K0hmRitQMHlhOD08L1Bhc3N3b3JkPg0KICA8QnJhbmNoSWQ+Z0Zkb3hmcXorWGp4RFpIcDlJUk5rQlVhL2UwTlkxQTN5azFTZldCYTBabjMrVVBPd2J2YWtFZlg2NGhiYkR6bVZCdjUvMmZiVUhoa1lyOWY2alp2U2lXODBwTHRPTDgxY2RwNXRhcXZLemx0VU5oaE11OE1zdWU0eC9HMld3MmY3QVRhL3dqTGJjZEZCSGJTMzI2ci9pMXVsU3JMMFNTb0lQN2lyQ1JmY204PTwvQnJhbmNoSWQ+DQo8L0F1dGhlbnRpY2F0aW9uPg==";

		$endpoint = "https://demo-api.transfast.net". $data['endpoint'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, '60');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: Credentials '.$token;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response=curl_exec ($ch);

		curl_close($ch);

		return  json_decode($response,true);
	}

	/* To be removed for now*/
	/*public function save_payment_details(Request $request){
		
		
		$inputs = $request->all();

		$pa = new PaymentAccount;
		if (!$pa->validate($inputs)){
			$messages = $pa->errors();
			$html = '<div class="alert alert-warning">';
			foreach( $messages as $msg ){
				$html .= '<p>'.$msg.'</p>';
			}
			$html .= '</div>';
		}
		else{
			$pa_id = PaymentAccount::where("user_id",Auth::user()->id)->pluck("id");
			if( $pa_id ) $pa = PaymentAccount::find($pa_id);

			$fields = array('FirstName','FirstName','LastName','CompleteAddress','CountryIsoCode','StateId','CityId','TownId','PaymentModeId','ReceiveCurrencyIsoCode','BankId','Account','BankBranchId');
			
			foreach( $fields as $field ){
				$pa->{$field} =  $inputs[$field];
			}

			$pa->user_id = Auth::user()->id;
			$pa->Account = Crypt::encrypt($pa->Account);
			$pa->BankBranchId = Crypt::encrypt($pa->BankBranchId);
			$pa->save();

			$html = '<div class="alert alert-success">Details saved successfully!</div>';
		}

		echo $html;

	}*/
	
	private static function folderToZip($folder, &$zipFile, $exclusiveLength) { 
    $handle = opendir($folder); 
    while (false !== $f = readdir($handle)) { 
      if ($f != '.' && $f != '..') { 
        $filePath = "$folder/$f"; 
        // Remove prefix from file path before add to zip. 
        $localPath = substr($filePath, $exclusiveLength); 
        if (is_file($filePath)) { 
          $zipFile->addFile($filePath, $localPath); 
        } elseif (is_dir($filePath)) { 
          // Add sub-directory. 
          $zipFile->addEmptyDir($localPath); 
          self::folderToZip($filePath, $zipFile, $exclusiveLength); 
        } 
      } 
    } 
    closedir($handle); 
  } 
  public function download_page($path){
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$path);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		
		$retValue = curl_exec($ch);		
	
		curl_close($ch);
		return $retValue;
		
	}
  public function TowingCompany(Request $request)
	{
		$input = $request->all();
		
		if(isset($input['city'])){
			$city = $input['city'];	
			$towing_detail = towing_companies::where('city_name',$city)->get();
			return $towing_detail;
		}elseif(isset($input['id'])){
			$id = $input['id'];	
			$towing_detail = towing_companies::where('id',$id)->get();
			return $towing_detail;
		}elseif(isset($input['city_id'])){
			$city_id = $input['city_id'];	
			$towing_detail = towing_companies::where('city_id',$city_id)->get();
			return $towing_detail;
		}

		/* Google API fo fetch the towing companies located in selected city */
		
		/*$in = "Towing Companies in ".$city;
		$in = str_replace(' ','+',$in); // space is a +
		$url  = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='.$in.'&oq='.$in.'';
		
		$fetched_data = array();
		$path ='https://maps.googleapis.com/maps/api/place/textsearch/json?query=towing+companies+in+'.urlencode($city).'&key=AIzaSyCLv-b_3t1ps0zVJuJ-GrPoL6TObnJKiuQ';
		
		$out =  $this->download_page($path);
		$data = json_decode($out, true);
		$total_result = count($data["results"]);
		foreach($data["results"] as $key=>$result){
			$fetched_data[$key]["company"] = $result["name"];
			$contacts =  $this->download_page("https://maps.googleapis.com/maps/api/place/details/json?reference=".$result["reference"]."&key=AIzaSyBwpbrKvoACqEhpsfOo6a-pVJBDWTiu0d4");
			$contacts_ = json_decode($contacts, true);
			$fetched_data[$key]["contact"] = $contacts_["result"]["formatted_phone_number"];
		}
		if($city == "WINNIPEG"){
			$fetched_data[$total_result]["company"] = "Dr. Hook Towing";
			$fetched_data[$total_result]["contact"] = "204-956-4665";
		}
		return $fetched_data;
		*/
		
	}
	public function agree_to_terms(Request $request){
		$data = PageContent::where("page_name","owner_agreement")->get();
		//$data = json_decode($data[0]->page_content);
		echo $data;  
		exit();		
	}
	public function Sharefeed( Request $request ){
		$inputs = $request->all();
		$user = new User;
		$share_feed = $user->sendmail_or_text($inputs);
		if($share_feed == 1){
			return Redirect::to("/home")->with('success', 'Message Sent Successfully.');
		}
	}
	
	public function filter_transactions(Request $request){
		$inputs = $request->all();
	
		$meter_id = $inputs["meter_id"];
		
		
		if( ( isset($inputs['report_type']) and $inputs['report_type'] == "month" ) ){
			$groupBy =  "month(payments.created_at), year(payments.created_at)";
		}
		else{
			$groupBy =  "day(payments.created_at), month(payments.created_at), year(payments.created_at)" ;
			$inputs['report_type'] = "day";	
		}
		
		
		$groupBy =  "day(payments.created_at), month(payments.created_at), year(payments.created_at)" ;
		
		if( !empty($inputs["start_date"]) || (!empty($inputs["end_date"])) ){
			$meter_details[] = PaymentItem::join("payments","payments.id","=","payment_items.payment_id")->join("meters","meters.id","=","payment_items.meter_id")->where("meters.meter_id",$meter_id)->where("payments.payment_type",'meter_hire')->where( function( $obj ) use($inputs) {
	
					
						if( !empty($inputs["start_date"]) ){
							$obj->where("payments.created_at",">=",date("Y-m-d H:i:s",strtotime($inputs["start_date"]." 00:00:00")));
						}else{
						}
						if( !empty($inputs["end_date"]) ){
							$obj->where("payments.created_at","<=",date("Y-m-d H:i:s",strtotime($inputs["end_date"]." 23:59:00")));
						}else{
						}
					
			} )->groupBy(DB::raw($groupBy))->select(DB::raw("payments.created_at as trans_day, sum(payment_items.hours) as hours, sum(payment_items .total_price * payment_items .payment_commission / 100) as trans_amount,
	count(payments.id) as transactions "))->get();
		
		}else{
		
			for($i=1; $i<= date('d'); $i++){
					if($i <= 9){		
						$i = "0".$i; 
					}
					$inputs["i"] = $i;
					$meter_details[]  = PaymentItem::join("payments","payments.id","=","payment_items.payment_id")->join("meters","meters.id","=","payment_items.meter_id")->where("meters.meter_id",$meter_id)->where("payments.payment_type",'meter_hire')->where( function( $obj ) use($inputs) {
		
						
							$obj->where("payments.created_at",">=",date('Y-m-').$inputs['i']." 00:00:00");
						
						
							$obj->where("payments.created_at","<=",date('Y-m-').$inputs['i']." 23:59:00");
						
						
						} )->groupBy(DB::raw($groupBy))->select(DB::raw("payments.created_at as trans_day, sum(payment_items.hours) as hours, sum(payments.amount) as trans_amount,
		count(payments.id) as transactions "))->orderBy('payments.created_at', 'asc')->get();
						
					///$meter_details = array_merge($meter_details_array);
					//echo "<pre>" ; print_r($meter_details); echo "</pre>";
				}
		}
			
		//echo "<pre>"; print_r($inputs); echo "</pre>";
		//dd($meter_details);
		if( count($meter_details) ){
			$html = View::make("landowner.meters_by_date",compact("meter_details","inputs"))->render();
			echo $html;
		}else{
			$inputs["failure"] = "No Result Found";
			
			$html = View::make("landowner.meters_by_date",compact("meter_details","inputs"));
			echo $html;
			//echo '<div class="alert alert-warning">There have been no transactions to date. Please check again after your parking space has been booked.</div>';
		}
		exit;
		
	}
	public function order_towing_details(Request $request){
		$inputs = $request->all();
		$meter_id = Meter::where('meter_id',$inputs["meter_id"])->pluck('id');
		$payment_id = PaymentItem::where('meter_id',$meter_id)->orderBy('id','desc')->limit(1)->pluck('payment_id');
		//echo $payment_id;
		if(!empty($payment_id)){
			$order_details = Payment::where('id',$payment_id)->get();
			$order_details[0]->towing_company = towing_companies::where('company',$order_details[0]->towing_company)->pluck('id');
			return $order_details;
		}
		
	}
	public function save_offer(Request $request){
  
		$input = $request->all();
		$redirectURL = "/home";
		  
		$offer_name  = ( isset( $input["offer_name"] )?$input["offer_name"]:'' );
		$offer_start_at = ( isset( $input["offer_start_at"] )?$input["offer_start_at"]:'' );
		$offer_end_at  = ( isset( $input["offer_end_at"] )?$input["offer_end_at"]:'' );
		$price    = ( isset( $input["price"] )?$input["price"]:'' );
		$offer_start_at =  date('Y-m-d h:i:s', strtotime($offer_start_at));
		$offer_end_at =  date('Y-m-d h:i:s', strtotime($offer_end_at));
		  $meter_ids = implode($input["meter_ids"],',');   
		$form_action  = ( ( isset( $input["form_action"] ) && $input["form_action"] == 1 )?"insertion":"updation" );
		  
		  
		//echo $offer_start_at; echo $offer_end_at; exit;
		$rules = [
			'meter_ids'   => 'required',
			'offer_name'   => 'required|max:255',
			'offer_start_at'  => 'required',
			'offer_end_at'  => 'required',
			'price'    => 'required'
		];
		$validator = Validator::make($input,$rules);
		  
		if ( $validator->fails() ){
			$messages = $validator->messages()->all();
		    return Redirect::to($redirectURL)->withInput($request->except('_token'))->withErrors($messages);   
		}else{
		   
		   if( $form_action == "insertion" )
		   {
		   
				/* Insertion */
				if( count( $input["meter_ids"] ) > 0 ){
			
			 		
			 
					 DB::table('offers')->insert(
					   ['meter_id'=>$meter_ids , 'offer_name'=>$offer_name, 'start_time'=>$offer_start_at, 'end_time'=>$offer_end_at, 'price'=>$price ]
					 ); 
			 		$vars = array();
			 		$vars['tab'] = 'qr_code';
			 		return Redirect::to($redirectURL)->with( ['success' => 'Offer Added Successfully.' , 'tab' =>'qr_code'] ); 
			 
			}
		   }else{
		   
			/* Updation */
			//echo "<pre>"; print_r($input); exit;
			$offer_data = Offer::find($request->offer_id);
			$offer_data->meter_id = $meter_ids; 
			$offer_data->offer_name = $offer_name;
			$offer_data->start_time = $offer_start_at;
			$offer_data->end_time = $offer_end_at;
			$offer_data->price = $price ;
			
			$offer_data->save(); 
			return Redirect::to($redirectURL)->with( ['success' => 'Offer updated Successfully.' , 'tab' =>'qr_code'] ); 
		   }
		   
		  }
		  
		  
 }
	
	public function offer_detail(Request $request){
		
		$input = $request->all();
		$offer = new Offer;
		$offer_details = $offer->show_meter_id($input['offer_id']);
		return $offer_details;
		//echo "<pre>"; print_r($offer_details);  print_r($input); exit;
	}
	public function groups_name(Request $request){
		$id = $request->id;
		$lot = Lot::where('id',$id)->where( ["status"=>1])->get();
		$hr_daily = $lot[0]->hr_daily;
		$lot_price = $lot[0]->price;
		//echo "<pre>"; print_r($price); exit;
		
		/* fetch variable rates */
		$variable_rates = VariableRate::where('lot_id',$id)->where('status',1)->get(); 
		
		$variable_rates_meta = DB::table("variable_rates_meta")->get() ;
		
		$html = "";
		$count = 1;
		$color_codes = array('#87d236','#92eb32','#5cff4e','#32eb5b','#32eb87','#32ebbf','#32d5eb','#32b3eb','#3292eb','#3255eb','#6432eb','#a032eb');
		if( isset($variable_rates) and count($variable_rates)>0 ){
			foreach( $variable_rates as $rates )
			{
				/* Skip variable rates that come and gone only with offer type special event and date range */
				
				$skip = 0;
				if($rates["offer_type"] == 6 || $rates["offer_type"] == 7){
					 if($rates["start_date"]<date('Y-m-d h:i:s')){
						 $skip = 1;
					 }
				}
				
				if( $skip == 0 ){ 
				 
					$hide_date_time = "";	
					$hide_time = "";	
					
					if($rates["offer_type"] == 3 || $rates["offer_type"] == 5){
						$hide_date_time = " style=display:none;";		
					}elseif($rates["offer_type"] == 6 || $rates["offer_type"] == 7){
						$hide_time = " style=display:none;";	
					}elseif($rates["offer_type"] == 4){
						$hide_date_time = " style=display:none;";	
						$hide_time = " style=display:none;";	
					}
					
					/*if( isset( $rates["start_date"] ) ){
						$data["start_date"][$count] = $rates["start_date"];
					}else{$data["start_date"][$count]="";}
					
					if( isset( $rates["end_date"] ) ){
						$data["end_date"][$count] = $rates["end_date"];
					}else{$data["end_date"][$count]="";}*/
					
					if($count>12){
						$background_color = $color_codes[$count%12];
					}else{										
						$background_color = $color_codes[$count-1];
					}
					
					if( $rates["lot_id"] == $id ){
						
						$prices = array('2.00', '2.50', '3.00', '3.50', '4.00', '4.50', '5.00', '5.50', '6.00', '6.50', '7.00', '7.50', '8.00', '8.50', '9.00', '9.50');
					
						$html .= '<div class="panel panel-default variable_rate_panel" id="variable_rate_'.$count.'">
										<div class="panel-heading" style="background-color:'.$background_color.'">
										  <h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$count.'">
											  <i class="fa fa-caret-right"></i> Variable Rate '.$count.': 
											</a>
											<img src="https://my-meter.com/dev/public/images/panel_close1.png" class="variable_rate_close" data-attr="'.$rates["id"].'">
										  </h4>
										</div>
										
										<div id="collapse'.$count.'" class="panel-collapse collapse">
											<div class="panel-body">
												 <input type="hidden" name="variable_rates[rate_id][]" value="'.$rates["id"].'" /> 
												<div class="form-group">
											
													<div class="row">
														<div class="col-lg-6">
										
															<select name="variable_rates[price][]" class="form-control">';
															
																foreach( $prices as $price ):
																	$selected = "";
																	if( $price ==  $rates["price"]){
																		$selected = " selected='selected'";	
																	}
																	 $html .= '<option value="'.$price.'"'. $selected.'>$'.$price.'</option>';
																   
																endforeach;
															
															$html .= '</select>
														</div>
												
														<div class="col-lg-6">
														  <select name="variable_rates[hr_flat][]" class="form-control">';
														  for($i=0;$i<=1;$i++){
															$selected = "";
															if( $variable_rates_meta[$i]->id ==  $rates["hr_flat"] ){
																$selected = " selected='selected'";	
															}
															  $html .= '<option value="'.$variable_rates_meta[$i]->id.'" '.$selected.'>'.$variable_rates_meta[$i]->meta_key.'</option>';
														  }
														  $html .= '</select>
														</div>
													</div>
												</div>
										
												<div class="form-group">
												  <div class="row">
													<div class="col-lg-12">
													  <select name="variable_rates[offer_type][]" class="form-control offer_type">
													  ';
													  
													  $data_type = array("","","timepicker","","timepicker","date_time_picker","date_time_picker");
													  
													  for($i=2;$i<=6;$i++){
														  $selected = "";
														  if( $variable_rates_meta[$i]->id == $rates["offer_type"] ){
															   $selected = ' selected="selected" ';
														  }
															  
														  $html .= '<option data-type="'.$data_type[$i].'" value="'.$variable_rates_meta[$i]->id.'"'.$selected.'>'.$variable_rates_meta[$i]->meta_key.'</option>';
														  
													  }
													   $html .= '
													  </select>
													</div>
												  </div>
												</div>
							
												<div class="form-group time_fields" '.$hide_time.'>
												  <div class="row">
													<div class="col-lg-6">
													  <input type="text" class="form-control timepicker defaultstart_time" name="variable_rates[start_time][]" placeholder="Start Time" value="'.( isset( $rates["start_time"] )?$rates["start_time"]:'' ).'" />
													</div>
													<div class="col-lg-6">
													  <input type="text" class="form-control timepicker defaultend_time" name="variable_rates[end_time][]" placeholder="End Time" value="'.( isset( $rates["end_time"] )?$rates["end_time"]:'' ).'" />
													</div>
												  </div>
												</div>
							
												<div class="form-group date_fields" '.$hide_date_time.'>
												  <div class="row">
													<div class="col-lg-12">
													  <input type="text" class="form-control date_time_picker start_time" name="variable_rates[start_date][]" placeholder="Start Time" value="'.( isset( $rates["start_date"] )?$rates["start_date"]:' .date("Y-m-d h:i:sa").' ).'" />
													</div>
												  </div>
												</div>
										
												<div class="form-group date_fields" '.$hide_date_time.'>
												  <div class="row">
													<div class="col-lg-12">
													  <input type="text" class="form-control date_time_picker end_time" name="variable_rates[end_date][]" placeholder="End Time" value="'.( isset( $rates["end_date"] )?$rates["end_date"]:'1' ).'" />
													</div>
												  </div>
												</div>
											</div>
										</div>
									</div>';
						
						
			
					}
					$count++;
				}
			}
		
		}
		$data["price"] 		= $lot_price;
		$data["hr_daily"]  	= $hr_daily;
		$data["variable_rates"] = $html;
		
		return $data;
	}
	public function qrCode(Request $request){
		
		$input = $request->all();
		
		$lot_id 	= $input["lot_id"];
		$lot_price 	= $input["default_rate"];
		$hr_daily 	= $input["hr_daily"];
		
		$userid = Auth::user()->id;
		
		
		/************************************* Update price and hr_daily for the sleected Group *************************************/
		$lot 			= lot::find($lot_id);
		$lot->price 	= $lot_price;
		$lot->hr_daily 	= $hr_daily;
		$lot->update();
		
		/******************************** check if variable rates exists for the selected lot id ************************************/	
		
		$count_records = DB::table('variable_rates')->where('lot_id',$lot_id)->count();
		if( $count_records > 0 ){
					
		}else{
			
		}
		
		$variable_rates_columns = array('price','hr_flat','offer_type','start_time','end_time','start_date','end_date','bg_color');
			
		/*** Save Variable Rates ***/
		
		$columns = array('price','hr_flat','offer_type');
		
		$count = ( isset($input["variable_rates"]["price"])?count($input["variable_rates"]["price"]):0 ); 
		
		if( isset($input["variable_rates"]["price"]) and !empty($input["variable_rates"]["price"][0]) ){
			
			for($i= 0; $i< $count; $i++){
				//echo "Rate id - ";
				$rate_id = ( isset($input["variable_rates"]["rate_id"][$i]) && !empty($input["variable_rates"]["rate_id"][$i]) ? $input["variable_rates"]["rate_id"][$i]:'' );
				//echo "<br>";
				if( isset($rate_id) && !empty($rate_id) ){
					$VariableRate = VariableRate::find($rate_id);
				}else{
					$VariableRate = new VariableRate();
				}
				
				$start_date = "0000-00-00 00:00:00";
				$end_date 	= "0000-00-00 00:00:00";
				$start_time	= "";
				$end_time 	= "";
				
				
				if($input["variable_rates"]["offer_type"][$i] == 3 || $input["variable_rates"]["offer_type"][$i] == 5){
					/*
						*************** Evenings and Every Day ************** 
					*/
					
					$start_time = "00:00:00";
					
					if(!empty($input["variable_rates"]["start_time"][$i])){
											
						$start_time = 	app("App\Http\Controllers\UtilsController")->formatTime($input["variable_rates"]["start_time"][$i],'H:i:s');
						$end_time 	= 	app("App\Http\Controllers\UtilsController")->formatTime($input["variable_rates"]["end_time"][$i],'H:i:s');
						
					}
					
					$start_date = date('Y-m-d ').$start_time; //( Use current date and inputted start time as START )
					$end_date	= "0000-00-00 00:00:00";
					
				}elseif($input["variable_rates"]["offer_type"][$i] == 6 || $input["variable_rates"]["offer_type"][$i] == 7){
					/*
						*************** Special Event and Date Range **************
					*/
					
					$start_date = app("App\Http\Controllers\UtilsController")->formatDate($input["variable_rates"]["start_date"][$i]);
					$end_date = app("App\Http\Controllers\UtilsController")->formatDate($input["variable_rates"]["end_date"][$i]);
					
				}else{
					/*
						*************** Weekends **************
					*/
					
					$start_date = date('Y-m-d H:i:s'); //( Use current date and inputted start time as START )
					$end_date	= "0000-00-00 00:00:00";
				}
				
										
				foreach($variable_rates_columns as $field){
					
					if( isset($input["variable_rates"][$field][$i]) ){
						
						$VariableRate->{$field} = $input["variable_rates"][$field][$i];
						
					}
				};
				
				$VariableRate->start_time 	= $start_time;
				$VariableRate->end_time 	= $end_time;
				$VariableRate->start_date 	= $start_date;
				$VariableRate->end_date 	= $end_date;

				if( isset($rate_id) && !empty($rate_id) ){
					
					/************************ Update Variable Rate **********************/
					
					$VariableRate->update();
				
				}else{
					/************************ Add Variable Rate **********************/
					
					if( isset($lot_id)&&!empty($lot_id) ){
						$VariableRate->lot_id 	= 	$lot_id;
					}
					
					if( isset($userid)&&!empty($userid) ){
						$VariableRate->user_id 		= 	$userid;
					}
					
					$VariableRate->save();
				}
				
			}
			
		}
		
		$redirectURL = '/home/qrCode';
		return Redirect::to($redirectURL)->with( ['success' => 'Data Saved.'] ); 
	}

	public function delete_varaiable_rate(Request $request){
		$input = $request->all();
		$rate_id = $input["rate_id"];

		$VariableRate = VariableRate::find($rate_id);
		$VariableRate->status = 0;
		$VariableRate->update();
		
		echo "Deleted";
		
	}
		public function location_meter_id(Request $request){
		
		$payment_id = $request->all();
		$payments = Payment::distinct()->where('id', '=', $payment_id)->get();
		$data["shipped_meters"] = $payments[0]->shipped_meters;
		$meter_ids = explode(',', $data["shipped_meters"]);
		//echo "<pre>";print_r($data["shipped_meters"])."<br />";
		return $meter_ids;
		}
}
 
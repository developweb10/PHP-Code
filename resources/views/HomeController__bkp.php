<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use Hash; use DB; use Auth; use Input; use DateTime; use View; use URL; use Cookie; use Session;
use App\Lot; use App\Meter; use App\Payment; use App\PaymentItem; use App\User; use App\Referral; use App\ReferralCommission; use App\Country; use App\SmsQueue; use App\PageContent; use App\City; use App\PaymentAccount;

use PDF, Crypt;

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
		//,"email_payments"
		$user_fields = array("name","street","city","country","state","last_name","account_no","phone","transit_no","bank_code");
		//add validation rules
		$rules = [
			'name' => 'required|max:255',
			'street' => 'required|max:255',
			'city' => 'required|max:255',
			'country' => 'required|numeric',
			'state' => 'required|max:255',
			'last_name' => 'required|max:255',
			'account_no' => 'required|max:255',
			'phone' => 'required|max:20',
			'transit_no' => 'required',
			'bank_code' => 'required'
        ];
		
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
			//redirect with success message
			return Redirect::to($redirectURL)->with('success', 'Account details updated.');
		}
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

	public function newmeter(Request $request, $free_meter=0, $free_meter_arr=array()){
		$input = $request->all();
		
		$userid = Auth::user()->id;
		
		//$input["lot_id"], $input["meter_count"]
		$base_price = config("meter_base_price");
		$meter_discount = 0;
		
		
		if( $free_meter ){
			$input["lot_id"] = $free_meter_arr["lot_id"];
			$input["price"] = $free_meter_arr["price"];
			$input["meter_count"] = 1;
			$base_price = 0;
		}
		
		
		//
		if( Auth::user()->referred_by > 0 and !$free_meter ){
			$getSA = Referral::where("user_id",$userid)->where("referral_medium","PROMO CODE")->get();
			if( $getSA->count() && config("promo_code_discount") > 0 ){
				$meter_discount = number_format(($base_price*config("promo_code_discount"))/100,2);
				$base_price = $base_price-$meter_discount;
			}
		}
		
		if(! Lot::where(array('user_id'=>$userid, 'id'=>$input["lot_id"]))->exists() ){
			$messages[] = "Invalid lot selected.";
			return Redirect::to('/home')->withInput($input)->withErrors($messages);
			exit;
		}
		$input["user_id"] = $userid;
		

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
				
				return $this->processMonaris($options);
			
			}else{
				
				return $this->completeNewMeterPayment( $request, 1, $input );
				
			}
		
		}	
	}
	
	public function completeNewMeterPayment( Request $request, $free_meter=0, $free_meter_arr=array() ){

		session_start();
		$input = $request->all();

		if( !$free_meter ){

			if( !isset($_SESSION['response']) || !isset($_SESSION["all_options"]) ) exit("There experienced some problem.");

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
		$input = $request->all();
		$meter_id = $input['meter_id'];
		$m_id = Meter::join("users","users.id","=","meters.user_id")->where( [ 'meters.meter_id'=>$meter_id, "meters.status"=>1, "users.deleted"=>0 ])->select("meters.id","meters.lot_id","meters.expiry")->get();
		
		$message = ["error"=>"","data"=>"","price"=>0.00];
		
		if( $m_id->count() ){
			//check if meter not active 
			//$expiry = app('App\Http\Controllers\UtilsController')->date_difference(date('Y-m-d H:i:s'),$m_id[0]->expiry);
			//if meter expired
			//if( $expiry["hours"] == 0 && $expiry["mins"] == 0 ) {
			
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

	public function deleteMeter(Request $request){
		$input = $request->all();
		$userid = Auth::user()->id;
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

	public function updateMeterGroup(Request $request){
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

	}
	
	
	
	public function pay(Request $request){
		$comm_percent = config('commission');
		$input = $request->all();

		$current_date = app("App\Http\Controllers\UtilsController")->formatDate(date("Y-m-d H:i:s"));
		$add_time = $request->has("add_more_time");
		
		$meter_id = $input["meter_id"];
		$hours = $input["expiry_time"]; //ceil((strtotime($expiry) - strtotime($current_date))/3600);
		$minutes = $hours * 60; 

		$meter = Meter::join("lots","meters.lot_id","=","lots.id")->where("meter_id",$meter_id)->select("lots.price","meters.lot_id","meters.id","meters.user_id","meters.expiry")->get();

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

		$amount = number_format($hours * $meter->price,2);
		
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
		
		return $this->processMonaris($options);
		
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
		$notification_time = 5; //schedule sms before 5 minutes of expiry
		if( !$request->has("pay_item_id") ) $messages[] = "There experienced some problem.";
		if( isset($messages) && count($messages)) return Redirect::to('/my-meter')->withInput($input)->withErrors($messages)->with("opt_in_err",true);
		
		$pay_item = PaymentItem::find($input['pay_item_id']);
		$meter = Meter::join("users","users.id","=","meters.user_id")
			->join("countries","countries.id","=","users.country")
			->where("meters.id",$pay_item->meter_id)->select("meters.meter_id","meters.expiry","meters.user_id","countries.phonecode")->get();
		$meter = $meter[0];
		
		$expiry = $meter->expiry;
		$time_remaining = strtotime($expiry) - strtotime(date("Y-m-d H:i:s"));
		
		if( !$request->has("opted_phone_number") ) $messages[] = "Enter mobile number.";
		if( isset($messages) && count($messages)){
			return Redirect::to('/my-meter')->withInput($input)->withErrors($messages)->with('pay_item_id',$input['pay_item_id'])->with("opt_in_err",true)->with('expiry_time',$time_remaining)->with('meter_id',$meter->meter_id);
		}
		
		$pay_item->opted_phone_number = $input['opted_phone_number'];
		$pay_item->update();
		
		
		$to = $meter->phonecode . $input['opted_phone_number'];

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
		session_start();
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

	
	public function generateSignage( Request $request ){
		
		$inputs = $request->all();
		$vars = array();
		$vars['lot_id'] = $inputs["lot_id"];
		$lot = Lot::find($vars['lot_id']); //->pluck("price");
		$vars['lot_price'] = $lot->price;
		$vars['towing_company_number'] = Auth::user()->towing_company_number;

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
		$folder_name = 'test_folder_'.$vars['meter_ids'][0];
		$dir_path = $_SERVER['DOCUMENT_ROOT']."/".$folder_name;
		 if(is_dir($dir_path)) {
		  rmdir($dir_path);

		 }
		 mkdir($dir_path);
		 foreach ($vars['meter_ids'] as $meter_id_selected) {
			//ob_start();
				// Set the content-type
			//header('Content-Type: image/png');
			
			
			header("Content-Transfer-Encoding: binary");
			header('Content-Description: File Transfer');



			// Create the image
			//$im = imagecreatetruecolor(400, 30);
			$im = imagecreatefromjpeg('https://my-meter.com/images/custom/20160517082012signage_image.jpg');
			// Create some colors
			$white = imagecolorallocate($im, 225, 225, 225);
			$grey = imagecolorallocate($im, 128, 128, 128);
			$black = imagecolorallocate($im, 112, 218, 18); 


			// The text to draw
			$price = $vars['lot_price'];
			// Replace path by your own font path
			$font = '/home/mymeter9/public_html/my_meter_production/trunk/public/dev/public/fonts/ufonts.com_impact.ttf';
			//dirname(__FILE__) . '/arial.ttf';
			// Add some shadow to the text
			//imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);
			$hr = "/hr.";
			// Add the text
			imagettftext($im, 650, 0, 1850, 1310, $black, $font, $price);
			imagettftext($im, 650, 0, 1850, 1310, $black, $font, $price);


			imagettftext($im, 400, 0, 2500, 1910, $black, $font, $hr);
			imagettftext($im, 400, 0, 2500, 1910, $black, $font, $hr);

			imagettftext($im, 100, 0, 2100, 5140, $black, $font, $contact_no);

			//
				// $path_image = $meter_id_selected.'saved-example.jpeg';
				//echo "Meter id -- ".$meter_id_selected."<br>";
				imagettftext($im, 400, 0, 500, 3200, $white, $font, $meter_id_selected );
				//header('Content-Disposition: attachment; filename=meter.png');   
				$getImage = ob_get_contents();
				imagepng($im,$dir_path."/".$meter_id_selected.".png");
				imagedestroy($im);
				
				//ob_end_clean();
		}
		//HomeController::zipDir($dir_path, $_SERVER['DOCUMENT_ROOT'].'/'.$folder_name.'.zip');
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


				//return View::make('landowner.signpage_pdf',compact('vars'));
		//$pdf = PDF::loadView('landowner.signpage_pdf', array('vars'=>$vars));
		
		//return $pdf->stream();
		//return $pdf->download('Parking Sign.pdf');
	}
	
	public function register( Request $request ){
		
		$inputs = $request->all();
		$generatePassword = str_random(6);
		
		$redirectURL = "/";
		
		$rules = array(
			'name' => 'required',
			'email' => 'required|email|unique:users',
		);
		//validate input data
		$validator = Validator::make($inputs, $rules);
		//if validation fails
		if($validator->fails()){
			//get error messages
			$messages = $validator->messages()->all();
			//return with error message
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

			$user_created = User::create([
				'name' => $data['name'],
				'email' => $data['email'],
				'password' => bcrypt($generatePassword),
				'role_id' => $role_id,
				'referred_by' => (!empty($referred_by))?$referred_by:'null',
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
			
			
			mail($to,$subject,$body,$headers);	
			return Redirect::to($redirectURL)->with("register_success","true");
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
		

		$meter_details = PaymentItem::join("payments","payments.id","=","payment_items.payment_id")->join("meters","meters.id","=","payment_items.meter_id")->where("meters.meter_id",$meter_id)->where("payments.payment_type",'meter_hire')->where( function( $obj ) use($inputs) {

				if( !empty($inputs["start_date"]) ){
					$obj->where("payments.created_at",">=",date("Y-m-d H:i:s",strtotime($inputs["start_date"]." 00:00:00")));
				}
				if( !empty($inputs["end_date"]) ){
					$obj->where("payments.created_at","<=",date("Y-m-d H:i:s",strtotime($inputs["end_date"]." 23:59:00")));
				}
				

		} )->groupBy(DB::raw($groupBy))->select(DB::raw("payments.created_at as trans_day, sum(payment_items.hours) as hours, sum(payments.amount) as trans_amount,
count(payments.id) as transactions "))->get();
		
		if( $meter_details->count() ){
			$html = View::make("landowner.meters_by_date",compact("meter_details","inputs"));
			echo $html;
		}else{
			echo '<div class="alert alert-warning">No details found!</div>';
		}
		exit;
	}
	

	public function get_states_by_country(Request $request){
		$inputs = $request->all();
		$country_id = $inputs["country_id"];
		$states = City::where("country_id",$country_id)->groupBy("state_code")->get();
		$html = '<option value="">Select State/Province</option>';
		if( count($states) ){
			foreach($states as $state){
				$html .= '<option value="'.$state->state_code.'">'.$state->state_code.'</option>';
			}
		}
		echo $html;
		exit;
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
		
		$cities = $this->get_cities($inputs["country_id"],$inputs["state_id"]);

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

		$user = PaymentAccount::where("user_id",Auth::user()->id)->get()->first();

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
		echo $html;
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
//print_r($endpoint);
//exit;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
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


	public function save_payment_details(Request $request){
		
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

	}
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
}

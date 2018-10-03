<?php namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Redirect;

use App\Settings;

use Hash; use DB; use Auth; use Input; use DateTime; use View; use URL; use Cookie; use Session; 

use App\Lot; use App\Meter; use App\Payment; use App\PaymentItem; use App\User; use App\Referral; use App\ReferralCommission; use App\Country; use App\SmsQueue; use App\PageContent;
use App\City;

use App\Groups;

use Mail;

use Html;

use Mapper;

use App\LandownersSettings;

class UtilsController extends Controller {



	/**

	 * Display a listing of the resource.

	 *

	 * @return Response

	 */

	public function index()

	{

		//

	}



	/**

	 * Show the form for creating a new resource.

	 *

	 * @return Response

	 */

	public function create()

	{

		//

	}



	/**

	 * Store a newly created resource in storage.

	 *

	 * @return Response

	 */

	public function store()

	{

		//

	}



	/**

	 * Display the specified resource.

	 *

	 * @param  int  $id

	 * @return Response

	 */

	public function show($id)

	{

		//

	}



	/**

	 * Show the form for editing the specified resource.

	 *

	 * @param  int  $id

	 * @return Response

	 */

	public function edit($id)

	{

		//

	}



	/**

	 * Update the specified resource in storage.

	 *

	 * @param  int  $id

	 * @return Response

	 */

	public function update($id)

	{

		//

	}



	/**

	 * Remove the specified resource from storage.

	 *

	 * @param  int  $id

	 * @return Response

	 */

	public function destroy($id)

	{

		//

	}

	

	public function inspections(Request $request,$user_id=0)

	{

		$vars = array();

		$group_id = $request->has("group_id") ? $request->get("group_id"): 0;

		$user_id = base64_decode($user_id);

		

		if(!is_numeric($user_id)){ return Redirect::to('/');exit; }

		

		$user = User::find($user_id); 

		$groups = Lot::where("user_id",$user_id)->get();

		

		$vars["group_id"] = $group_id;



		if( $user->count() ){

			$mymeters = Meter::leftjoin('lots','meters.lot_id','=','lots.id')

				->where(function ($obj) use ($user_id,$group_id) {

					$obj->where("meters.user_id", '=', $user_id);

					/*$obj->where("expiry", '>=', date('Y-m-d H:i:s'));*/

					if( $group_id > 0 ) $obj->where("meters.lot_id", '=', $group_id);

				})->select('meters.*','lots.lot_name')->get()->toArray();

			return view('includes.inspections', compact('user','mymeters','groups','vars'));

		}else{

			return Redirect::to('/');exit;

		}

	}

	

	public function referred($promo_code=0, Request $request){

		return Redirect::to('/')->withCookie( cookie("referred_by",$promo_code,43200) );

	}

	

	public function date_difference($start, $end) {

		$datetime1 = new DateTime($start);

		$datetime2 = new DateTime($end);

		$interval = $datetime1->diff($datetime2);

		$h = $interval->invert ? 0 : $interval->format('%h');

		$m = $interval->invert ? 0 : $interval->format('%i');

		return array('hours'=>$h, 'mins'=>$m);

	}

public function date_difference_expDate($start, $end) {

		$datetime1 = new DateTime($start);

		$datetime2 = new DateTime($end);

		$interval = $datetime1->diff($datetime2);

		$h = $interval->invert ? 0 : $interval->format('%H');

		$m = $interval->invert ? 0 : $interval->format('%i');

		return array('hours'=>$h, 'mins'=>$m);

	}

	

	public function send_sms( $to, $body ){

		

		$body = ( strlen($body) > 140 ) ? substr($body,1,140) : $body;

		$account_key = "M7w7r64QveiZPrA4fEk5x0r762blIW4N";

		

		$result = file_get_contents("http://smsgateway.ca/sendsms.aspx?CellNumber=".$to."&MessageBody=".urlencode($body)."&AccountKey=".$account_key);

		return $result;

	

	}

	

	public function notifiy(){

		$landowner = Auth::user()->id;



		/*$mymeters = DB::select( DB::raw("SELECT TIMESTAMPDIFF(MINUTE, CURRENT_TIMESTAMP, m.expiry) as diff, m.expiry, pi.opted_phone_number, m.meter_id, c.phonecode FROM payment_items pi 

								LEFT JOIN payments p ON pi.payment_id = p.id

								LEFT JOIN meters m ON pi.meter_id = m.id

								LEFT JOIN users u ON m.user_id = u.id

								LEFT JOIN countries c ON u.country = c.id

								WHERE 

									p.payment_type = 'meter_hire' AND pi.opted_phone_number != ''

									AND m.expiry >= CURRENT_TIMESTAMP

									AND m.user_id = ".$landowner->id."

							") );*/

		

		$this->process_sms_queue();

		return Redirect::to(  app('App\Http\Controllers\HomeController')->inspectionsURL( $landowner )  )->with("success","Notifications sent successfuly.") ;exit;

		

	}

	

	private function process_sms_queue(){

		$sms_queue = SmsQueue::all();



		if( $sms_queue->count() ){

			foreach( $sms_queue as $sms ){

				$result = $this->send_sms($sms->to,$sms->sms_body);

					

				//create sms log

				$log = new SmsLog;

				$log->to = $sms->to;

				$log->sms_body = $sms->sms_body;

				$log->sms_status = $result;

				$log->meter_id = $sms->meter_id;

				$log->meter_owner = $sms->meter_owner;

				$log->save();

				

			}

			

			SmsQueue::truncate();

			

		}

	}

	

	public function add_more_time($meter_id, Request $request){

		$meter_id = base64_decode($meter_id);

		$meter = Meter::where('meter_id',$meter_id)->get()->first()->toArray();

		$expiry = $this->date_difference_expDate(date('Y-m-d H:i:s'),$meter['expiry']);



		//if meter expired

		if( $expiry["hours"] == 0 && $expiry["mins"] == 0 ) {

			return Redirect::to('my-meter')->withErrors(array("Your meter has expired. Please return to your vehicle before rebooking this meter. Thanks!"));

			exit;

		}

		return view("add-time", compact('meter'))->with('meter_id',$meter['meter_id']);

	}

	

	public function contact_us( Request $request ){

		

		$redirect_to = "/contact-us";

		if( Auth::user() && Auth::user()->role_id == 2 ){

			$redirect_to = "/sa-home/support";

		}elseif( Auth::user() && Auth::user()->role_id == 3 ){

			$redirect_to = "/home/support";

		}

		

		$input = $request->all();



		if( count($input) ){



			//validate captcha

			 $response= json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfVeCATAAAAADLhuPIHcrf8bs-89jBBe-sEIHJN&response=".$input['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);

			if($response['success'] == false)

			{

				$messages[] = "Captcha not validated.";

				return Redirect::to($redirect_to)->withInput($request->except('_token'))->withErrors($messages);

				exit;

			}

			

			$rules = [

				'contact_name' => 'required|max:255',

				'contact_email' => 'required|email',

				'contact_subject' => 'required',

				'contact_message' => 'required',

			];

			//validate input data

			$validator = Validator::make($input, $rules);

			//if validation fails

			if($validator->fails()){

				//get error messages

				$messages = $validator->messages()->all();

				//return with error message

				return Redirect::to($redirect_to)->withInput($request->except('_token'))->withErrors($messages);

				exit;

			}

			else{

				$subject = $input['contact_subject'];

				$message = "New contact email received. Below are the details: <br><br> Name: " . $input['contact_name'] . " <br> Email: " . $input['contact_email'] . " <br> Message: ".$input['contact_message'];

				

				// Always set content-type when sending HTML email

				$headers = "MIME-Version: 1.0" . "\r\n";

				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				

				$headers .= "X-Mailer: PHP/" . phpversion();

				$headers .= "Content-Transfer-Encoding: 8bit\r\n";

				

				// More headers

				$headers .= 'From: '.$input['contact_name'].' <'.$input['contact_email'].'>' . "\r\n";

				$headers .= 'Reply-To: '.$input['contact_name'].' <'.$input['contact_email'].'>' . "\r\n";

				$headers .= 'Cc: gurpreet.webtek@gmail.com' . "\r\n";

				

				

				//$data['email']

				$to = "info@my-meter.com";

				

				mail($to,$subject,$message,$headers);	

				

				

				//send email

				return Redirect::to($redirect_to)->with("success","Your message has been submitted successfully.");

				exit;

			}

			

			

		}

		return view("includes.contact-us");

	}

	

	public function render_page( $page,$view,$with=array() ){

		$data = PageContent::where("page_name",$page)->get();
		//echo count($data)."<br>";
		if( count($data) > 0 ){

			$data = json_decode($data[0]->page_content);

		}else{

			$fields = array();

			$fields["terms"] = array("page_title","page_content");

			$fields["owner_agreement"] = array("page_title","page_content");

			$fields["sa_agreement"] = array("page_title","page_content");

			$fields["sm_agreement"] = array("page_title","page_content");

			$fields["privacy"] = array("page_title","page_content");

			$fields["faq"] = array("page_title","page_content","questions","answers");

			$fields["home"] = array("section1","section2","section3","section4");



			foreach( $fields[$page] as $key=>$fld ){

				$data->$fld = "";

			}

		}
		
		$cities = City::where("country_id",2)->get();
		//echo "<pre>";
		//print_r($cities); echo "</pre>"; 
		$data->cities = $cities;
		return view($view,compact("data"))->with('vars',$with);

	}

	public function terms(){

		$data = PageContent::where("page_name","owner_agreement")->get();

		$data["lo_data"] = json_decode($data[0]->page_content,true);

		return $this->render_page( "terms","includes.terms", $data );

	}

	public function map(){
		
		$data = PageContent::where("page_name","map")->get();
		$data["map_details"] = json_decode($data[0]->page_content,true);
		
	//	echo "<pre>"; print_r($data["map_details"]); exit;
		//$data["lo_data"] = json_decode($data[0]->page_content,true); 
		Mapper::map(49.895136,-97.1383744,['async' => false,'zoom' =>12,'fullscreenControl' => false,'type' => 'ROADMAP','streetViewControl' => false, 'ui' => false,'cluster' => false,'center' => true, 'eventBeforeLoad' => 'styleMap(map_0);','marker' => false]);
		
		//Mapper::informationWindow("51.4963024", "-113.5099595", '1234567890',['open' => true, 'icon' =>'images/map_icons.png']);
		//Mapper::informationWindow("51.4963024", "-113.5099595", '1234', ['open' => true, 'icon' =>'images/map_icons.png']);
		
		//$payments = payment::where("payment_type","sign_buy")->where('latitude', '!=' , '') ->orderBy('longitude','ASC')->get(); // ->where("shipping_address","Bennett Ave")
		
		$payments = payment::join("lots","payments.lot_id","=","lots.id")->where( [ 'payments.payment_type'=>"sign_buy"])->where('latitude', '!=' , '')->select("payments.*","lots.price")->orderBy('longitude','ASC')->get();
		
		//$content = "";
		$counter = 0;
		
		$old_location ="";
		
		$preserve_locations = array();
		 //echo"<pre>"; print_r($payments ); exit;
		foreach($payments as $payment){  
		
			$longitude = $payment["longitude"];
			$latitude  = $payment["latitude"];
			
			//echo $payment["shipping_address"]."<br>";
			//echo $payment["shipped_meters"]."<br>";
			
			$current_location = $longitude. ' ' .$latitude;
			
			/******************************** If unmatch create a new marker ************************************/
			
			if(!empty( $payment["longitude"] ) && !empty( $payment["longitude"] )){
				
				if(!empty( $old_location ) && $old_location !== $current_location){
					
					//echo "<pre>"; print_r($preserve_locations);
						//exit;
					
					/*********************** Loop on preserve data *********************/
					
					foreach( $preserve_locations as $preserved_content ){
						
						
						
						/********************* Fetch Price for the lot allocated to meter *********************/
						
						if (strpos($preserved_content, ',') !== false) {
							$meter_ids = explode(',',$preserved_content);
							foreach( $meter_ids as $meter_id ){
								
								$meter_price = Meter::leftjoin('lots','meters.lot_id','=','lots.id')

								->where(function ($obj) use ($meter_id) {
				
									$obj->where("meters.meter_id", '=', $meter_id);
				
								})->select('lots.price')->get();
								
								if(isset($meter_price[0])!=''){ 
									$content .= $meter_id ."  $".$meter_price[0]->price." / Hr <br>";
								}
							}
						}else{
							$meter_id = $preserved_content;
							$meter_price = Meter::leftjoin('lots','meters.lot_id','=','lots.id')

								->where(function ($obj) use ($meter_id) {
				
									$obj->where("meters.meter_id", '=', $meter_id);
				
								})->select('lots.*')->where('price', '!=' , '')->get();
								
								if(isset($meter_price[0])!=''){ 
									$content .= $meter_id."  $".$meter_price[0]->price." / Hr. <br>";
								}
						}
						
						
					
					}
					
					Mapper::informationWindow($old_latitude, $old_longitude , $content,['title'=>$counter, 'autoClose' => true,'open' => false, 'icon' =>'images/map_icons.png','eventMouseOver' => 'icon(this, map_0)','eventMouseOut' => 'icon_out(this, map_0)']);
					
					/****************** Reset precerve data *****************/
					unset($preserve_locations);
					$preserve_locations = array();
						
					$counter = $counter + 1;
				}
				
				$old_location =  $current_location;
				$old_longitude = $longitude ;
				$old_latitude = $latitude ;	
				$content = $payment["shipping_address"]."<br><br>";
				$preserve_locations[] = $payment["shipped_meters"];
				
			}
			
					
				
			//echo $payment["shipping_city"]."<br>";
			/*$city = urlencode ($payment['shipping_city']);
			//echo $city;
			if(!empty($city))
			{
				*/
				
				/* Fetch Latitude and longitude 
				$city_details = city::where("city_name",$city)->where("country_id",2)->get();
				
				if( isset($city_details[0])) { 
					if(!empty( $city_details[0]->longitude ) && !empty( $city_details[0]->latitude )){
						$longitude = $city_details[0]->longitude;
						$latitude = $city_details[0]->latitude;
					}
				
					Mapper::marker($latitude, $longitude);
				}
				*/
				//$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$city";
				//$json_data = file_get_contents($url);
				//$result = json_decode($json_data, TRUE);
				//echo "<pre>"; print_r($result); exit;
				//if( isset($result['results'][0]['geometry']['location']['lat']) ){
					
				//}
			//}
		}
		
		// echo "<pre>"; print_r($preserve_locations);
		
		foreach( $preserve_locations as $preserved_content ){
			/********************* Fetch Price for the lot allocated to meter *********************/
			if (strpos($preserved_content, ',') !== false) {
				$meter_ids = explode(',',$preserved_content);
				foreach( $meter_ids as $meter_id ){
					
					$meter_price = Meter::leftjoin('lots','meters.lot_id','=','lots.id')

					->where(function ($obj) use ($meter_id) {
	
						$obj->where("meters.meter_id", '=', $meter_id);
	
					})->select('lots.price')->get();
					
					if(isset($meter_price[0])!=''){ 
						$content .= $meter_id ."  $".$meter_price[0]->price." /Hr <br>";
					}
				}
			}else{
				$meter_id = $preserved_content;
				$meter_price = Meter::leftjoin('lots','meters.lot_id','=','lots.id')

					->where(function ($obj) use ($meter_id) {
	
						$obj->where("meters.meter_id", '=', $meter_id);
	
					})->select('lots.price')->get();
					
					if(isset($meter_price[0])!=''){ 
						$content .= $meter_id."  $".$meter_price[0]->price." / Hr. <br>";
					}
					
			}
					
		}
					
		return view( "includes.map")->with('vars',$data[0]);
		
	}
	
	public function privacy(){

		return $this->render_page( "privacy","includes.privacy" );

	}

	

	public function owner_agreement(){

		return $this->render_page( "owner_agreement","includes.owner-agreement" );

	}

	public function sa_agreement(){

		return $this->render_page( "sa_agreement","includes.sa-agreement" );

	}

	public function sm_agreement(){

		return $this->render_page( "sm_agreement","includes.sm-agreement" );

	}


	public function faq(){

		$data = PageContent::where("page_name","faq_landowner")->get();

		$data = json_decode($data[0]->page_content);

		return $this->render_page( "faq","includes.faq",$data );

	}



	public function faq_sa(){

		return $this->render_page( "faq_sa","includes.faq" );

	}



	public function faq_landowner(){

		return $this->render_page( "faq_landowner","includes.faq" );

	}

	

	public function landing_home(){

		$success = Session::has('success') ? Session::get('success') : '';

		return $this->render_page( "home","welcome",array('success'=>$success) );

	}

	

	public function add_time_url( $meter_id ){

		return URL::to('at')."/". base64_encode($meter_id);

	}

	

	public function inspectionsURL($userid){

		return URL::to('/')."/inspections/".base64_encode($userid);

	}

	

	public function formatDate($date,$format='Y-m-d H:i:s'){

		return (new DateTime($date))->format($format);

	}



	public function formatTime($date,$format='H:i:s'){

		return (new DateTime($date))->format($format);

	}

	

	

	public function affilliateURL($promo_code){

		return URL::to('/')."/referred/".$promo_code;

	}

	

	public function html_decode($text){
		$text = str_replace("[[get_form_data]]","<a href='#' data-toggle='modal' data-target='#newLotModal'>",$text);
		$text = str_replace("[[/get_form_data]]","</a>",$text);
		echo html_entity_decode($text);

	}

	

	public function completePayment( Request $request ){

		
		
		session_start();
		$settings = Settings::orderBy("id","desc")->first();
		$input = $request->all();
		if( !isset($_SESSION['response']) || !isset($_SESSION["all_options"]) ) exit("Sorry, We're experiencing technical difficulties. Please try again later.");



		if( Input::has("transaction") ){

			

			$response = $_SESSION['response'];

			$all_options = $_SESSION["all_options"];



			unset($_SESSION["request"]);

			unset($_SESSION["all_options"]);

		}

		

		$redirect_url = isset($all_options["redirect_url"])?$all_options["redirect_url"]:"/my-meter";

		//echo "<pre>"; print_r($_SESSION);
		//echo "<pre>"; print_r($response); echo "</pre>"; exit();//echo "<pre>"; print_r($all_options); exit();
		
		
		if( isset($response["Message"]) && ( strtolower($response["Message"]) ==  "approved" || starts_with(strtolower($response["Message"]),"approved") )){

			

			$input = $all_options["input"];

			$meter = $all_options["meter"];



			$add_time = $all_options["add_time"];

			$expiry_meter = $all_options["expiry_meter"];

			$amount = $all_options["amount"];

			$hours = $input["expiry_time"];

			

			$userid = $all_options["cust_id"];

			

			$trans_id = $response["TransID"];

			$trans_status = "approved";

			$trans_response = serialize($response);

			//create payment

			

			$meter_ = Meter::find($meter->id);

			/* If group allocated */
			$group_id = $meter_->group_id;
			
			/* Client will get 70% of amount if have not allocated any group */ 
			if($group_id != 0){
				$Group = Groups::find($group_id);
				$landowner_commission = $Group->commission;
			}else{
				$landowner_commission = config("landowner_commission");
			}
			

			$remaining_hours = ( $add_time ) ? $meter_->hours + $hours : $hours;

			$meter_->hours = $remaining_hours;

			$meter_->expiry = $expiry_meter;

			//$meter_->hour_price = $meter->price;
			//$meter_->hour_price = $meter->hour_price;
			
			$meter_->update();

			

			//userid for visiting customer/driver

			//$userid  =999999999;

			 

			$pay = new Payment;

			$pay->user_id = $userid;

			$pay->lot_id = $meter->lot_id;

			$pay->amount = $amount;

			$pay->trans_id = $trans_id;

			$pay->trans_status = $trans_status;

			$pay->trans_response = $trans_response;

			$pay->payment_type = $all_options["pay_type"];

			$pay->save();

			

			$payment_id = $pay->id;

			

			//create payment items

			$pay_item = new PaymentItem;

			$pay_item->payment_id = $payment_id;

			$pay_item->user_id = $userid;

			$pay_item->lot_id = $meter->lot_id;

			$pay_item->meter_id = $meter->id;

			$pay_item->hours = $hours;

			$pay_item->hour_price = $meter->price;

			$pay_item->total_price = $amount;
			
			/* Payment commission may vary. It depends upon the group's commission at the time when hiring meter. In future group can be changed so we are keeping track of commission for each transaction. */
			$pay_item->payment_commission = $landowner_commission;

			$pay_item->save();

			

			$pay_item_id = $pay_item->id;

			

			//update balance for metere owner
			 
			$meterOwner = User::find($meter->user_id);

			//$meterOwner->balance = $meterOwner->balance + $amount;
			
			/* Client will get 70% of amount if have not allocated any group */ 
			$landowner_commission = config("landowner_commission");
			$landowner_amount = ($landowner_commission * $amount)/100;

			$meterOwner->balance = $meterOwner->balance + $landowner_amount;

			$meterOwner->update();

			//echo "<pre>"; print_r($meterOwner); exit();

			//set commissions if this meter owner is a referral of other user

			$getReferral = Referral::where("user_id",$meter->user_id)->get();
			
			//echo "get refeeral where userid = 42 (gurp)<br>";
			
			//print_r($getReferral);


			if( $getReferral->count() ){

				$getReferral = $getReferral[0];

				$getSAReferral = Referral::where("user_id",$getReferral->referred_by)->get();                         // Commission for Sale Associate from referral
				
				
				//$manager_commission = config('sm_commission'); //percent

				
				$getUserCommission = User::where("id",$getReferral->referred_by)->get();							  // Commission for Sale Associate from users
				
				/*Set commission for user if exsits in user table*/
				if($getUserCommission[0]->commission != 0.00)
				{
					$sa_commision = $getUserCommission[0]->commission;   /*user table commission*/
				}
				else
				{
					$sa_commision = $settings["sa_commission"];          /* Global commission set by admin for Sale Associates*/
				}
				$comm = number_format(($amount * $sa_commision)/100,2);
				//echo  "Commision of SA (associate1) -- ".$comm."<br>";

				$if_manager = User::find($getReferral->referred_by);
								
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
				
				$ref_comm->save();

				//update referred by user balance with commission

				$referred_by = User::find($getReferral->referred_by);

				$referred_by->balance = $referred_by->balance + $comm;

				$referred_by->update();
				//echo "REfered BY -- <br>";
				//print_r($referred_by);
				
				if( $if_manager->created_by > 0 ){
					$manager = User::find($if_manager->created_by);
					$manager->balance = $manager->balance + $manager_comm;
					//print_r($manager);
					$manager->update();
				}



			}

			$send_email = 0;
			$meterOwner_email = $meterOwner->email;
			$to = $meterOwner_email;
			
			$Lo_Settings = new LandownersSettings();
			$settings_select = $Lo_Settings->settings_select($meterOwner->id);
			
			if( isset($settings_select) && count($settings_select)>0 ){
				/* Use landowner settings if landowner has set his own settings */
				if(isset($settings_select['email_feature']) && $settings_select['email_feature'] == 1){
					$send_email = 1;
					
					if(isset($settings_select['recipient_email']) && !empty($settings_select['recipient_email'])){
						$to = $settings_select['recipient_email'];
					}
				}
			}else{
				/* Use admin settings if landowner has not set his own settings */
				if(isset($settings["email_feature"]) && $settings["email_feature"] == 1){
					$send_email = 1;
				}
			}
			/********* expire time **********/
			$time_hours = $hours;
			if($time_hours == '.5'){
				$time_hours = "30mins";
			}elseif($time_hours == '1'){
				$time_hours = "1 hour";
			}else{
				$time_hours = $time_hours." hours";
			}
			/******************* meterOwner send mail **********************/
			
			if($send_email == 1){
				$data = PageContent::where("page_name","automated_emails")->get();
				
				$data = json_decode($data[0]->page_content,true);
				
				$driver_park_id = $input['meter_id'];
				
				$meter_user_id= $meter_->user_id;
				//groups name
				$lot_location = Lot::find($meter->lot_id);

				$subject =$data['hire_meter']['subject'];  // Mail Subject
				
				$messages = $data['hire_meter']['body'];   // Mail massage 
				
				$messages  = str_ireplace("[[user_name]]",$meterOwner->name,$messages);		//Landowner Name
				$messages = str_ireplace("[[meter_id]]",$meter_->meter_id,$messages);		//Landowner Meter Id
				$messages = str_ireplace("[[meter_hour]]"," ".$time_hours,$messages);		//Expire Time
				$messages = str_ireplace("[[meter_paid]]",' $'.$amount,$messages);			//Paid
				$messages = str_ireplace("[[location]]",$lot_location->lot_name,$messages);	//Gropus Name
				$message = $messages;
				
				// Always set content-type when sending HTML email
	
				$headers = "MIME-Version: 1.0" . "\r\n";
	
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
				$headers .= "X-Mailer: PHP/" . phpversion();
	
				$headers .= "Content-Transfer-Encoding: 8bit\r\n";

				// More headers
	
				$headers .= 'From: My-meter.com <info@my-meter.com>' . "\r\n";
	
				$headers .= 'Reply-To: My-meter.com <info@my-meter.com>' . "\r\n";
	
				mail($to,$subject,$message,$headers);
			}
				
			/******************* meterOwner send mail end **********************/
			
			/******************* meterOwner send sms start **********************/
			
			$send_sms = 0;
			
			$recipient_mobile ="";
						
			if( isset($settings_select) && count($settings_select)>0 ){
				/* Use landowner settings if landowner has set his own settings */
				if(isset($settings_select['sms_feature']) && $settings_select['sms_feature'] == 1){
					$send_sms = 1;
					
					if(isset($settings_select['recipient_mobile']) && !empty($settings_select['recipient_mobile'])){
						$recipient_mobile = $settings_select['recipient_mobile'];
					}
				}
			}else{
				/* Use admin settings if landowner has not set his own settings */
				if(isset($settings["sms_feature"]) && $settings["sms_feature"] == 1){
					$send_sms = 1;
				}
			}
			if($send_sms == 1){
				$sms_massage = strip_tags($message);
				
				if(!empty($recipient_mobile)){
					$result = UtilsController::send_sms($recipient_mobile,$sms_massage);
				}	
			}
			/******************* meterOwner send sms end **********************/	

			/* Maintain sessions */

			$_SESSION["amount"] = $amount;
			$_SESSION["time_hours"] = $time_hours;
			$_SESSION["hours"] = $hours;
			$_SESSION["expiry_time"] = $remaining_hours*60*60;
			$_SESSION["payment_id"] = $payment_id;
			$_SESSION["pay_item_id"] = $pay_item_id;
			$_SESSION["meter_id"] = $meter_->meter_id;
			$_SESSION['expiry_meter'] = $expiry_meter;
			
			
			if( $add_time ){

				$msg =  "Time added to your meter.";

				$_SESSION["success"] = $msg;

				return Redirect::to('/my-meter')->with('success', $msg)->with('expiry_time',$remaining_hours*60*60)->with('meter_id',$meter_->meter_id);		

			}else{

				$msg =  "Meter Started.";
				$_SESSION["success"] = $msg;
				return Redirect::to('/my-meter')->with('success', $msg)->with('amount',$amount)->with('time_hours',$time_hours)->with('hours',$hours)->with('pay_item_id',$pay_item_id)->with('expiry_time',$remaining_hours*60*60)->with('meter_id',$meter_->meter_id);		

			}

		

		}

		else{

			

			$messages[] = $response["Message"];
			
			//echo "<pre>"; print_r($messages); echo "</pre>"; exit();
			
			if( isset($response["Message"]) && ( starts_with(strtolower($response["Message"]),"declined") || strpos( strtolower($response["Message"]), "card problem" ) !== false  )){
				$messages[0] = "Invalid Card Number";
				$input = isset($all_options["input"])?$all_options["input"]:array();
				
				$input["messages"] = $messages;
				
				//echo "<pre>"; print_r($input); echo "</pre>"; exit();

				return Redirect::to("/")->withInput($input);
				
			}else{

				$input = isset($all_options["input"])?$all_options["input"]:array();

				return Redirect::to("/")->withInput($input)->withErrors($messages);
			}

		}

	}


public function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'E7C8836AD32C688444E3928F69F046715F8B33AF2E52A6E67A626B586DE8024E';
    $secret_iv = 'B9F128D827203729BE52A834CC0890B7';

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}


}


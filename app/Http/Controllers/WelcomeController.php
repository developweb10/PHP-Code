<?php namespace App\Http\Controllers;  

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

//use App\Http\Controllers\HomeController;
use Mail;
use App\PageContent;
use Crypt;
use Session;

use App\Settings;
use App\Referral; use App\ReferralCommission; 

use App\City;
use Input;
use URL;
use DB;
 use App\Meter;
 use App\Payment; use App\PaymentItem; use App\User; use App\towing_companies; 
 
 use Socialite;
 
 
use QrCode;
 
class WelcomeController extends Controller {



	/*

	|--------------------------------------------------------------------------

	
	| Welcome Controller

	|--------------------------------------------------------------------------

	|

	| This controller renders the "marketing page" for the application and

	| is configured to only allow guests. Like most of the other sample

	| controllers, you are free to modify or remove it as you desire.

	|

	*/



	/**

	 * Create a new controller instance.

	 *

	 * @return void

	 */

	public function __construct()

	{
		
		//echo phpinfo(); ini_set('memory_limit','1024M'); echo phpinfo(); exit;
		
		if($_SERVER['REMOTE_ADDR'] == '203.134.193.44') 
		{	
			
			/*$payments = payment::where("payment_type","sign_buy")->offset(209)->limit(5)->get();
			
			foreach($payments as $payment){
				$city_detail = urlencode( $payment["shipping_address"]." ".$payment["shipping_city"] );
				$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$city_detail";
				$json_data = file_get_contents($url);
				$result = json_decode($json_data, TRUE);
				// INsert
				if( isset($result['results'][0]['geometry']['location']['lat']) && !empty( $result['results'][0]['geometry']['location']['lat'] ) ){
					$lat = $result['results'][0]['geometry']['location']['lat'];
					$lng = $result['results'][0]['geometry']['location']['lng'];
					$data = DB::table('payments')->where('id',$payment["id"])->update( ['latitude' => $lat , 'longitude' => $lng ] );
				}
			}*/
			
			/*$cities = DB::select(DB::raw("select cities.city_name , cities.state_code , cities.id from `cities` where cities.country_id = 2 limit 213,20"));  
//			
				$name = '';
			foreach($cities as $city){
				$city_id = $city->id;
				$city_name = urlencode($city->city_name);
				$url = "http://maps.googleapis.com/maps/api/geocode/json?address=$city_name";
				$json_data = file_get_contents($url);
				if( $city_name == "acme" ){
					//mail('jass.webtech@gmail.com','test',$json_data);
				}
				$name .= $city_name."<br>";
				$result = json_decode($json_data, TRUE);
				
				// INsert
				if( isset($result['results'][0]['geometry']['location']['lat']) && !empty( $result['results'][0]['geometry']['location']['lat'] ) ){
					$lat = $result['results'][0]['geometry']['location']['lat'];
					$lng = $result['results'][0]['geometry']['location']['lng'];
					$data = DB::table('cities')->where('id',$city_id)->update( ['latitude' => $lat , 'longitude' => $lng ] );
				}
				
			}
			
			mail('jass.webtech@gmail.com','city names',$name);*/
			
			//QrCode::format('png')->size(500)->generate('https://my-meter.com/dev/public/meterID/100389');
			//QrCode::generate('test1','images/qrcodes/images.png'); 
			//exit;
			
			//$meters = new Meter();
			//$all_meters = $meters->all();
			//echo "<pre>"; print_r($all_meters); echo "</pre>";
			
			/*foreach($all_meters as $meter){
				echo $meter->meter_id."<br>";
				QrCode::format('png')->size(500)->generate('https://my-meter.com/dev/public/meterID/'.$meter->meter_id);
				QrCode::generate('https://my-meter.com/dev/public/meterID/'.$meter->meter_id,'images/qrcodes/'.$meter->meter_id.'.png'); 
			}
			exit;*/
			/*$data = '';
			$users = DB::table('users')->get();
			//foreach($users as $user){
			for($i=1;$i<=500;$i++)
			{
				//echo $user_id = $user->id;
				echo $i." : ";
				$current_timestamp = time();
				
				$current = date('Y-m-d H:i:s');
				
				echo $current_timestamp." : ".uniqid()." : ".date('Y-m-d H:i:s')." : ".microtime(TRUE)." : ".strtotime($current)."<br>";
				
				//$data .= DB::table('users')->where('id',$user_id)->whereIn("users.role_id",[2,3,5])->update( ['security_answer' => uniqid()] );
				//$data .= "<br>";
			}
			
			//echo $data;
			exit;*/
		} 
		
//$cities_no_found = array();
//		$cities = DB::select(DB::raw("select cities.city_name , cities.state_code , cities.id from `cities` where cities.country_id = 2 and cities.id NOT IN ( SELECT distinct(cities.id) FROM `cities` join `towing_companies` where cities.id = towing_companies.city_id ) limit 530,20")); 
//		
//		foreach($cities as $city){
//			echo "<hr/><hr/>".$city->city_name."<br>"; 
//			$path ='https://maps.googleapis.com/maps/api/place/textsearch/json?query=towing+companies+in+'.urlencode($city->city_name).'&key=AIzaSyCLv-b_3t1ps0zVJuJ-GrPoL6TObnJKiuQ';
//			$out =  $this->download_page($path);
//			//echo "Out without decode -- ";
//			//print_r($out);
//			
//			$data = json_decode($out, true);
//			//echo "<pre>"; print_r($data); echo "</pre>";
//			//continue;
//			$total_result = count($data["results"]);
//		
//			if(is_array($data["results"]) && count($data["results"])>0){
//				foreach($data["results"] as $key=>$result){
//					echo "<pre>"; print_r($result["name"]); echo "</pre>";
//					$fetched_data[$key]["company"] = $result["name"];
//					$contacts =  $this->download_page("https://maps.googleapis.com/maps/api/place/details/json?reference=".$result["reference"]."&key=AIzaSyBwpbrKvoACqEhpsfOo6a-pVJBDWTiu0d4");
//					$contacts_ = json_decode($contacts, true);
//					
//					if(!empty($contacts_["result"]["formatted_phone_number"])){
//						echo "Contact : ".$contacts_["result"]["formatted_phone_number"]."<br>";
//						$fetched_data[$key]["contact"] = $contacts_["result"]["formatted_phone_number"];
//					}else{
//						$fetched_data[$key]["contact"] = "";
//					}
//					
//					// INsert
//					$towing_companies_count = DB::select(DB::raw("select count(*) as count from towing_companies where towing_companies.company = '".addslashes($result["name"])."'"));
//					echo "towing_companies_count : ".$towing_companies_count[0]->count;
//					if($towing_companies_count[0]->count > 0){
//					}else{
//						echo '<pre>';
//							print_R($fetched_data[$key]);
//						echo '</pre>';
//						DB::table('towing_companies')->insert(
//							['city_name' => $city->city_name, 
//							 'city_id' => $city->id , 
//							 'company' => $fetched_data[$key]["company"] ,
//							 'contact_no' => $fetched_data[$key]["contact"] , 
//							 'state_code' => $city->state_code , 
//							 'country_id' => 2 
//							]
//						);
//					}
//				}
//			}else
//			{	
//				if(!in_array($city->city_name,$cities_no_found))
//				$cities_no_found[] = $city->city_name;
//				echo "No data returned by API for".$city->city_name;
//			}
//		}
//		print_r($cities_no_found);
		//exit();
		
		$this->middleware('guest');
		
	}
public function download_page($path){		
	return file_get_contents($path);
		/*$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$path);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		
		$retValue = curl_exec($ch);		
	
		curl_close($ch);
		return $retValue;
*/		
	}
public function transpay_curl($data = array()){

		if( !count($data) ) return;




	$token = "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxBdXRoZW50aWNhdGlvbj4NCiAgPElkPjM2YjliYmJkLWFlN2UtNGZjNy04NzVkLTczYmQ5YmEzNGM2MzwvSWQ+DQogIDxVc2VyTmFtZT5tSnlTY004NjR3cDgwY0E2dDIrV1JVekVydDFXWnFmTHBnZjRtTU1Bd2dvdkVwRVNkMkZtY2I5T2pvODNBdXI3SDBrYXJNVlBZeVNha0c4d1k0MUQvSDhXdXd5cTdHZm1ha0xYSnM2aXhub3dkMFRLdktoM2UyaFFhNVdBS1VIU1dpTW9hd2FXUEhDUWlmcEJxNG9YVEdwSlM2QWhWUFIvUFAxazdpMXNXdU09PC9Vc2VyTmFtZT4NCiAgPFBhc3N3b3JkPml5aGV6MWl3M1RPS1daT2hjOE0yQzF4NHRHek5wbDBvaWxKZUh0bDNLRUtTQzZIdUNXdkd2MERDK25QbGNTbFBCTmxoZXNHTDBrUVMxeDJYK0hiMGQ3TmFwVWEwbEJEbmpUZzA1VGl6S0dFbGdGSlptbzB2dTZWK1RYbXgvb0R4U1ZLb3VVNys5eStUdmhLSm9CVlZ5UXB1YVVLNkxHMG02N2R2d0FObmF0VT08L1Bhc3N3b3JkPg0KICA8QnJhbmNoSWQ+cEROdm5Kb2g4d3R3Yml0OU9lVndNTlh1WlNoTDk4L1lPNnNtNkg2NWthVlF2YTBjSC8xb01mQy9rYTBuRnpoMU1ENyt0eDM0WCtiNHBsdndLZTN3amIxR0VSU1dlZ0ZkU0xiU0F5b3grVVVlU2lSYjBRMnJGUFU2WUdFZjI5enFuMUdkT2FZZFp4cUljdFgrb21OanRkWXQ5cm1MRldCWEZMdEZMWDVQalF3PTwvQnJhbmNoSWQ+DQo8L0F1dGhlbnRpY2F0aW9uPg==";

	$endpoint = "https://demo-api.transfast.net";

	//create transaction
	//echo "end point -- " . $endpoint .= $data['endpoint'];

	//echo "<pre>"; print_r($request_array);
	//$request_data = json_encode($request_array);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $endpoint);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 0);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
	curl_setopt($ch, CURLOPT_TIMEOUT, '60');
	//curl_setopt($ch,	CURLOPT_USERAGENT,$gArray['API_VERSION']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	$headers = array();
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Credentials '.$token;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$response=curl_exec ($ch);
	curl_close($ch);

	//$response_arr = json_decode($response,true);


		/*$token = "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxBdXRoZW50aWNhdGlvbj4NCiAgPElkPjI4Yzk1OTk4LTRhZTEtNGUxNi05ZWM5LTU5NTlhYzMzNzY1MTwvSWQ+DQogIDxVc2VyTmFtZT40L2FHM3Iwd0JUT1lzeXFsYXRDT0hWSTErTm1DN1VqSVZZRzFPUnc1M2VKd2xPZFowVTZEcjJxNmt5QTBYSjRLZmdIYUsvbnROaXE4a3AzN245VUpaNFNQYXBlM2szNDU0TmxFRzdjY2xlWFBvVjl0SFljWTVIN1RMNllkVGh0bXlHN2lUYlYyNlk5TjlaSHUwZUpQVEtwaVphc2tWMi9wZzZqRFZKZmZ0MXM9PC9Vc2VyTmFtZT4NCiAgPFBhc3N3b3JkPk1kVFpZR2QrMGUyOEdkaHRZQWlvRlAxSXNGVDJyNVRWQ252Y1ZUL29SUVJBdVZBckJab001UTRGWWhWaWZhSk82NkMvdXN2NmlXMWpuTzJuVmJiZ3N4Rit6NFpHWDM5M2hCWVFyMFBJUWgzdHc2VjNYZ2RCRFRKRm54VFRyTXJNZ1ZKVWRScFpuZWZObWhINU42MEhLU28zc2R4RG9SRUZ5K0hmRitQMHlhOD08L1Bhc3N3b3JkPg0KICA8QnJhbmNoSWQ+Z0Zkb3hmcXorWGp4RFpIcDlJUk5rQlVhL2UwTlkxQTN5azFTZldCYTBabjMrVVBPd2J2YWtFZlg2NGhiYkR6bVZCdjUvMmZiVUhoa1lyOWY2alp2U2lXODBwTHRPTDgxY2RwNXRhcXZLemx0VU5oaE11OE1zdWU0eC9HMld3MmY3QVRhL3dqTGJjZEZCSGJTMzI2ci9pMXVsU3JMMFNTb0lQN2lyQ1JmY204PTwvQnJhbmNoSWQ+DQo8L0F1dGhlbnRpY2F0aW9uPg==";

		echo "end point". $endpoint = "https://demo-api.transfast.net". $data['endpoint'];
		//echo "TEST";
	//dd($endpoint); exit();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, '60');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		//$headers = array();
		$headers[] = 'Accept: application/json';
				$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: Credentials '.$token;
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		if(curl_error($ch))
		{
			echo "YES";
			echo 'error:' . curl_error($ch);
		}

		$response=curl_exec ($ch);
		dd($response);
		curl_close($ch);*/

		return  json_decode($response,true);
	}

	/**

	 * Show the application welcome screen to the user.

	 *

	 * @return Response

	 */

	public function index()

	{
		

		$success = Session::has('success') ? Session::get('success') : '';

		return app('App\Http\Controllers\UtilsController')->render_page( "home","welcome",array('success'=>$success) );

	}

	public function sampleMeter()

	{

		return view('includes.sampleMeter');

	}

	public function myMeter(Request $request , $id=''){
		
		
		$oldInputs = $request->all();
		
		$meter_id = '';
		
		
		$data = PageContent::where("page_name","terms")->get();

		if( count($data) > 0 ){

			$data = json_decode($data[0]->page_content);

		}else{

			$data->page_title = "";

			$data->page_content = "";

		}
		if(!empty($id)){
			$data->meter_id = $id;
		}
		session_start();
		/* if count down is counting and expiry time is set */
		if(isset($_SESSION["expiry_time"]) && !empty($_SESSION['expiry_meter'])){
			/* Update the count down */
			$meter = Meter::where('meter_id',$_SESSION['meter_id'])->get()->first()->toArray();
			if($meter["expiry"] != "0000-00-00 00:00:00"){
				/* remaining time = expiry time - current time */
				$expiry = app('App\Http\Controllers\UtilsController')->date_difference_expDate(date('Y-m-d H:i:s'),$meter['expiry']);
				if( $expiry["hours"] == 0 && $expiry["mins"] == 0 ) {
				}else{
					$seconds = ($expiry["hours"]*60*60)+($expiry["mins"]*60);
					$remaining_time = $_SESSION["expiry_time"] - $seconds;
					$_SESSION["expiry_time"] = $seconds;
					
				}
			}
		}
		
		return view('includes.my-meter',compact('data'));

	}

	

	public function rent_out_parking_space(){ 

		$success = Session::has('success') ? Session::get('success') : '';

		return app('App\Http\Controllers\UtilsController')->render_page( "home","rent-out-parking-space",array('success'=>$success) );

	}
	public function unauth_completeNewMeterPayment(Request $request, $free_meter=0, $free_meter_arr=array()){
		
		session_start();
		
		
		$input = $request->all();
		$settings = Settings::orderBy("id","desc")->first();
		
		
		/*echo "free_meter -- ";
		print_r($free_meter );*/
		
		
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
		
		$user_detail = User::where("id",$all_options['cust_id'])->get();
			
		$user_email = $user_detail[0]["email"];
		$password = $all_options["input"]["password"];
		$user_name = $user_detail[0]["name"];
		
		/*if(){
			refered_by
		}*/
		/*echo "<pre>"; print_r($all_options); echo "</pre>";
		echo "<pre>"; print_r($user_detail[0]); echo "</pre>";
		
		
		echo "<pre>"; print_r($response); echo "</pre>"; 
		
		
		exit();
		*/
		
		if( isset($response["Message"]) && ( strtolower($response["Message"]) ==  "approved" || starts_with(strtolower($response["Message"]),"approved") )){
				
			$input = $all_options["input"];
			
			//$total_amount = $all_options["amount"];
			$base_price	= $all_options["base_price"];
			
			$total_amount = Session::has('amount') ? Session::get('amount'):$all_options["amount"];
			$sub_amount = Session::has('sub_amount') ? Session::get('sub_amount'):$base_price;
			$m_count = Session::has('meter_count') ? Session::get('meter_count'):$input["parking_meter_count"];
			
			$userid = $all_options["cust_id"];
	
			$trans_id = $response["TransID"];
			$trans_status = "approved";
			$trans_response = serialize($response);
			$meter_IDs = array();
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
				
			$meter_ids = implode(',',$meter_IDs);
			$data = array();
			$data["meter_id"]=$meter_ids;
			//$data["meter_id"][1]=100457;
			$data["lot_id"]=$input["lot_id"];
			$data["towing_contact"] = $all_options["input"]["towing_contact"];
			
			
				
			$result = (new HomeController);
			$gh = $result->generateSignage($request,$data);
			//dd($input);
			if(isset($input["towing_companies"]))
			$towing_detail = towing_companies::where('id',$input["towing_companies"])->get();

			//sleep(25);
				
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
			
			$pay->towing_contact_no = $input["towing_contact"];

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
				//echo  "Commision Amount of SA (".$getReferral->referred_by.") -- ".$sa_commision."<br>";
				
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
			
			$subject = isset($get_content['registration_order']['subject']) ? $get_content['registration_order']['subject']  : "Your account has been created!";
			$body = isset($get_content['registration_order']['body']) ? $get_content['registration_order']['body']  : "";
			
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
			$msg = "success";
			//echo $gh;
			
				
			
			
			if(mail($to,$subject,$body,$headers)){ //   'shaveta.webtech@gmail.com'
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
							$message->to("gurpreet.webtek@gmail.com", ""); //     "shaveta.webtech@gmail.com" matt@cibinel.com
							$message->subject('My-Meter - Order # '.$mail_data["pay_id"]); 
							$message->setBody($mail_data["mail_content"], 'text/html');
							$message->attach("https://my-meter.com/dev/public/My-Meter Signage".$mail_data["lot_id"].".zip",array('as' => 'My-Meter Signage.zip','mime' => 'application/octet-stream'));
							
					
						}); 
						
						
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
							 
				}
				//mail('trannum.webtech@gmail.com','My-Meter - Order # '.$payment_id,$admin_mail_body,$headers); // matt@cibinel.com
			}
			
			if( !$free_meter ){
				$msg = $input["parking_meter_count"]." Meter". ($input["parking_meter_count"]>1?'s':'') ." created.";
			}else{

				$user = User::find($userid);
				$user->free_meter_assigned = 1;
				$user->update();
				
				return true;

			}
			
			
			//////////////////////////////// Mail to custimer and admin //////////////////////////////////////////
			
			
			
			
			//////////////////////////////////////////////////////////////////////////
			
			/* Set the amount in session For facebook pixel conversion for "Purchase" event */ 
			
			return Redirect::to("/")->with('register_order_success', $msg)->with('amount',$total_amount);
			//return Redirect::to($redirect_url)->with('success', $msg);
		
		}
		else{
			$messages[] = $response["Message"];
			return Redirect::to($redirect_url)->withInput($input)->withErrors($messages);
		}	
	
	}
	
	public function applepay(){
		return view('applepay');
	}
	public function apple_pay_do(Request $request){
		//home/mymeter9/public_html/my_meter_production/trunk/public/dev/public
		//echo "Tsey";
		
		$input = $request->all();
		 
		 if(isset($input['u'])){
		  $validation_url = $input['u'];  
	   }else{
		   $validation_url ="https://apple-pay-gateway-cert.apple.com/paymentservices/startSession";
	   }
		define('PRODUCTION_CERTIFICATE_KEY', '/home/mymeter9/public_html/my_meter_production/trunk/Applepay.key.pem');
		define('PRODUCTION_CERTIFICATE_PATH', '/home/mymeter9/public_html/my_meter_production/trunk/Applepay.crt.pem');
		//echo PRODUCTION_CERTIFICATE_PATH;
		// This is the password you were asked to create in terminal when you extracted ApplePay.key.pem
		define('PRODUCTION_CERTIFICATE_KEY_PASS', 'pb11ac9655'); 
  
		//NB check $validation_url is apple.com  
		// create a new cURL resource  
		$ch = curl_init();  
		  //echo getcwd() . "/client.pem";
		$data = '{"merchantIdentifier":"merchant.com.my-meter", "domainName":"my-meter.com", "displayName":"My Meter"}';  
		  
		curl_setopt($ch, CURLOPT_URL, $validation_url);  
		
		curl_setopt($ch, CURLOPT_SSLCERT, PRODUCTION_CERTIFICATE_PATH);
		curl_setopt($ch, CURLOPT_SSLKEY, PRODUCTION_CERTIFICATE_KEY);
		curl_setopt($ch, CURLOPT_SSLKEYPASSWD, PRODUCTION_CERTIFICATE_KEY_PASS);
	
	
		//curl_setopt($ch, CURLOPT_SSLCERT, getcwd() . "/client.pem");   // ssl client certificate
		//curl_setopt($ch, CURLOPT_SSLKEY, getcwd() . "/keyout.pem");    // private key for ssl client cerificate
		//curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
		//curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
		//curl_setopt($ch, CURLOPT_KEYPASSWD, "s3cret");
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_POST, 1);  
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
		  
		$version = curl_version();
		//print_r($version);
		
		if(curl_exec($ch) === false)  
		{  
			echo 'Curl error: ' . curl_error($ch);  
		} else{
			//echo "Curl success : ".curl_exec($ch);
		}
		  
		  
		// close cURL resource, and free up system resources  
		curl_close($ch); 
	}
}


<?php namespace App\Http\Controllers;







use Illuminate\Http\Request;



use App\Http\Controllers\Controller;



use Illuminate\Support\Facades\Validator;



use Illuminate\Support\Facades\Redirect;







use Hash; use DB; use Auth; use Input; use DateTime; use View; use URL; use Cookie; use App;



use App\Lot; use App\Meter; use App\Payment; use App\PaymentItem; use App\User; use App\Referral; use App\ReferralCommission; use App\Country; use App\SmsQueue;  use App\Role; use App\Payout;  use App\PageContent; use App\City;







use PDF, Crypt;





class SaController extends Controller {



	



	public function __construct()



	{



		$this->middleware('auth');



		if( Auth::user() && Auth::user()->role_id != 2 ) {



			Redirect::to('/')->send();



			exit;



		}



		



		$this->user = Auth::user();



		//force to accept terms at first login



		if( $this->user->is_agreed == 0 ){



			$data = PageContent::where("page_name","sa_agreement")->get();



			$data = json_decode($data[0]->page_content);



			$terms = View::make("sa.terms",compact("data"));



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

		//get meters of current user's direct referrals
		$meters = Meter::join("referrals","meters.user_id","=","referrals.user_id")->where( function( $obj ) use( $user,$vars ) {
			$obj->where("referrals.referred_by",$user->id);
			if( $vars['start_date'] != '' )  $obj->where("meters.created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );
			if( $vars['end_date'] != '' )  $obj->where("meters.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );
		} )->get();

		$active_meters = 0;
		$current_date = date("Y-m-d H:i:s");
		foreach( $meters as $meter ){
			if( strtotime($meter->updated_at) >= strtotime('-30 days') and $meter->hour_price > 0 ){
				$active_meters++;
			}
		}

		//get referal comission details		
		$net_comm = ReferralCommission::join("referrals","referrals.id","=","referral_commissons.referral_id")->where( function( $obj ) use( $user,$vars	 ){
			$obj->where("referrals.referred_by",$user->id);

			if( $vars['start_date'] != '' )  $obj->where("referral_commissons.created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );
			if( $vars['end_date'] != '' )  $obj->where("referral_commissons.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

		} )->select(DB::raw("sum(referral_commissons.commission_amount) as total_commission"))->pluck("total_commission");

		/*gather chart data*/
		$chart_details = ReferralCommission::join("referrals","referrals.id","=","referral_commissons.referral_id")->where( function( $obj ) use( $user,$vars	 ){
			$obj->where("referrals.referred_by",$user->id);

			if( $vars['start_date'] != '' )  $obj->where("referral_commissons.created_at", ">=", date("Y-m-d H:i:s",strtotime($vars['start_date'])) );
			if( $vars['end_date'] != '' )  $obj->where("referral_commissons.created_at", "<=", date("Y-m-d H:i:s",strtotime($vars['end_date'])) );

		} )->select( DB::raw("sum(referral_commissons.commission_amount) as comm_per_day, Year(referral_commissons.created_at) as Yr, Month(referral_commissons.created_at) as Mn, Day(referral_commissons.created_at) as Dy"))->groupBy( DB::raw("Month(referral_commissons.created_at),Day(referral_commissons.created_at)") )->orderBy( DB::raw("Month(referral_commissons.created_at),Day(referral_commissons.created_at)") )->get();


		$chart_data = array("label"=>[],"dataset"=>[]);
		foreach( $chart_details as $chart ){
			$chart_data["label"][] = $chart->Dy . " / " . $chart->Mn;
			$chart_data["dataset"][] = $chart->comm_per_day;
		}

		/*set variables*/
		$vars["role"] = $this->user->role()->pluck("name");
		$vars["affilliateURL"] = app("App\Http\Controllers\UtilsController")->affilliateURL($user->promo_code);
		$vars["signups"] = $referrals->count();
		$vars["meters"] = $meters->count();
		$vars["active_meters"] = $active_meters;
		$vars["net_comm"] = $net_comm;
		$vars["next_pay"] = $user->balance;
		$vars["chart_data"] = $chart_data;
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
		
		/* If country doesn't exists for the user then set canada as default country  */
		$selected_country = ($user->country > 0) ? $user->country : 2;
		
		$selected_country_info = Country::where("id",$selected_country)->get();
		$states = City::where("country_id",$selected_country)->groupBy("state_code")->get();
		$selected_state = ($user->state > 0) ? $user->state :'';
		//$cities = app("App\Http\Controllers\HomeController")->get_cities($selected_country,$user['state']);

		$cities = City::where("country_id",$user->country)->where("state_code",$user->state)->get();
		$banks = app("App\Http\Controllers\HomeController")->get_payment_banks($request,'',$selected_country_info[0]->iso);

		////////////////////////////////////////////////////////////////////////////////END ACCOUNT TAB//////////////////////////////////////////////////////////////////



		



		$data = PageContent::where("page_name","faq_sa")->get();



		if( count($data) > 0 ){



			$data = json_decode($data[0]->page_content);



		}else{



			$fields["faq"] = array("page_title","page_content","questions","answers");



			foreach( $fields[$page] as $key=>$fld ){



				$data->$fld = "";



			}



		}

		$vars["overview_data"] = PageContent::where( "page_name", "sa_dashboard" )->get();



		if( count($vars["overview_data"]) > 0 ){



			$vars["overview_data"] = json_decode($vars["overview_data"][0]->page_content);



		}else{



			$vars["overview_data"]->dashboard_text = "";



			$vars["overview_data"]->dashboard_image = "";



		}







		$vars["agreement_data"] = PageContent::where( "page_name", "sa_agreement" )->get();



		if( count($vars["agreement_data"]) > 0 ){



			$vars["agreement_data"] = json_decode($vars["agreement_data"][0]->page_content);



		}else{



			$vars["agreement_data"]->page_title = "";



			$vars["agreement_data"]->page_content = "";



		}



		



		$vars["marketing_data"] = PageContent::where( "page_name", "sa_marketing" )->get();



		if( count($vars["marketing_data"]) > 0 ){



			$vars["marketing_data"] = json_decode($vars["marketing_data"][0]->page_content);



		}else{



			$vars["marketing_data"]->marketing_text = "";



			$vars["marketing_data"]->brochure_image = "";



			$vars["marketing_data"]->business_card_image = "";



		}


		$vars["brochure"] = URL::to("/sa/brochure");



		$vars["brochure_img"] = $vars["marketing_data"]->brochure_image;



		$vars["businesscard"] = URL::to("/sa/businesscard");



		$vars["businesscard_img"] = $vars["marketing_data"]->business_card_image;



		$dateObj   = DateTime::createFromFormat('!m', date("m")+1);



		$vars["next_month"] = $dateObj->format('F');


		$status = $request->has("status") ? $input["status"] : "";
		//get sales associates
			
		$landowner_users = User::leftJoin("referrals","users.id","=","referrals.user_id")->leftJoin("cities","users.city","=","cities.id")->leftJoin("meters","meters.user_id","=","users.id")->where("referrals.referred_by",Auth::user()->id)->where("users.role_id",3)->select( "users.*","cities.city_name",DB::raw("count(meters.meter_id) as meter_count"))->groupBy("users.id")->get();

		$vars["revenue"] = array();
		$vars["commission"] = array();
		foreach( $landowner_users as $lo){

			$vars["revenue"][$lo->id] = Payment::where("payments.user_id",$lo->id)->where( "payments.payment_type","meter_hire" )->where( "payments.trans_status","approved" )->select(DB::raw("sum(payments.amount) as amt"))->pluck("amt");


			$vars["commission"][$lo->id] = ReferralCommission::join("referrals","referrals.id","=","referral_commissons.referral_id")->where("referrals.referred_by",Auth::user()->id)->where("referrals.user_id",$lo->id)->select(DB::raw("sum(referral_commissons.commission_amount) as total_commission"))->pluck("total_commission");

		}	
		return view('affiliate-home', compact('user','countries','data','states','cities','landowner_users','banks'))->with("vars",$vars);
	}


	public function getBrochure(){



		$user = $this->user;

		$marketing_data = PageContent::where( "page_name", "sa_marketing" )->get();


		if( count($marketing_data) > 0 ){

			$marketing_data = json_decode($marketing_data[0]->page_content);

		}else{

			$marketing_data->brochure_image = "";

		}



		$data = array("promo_code"=>$user->promo_code,"brochure_image"=>$marketing_data->brochure_image);


		$paper_size = array(0,0,611.71,792);

		$pdf1 = PDF::setPaper($paper_size, 'portrait');

		$pdf = $pdf1->loadView('sa.brochure', $data);

		return $pdf->download('brochure.pdf');

	}







	public function getBusinesscard(){


		$marketing_data = PageContent::where( "page_name", "sa_marketing" )->get();

		if( count($marketing_data) > 0 ){

			$marketing_data = json_decode($marketing_data[0]->page_content);

		}else{

			$marketing_data->business_card_image = "";

		}

		$data["sa"] = $this->user;

		$data["business_card_image"]	= $marketing_data->business_card_image;

		$paper_size = array(0,0,252,144);

		$pdf1 = PDF::setPaper($paper_size, 'portrait');


		$pdf = $pdf1->loadView('sa.businesscard', $data);

		return $pdf->download('business_card.pdf');

	}



public function getCreateuser( Request $request ){

		//Input::old();

		$inputs = $request->all();

		$user_id = 0;

		$oldInputs = Input::old();

		$user = ( count($oldInputs) ) ? $oldInputs : User::find($user_id);

		$vars['inspectionsURL'] = "";

		$vars['user_id'] = $user_id;

		$vars['show_layout'] = Input::has("show_layout")?$inputs["show_layout"]:true;

		return view('sa.edituser', compact('user','vars'));

	}
	
public function postCreatenewuser( Request $request ){

		$input = $request->all();

		$user_id = 0;

		//,"email_payments"

		$user_fields = array("name","email","password");

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

			return Redirect::to('/sa-home')->withInput($request->except('_token'))->withErrors($messages);

			exit;

		}

		else{

			$generatePassword = str_random(6);

			$input["password"] = bcrypt($generatePassword);

			

			$user = new User;

			foreach($user_fields as $field){

				$user->{$field}= $input[$field] ;

			};
			
			$user->role_id = 3;
			$user->security_answer = uniqid();
			//$user->created_by = Auth::user()->id;
		
		
			//update records
			$user->save();
			
			Referral::create([
					'user_id' => $user->id,
					'referred_by' => Auth::user()->id,
					'commission' => config('commission'),
					'referral_medium'	=> 'URL'
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

				//app('App\Http\Controllers\HomeController')->createPromoCode($user->id);

				$subject = isset($get_content['registration']['subject']) ? $get_content['registration']['subject']  : "Your account has been created!";

				$body = isset($get_content['registration']['body']) ? $get_content['registration']['body']  : "";

			

			

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

			return Redirect::to('/sa-home')->with('success', 'User created. An email with credentials has been sent.');

		}

	}



}




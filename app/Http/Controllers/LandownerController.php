<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use Hash; use DB; use Auth; use Input; use DateTime; use View; use URL; use Cookie; use Session;
use App\Lot; use App\Meter; use App\Payment; use App\PaymentItem; use App\User; use App\Referral; use App\ReferralCommission; use App\Country; use App\SmsQueue;  use App\Role; use App\Payout;  use App\PageContent; use App\City;
use App\towing_companies; 
use PDF, Crypt;
use App\Offer;
use Mapper;
use App\LandownersSettings;
use App\Settings;
use App\VariableRate;

class LandownerController extends Controller  
{	

	public function __construct(Request $request)
	{
		$this->middleware('auth');
		if( Auth::user() && ( Auth::user()->role_id != 3 && Auth::user()->role_id != 1 ) ) {
			Redirect::to('/')->send();
			exit;
		}

		$this->user = Auth::user();
		
		//force to accept terms at first login
		/*if( $this->user->is_agreed == 0 ){
			$data = PageContent::where("page_name","owner_agreement")->get();
			$data = json_decode($data[0]->page_content);
			
			$terms = View::make("landowner.terms",compact("data"));
			echo $terms;
			exit;
		}*/

		/*if( Session::has('offer_promo_code') && Session::get('offer_promo_code') ){

			$promo_error = Session::has('promo_error') ? Session::get('promo_error'):"";

			Session::put('offer_promo_code',0);
			Session::save();
			$terms = View::make("landowner.offer-promo")->with('promo_error',$promo_error);
			echo $terms;
			exit;
		}*/

		//offer first free meter to landowner if not assigned yet and meter count is zero
		/*if( !$this->user->free_meter_assigned ){
			$meter_count = Meter::where("user_id",$this->user->id)->count();
			if( !$meter_count ){
				
				$countries = Country::where("status",1)->get();
				$states = City::where("country_id",$countries[0]->id)->groupBy("state_code")->get();
				$cities = app("App\Http\Controllers\HomeController")->get_cities($countries[0]->id,'');

				//City::where("country_id",$countries[0]->id)->where("state_code",'')->get();

				$html = View::make("landowner.offer-free-meter")->with("first_meter",1)->with("countries",$countries)->with("states",$states)->with("cities",$cities);
				echo $html;
				exit;	
			}
		}*/
		
		
		
		/*if( !$this->user->free_meter_assigned ){
//			echo $this->user->free_meter_assigned; 
			$meter_count = Meter::where("user_id",$this->user->id)->count(); //echo "meter_count -- ".$meter_count; exit();
			if( !$meter_count ){
				
				$cities = City::where("country_id",2)->get();

				$html = View::make("landowner.offer-free-meter")->with("first_meter",1)->with("cities",$cities);
				echo $html;
				exit;	
			}
		}*/
		
		
	}	


	public function index(Request $request, $tab='')
	{
		
		
		
		
		$userid = $this->user->id;
		
		$user = $this->user;		

		$action = 'decrypt';

		if(!empty($user->transit_no))
			$user->transit_no = app("App\Http\Controllers\UtilsController")->encrypt_decrypt($action,$user->transit_no);		

		if(!empty($user->account_no))
			$user->account_no = app("App\Http\Controllers\UtilsController")->encrypt_decrypt($action,$user->account_no);

		$input = $request->all();
		//echo "<pre>"; print_r($input); echo "</pre>";
		$oldInputs = Input::old();

		$vars = array( "tab"=>$tab );
		//echo "<pre>"; print_r($vars); echo "</pre>";
		if( $tab == 'account' && count($oldInputs) ){
			$temp_arr = $user->toArray();
			$temp_arr = array_merge($temp_arr,$oldInputs);
			$user = (object)$temp_arr;
			unset($temp_arr);
		}else{
			$user = (object)$user->toArray();
		}

		$mylots = Lot::where( [ 'user_id'=>$userid, "status"=>1 ] )->get();
		
		$vars = array( "tab"=>$tab ); 
		$vars["inspectionsURL"] = app("App\Http\Controllers\UtilsController")->inspectionsURL($userid);

		$input = $request->all();
		$vars['group_id'] = $request->has("group_id") ? $input["group_id"] : "";
		$vars['start_date'] = $request->has("start_date") ? $input["start_date"] : "";
		$vars['end_date'] = $request->has("end_date") ? $input["end_date"] : "";
		$vars['export']	=	$request->has("export") ? $input["export"] : "";

		if( count($mylots) ){
			$vars["lot_id"] = $mylots[0]->id;
			$vars["lot_name"] = $mylots[0]->lot_name;
			$vars["lot_address"] = $mylots[0]->lot_address;
			$vars["lot_price"] = $mylots[0]->price;
			$vars["lot_start_time"] = app("App\Http\Controllers\UtilsController")->formatTime($mylots[0]->start_time,"h:i a");
			$vars["lot_end_time"] = app("App\Http\Controllers\UtilsController")->formatTime($mylots[0]->end_time,"h:i a");
		}else{
			$vars["lot_id"] = 0;
			$vars["lot_name"] = "";
			$vars["lot_address"] = "";
			$vars["lot_price"] = "";
			$vars["lot_start_time"] = "";
			$vars["lot_end_time"] = "";
		}		

		$countries = Country::where("status",1)->get();
		//$selected_country = ($user->country > 0) ? $user->country : $countries[0]->id;
		$selected_country = ($user->country > 0) ? $user->country : 2;
		$selected_country_info = Country::where("id",$selected_country)->get();

		$banks = app("App\Http\Controllers\HomeController")->get_payment_banks($request,'',$selected_country_info[0]->iso);
		$states = City::where("country_id",$selected_country)->groupBy("state_code")->get();
		$cities = City::where("country_id",$user->country)->where("state_code",$user->state)->get();

		// Towing companies lacated in user city
		$towing_companies = towing_companies::where('city_id',$user->city)->get();

		//$mymeters = Meter::where( [ 'user_id'=>$userid, 'lot_id'=>$vars["lot_id"], 'status'=>1 ])->get();
		
		//meter details for parking sign section
		$where = array('meters.user_id'=>$userid, 'meters.lot_id'=>$vars["lot_id"], 'meters.status'=>1);
		$mymeters = Meter::join("lots","meters.lot_id","=","lots.id")->where($where)->get();
		
		//meter details for new home page
		$where = array( "meters.user_id" => $userid, "meters.status" => 1 );
		if( $vars['group_id'] > 0 )	$where["meters.lot_id"] = $vars['group_id'];

		$payment_where = "";
		if( $vars['start_date'] != '' )	$payment_where .= " AND payments.created_at >= '". date("Y-m-d H:i:s",strtotime($vars['start_date'])) ."' ";
		if( $vars['end_date'] != '' )	$payment_where .= " AND payments.created_at <= '". date("Y-m-d H:i:s",strtotime($vars['end_date'])) ."' ";

		$vars['meter_details'] = Meter::join("lots","meters.lot_id","=","lots.id")->where($where)->select("meters.id","meters.expiry","meters.meter_id","meters.lot_id","lots.lot_name","meters.hour_price","lots.price",DB::raw("
		(select count(payment_items .id) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id  ". $payment_where ." ) as transactions, 
		(select sum(payment_items .total_price * payment_items .payment_commission / 100) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id  ". $payment_where ."  ) as landowner_revenue, 
		(select sum(payment_items.hours) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id   ". $payment_where ."  ) as total_hours"))->get();		
		
		//dd($vars);
		/* Transactions of the selected meter*/
		$meter_details = array();
		if(isset($input["meter_id"])){
			$groupBy =  "day(payments.created_at), month(payments.created_at), year(payments.created_at)" ;
			$meter_id = $input["meter_id"];
			
			if( !empty($input["start_date"]) || !empty($input["end_date"])){
				 
				$meter_details[] = PaymentItem::join("payments","payments.id","=","payment_items.payment_id")->join("meters","meters.id","=","payment_items.meter_id")->where("meters.meter_id",$meter_id)->where("payments.payment_type",'meter_hire')->where( function( $obj ) use($input) {
	
					if( !empty($input["start_date"]) ){
						$obj->where("payments.created_at",">=",date("Y-m-d H:i:s",strtotime($input["start_date"]." 00:00:00")));
					}
					if( !empty($input["end_date"]) ){
						$obj->where("payments.created_at","<=",date("Y-m-d H:i:s",strtotime($input["end_date"]." 23:59:00")));
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
	   }
		//echo "<pre>"; print_r($meter_details); echo "</pre>"; 
		if( $tab == 'report_by_groups' ){
				
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$where = array( "meters.user_id" => $userid );
			$payment_where = "";
			if( $vars['start_date'] != '' )	$payment_where .= " AND created_at >= '". date("Y-m-d H:i:s",strtotime($vars['start_date'])) ."' ";
			if( $vars['end_date'] != '' ) $payment_where .= " AND created_at <= '". date("Y-m-d H:i:s",strtotime($vars['end_date'])) ."' ";
			
			$vars['lot_details'] = Lot::join("meters","meters.lot_id","=","lots.id")->where($where)->select("lots.id","lots.lot_name","lots.price",DB::raw("count(meters.id) as meter_count,( select count(id) from payments where lot_id = lots.id AND payment_type='meter_hire' ".$payment_where." ) as transactions,( select sum(amount) from payments where lot_id = lots.id AND payment_type='meter_hire' ".$payment_where." ) as trans_amount"))->groupBy("lots.id")->get();
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////		

		}elseif( $tab == 'report_by_meters' ){			
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$where = array( "meters.user_id" => $userid );
			if( $vars['group_id'] > 0 )	$where["meters.lot_id"] = $vars['group_id'];
			
			$payment_where = "";
			if( $vars['start_date'] != '' )	$payment_where .= " AND payments.created_at >= '". date("Y-m-d H:i:s",strtotime($vars['start_date'])) ."' ";
			if( $vars['end_date'] != '' )	$payment_where .= " AND payments.created_at <= '". date("Y-m-d H:i:s",strtotime($vars['end_date'])) ."' ";
			
			$vars['meter_details'] = Meter::join("lots","meters.lot_id","=","lots.id")
									->where($where)
									->select("meters.meter_id","meters.expiry","lots.lot_name",
									DB::raw("
										(select count(payment_items .id) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id  ". $payment_where ." ) as transactions, 
										(select sum(payment_items .total_price * payment_items .payment_commission / 100) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id  ". $payment_where ."  ) as landowner_revenue, 
										(select sum(payment_items.hours) from payment_items LEFT JOIN payments on payments.id = payment_items.payment_id where payments.payment_type = 'meter_hire' AND meter_id = meters.id   ". $payment_where ."  ) as total_hours"))->get();
			
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		}

		//payments for payments tab
		$vars['payments'] = Payout::where( "user_id",$userid )->get()->toArray();
		$data = PageContent::where("page_name","faq_landowner")->get();
		if( count($data) > 0 ){
			$data = json_decode($data[0]->page_content);
		}else{
			$fields["faq"] = array("page_title","page_content","questions","answers");
			foreach( $fields[$page] as $key=>$fld ){
				$data->$fld = "";
			}
		}

		$vars["agreement_data"] = PageContent::where( "page_name", "owner_agreement" )->get();
		if( count($vars["agreement_data"]) > 0 ){
			$vars["agreement_data"] = json_decode($vars["agreement_data"][0]->page_content);
		}else{
			$vars["agreement_data"]->page_title = "";
			$vars["agreement_data"]->page_content = "";
		}

		$vars["owner_content"] = PageContent::where( "page_name", "owner_content" )->get();
		if( count($vars["owner_content"]) > 0 ){
			$vars["owner_content"] = json_decode($vars["owner_content"][0]->page_content);
		}else{
			$vars["owner_content"]->signage_content = "";
			$vars["owner_content"]->signage_image = "";
		}

		$dateObj   = DateTime::createFromFormat('!m', date("m")+1);
		$vars["next_month"] = $dateObj->format('F');

		$vars["base_price"] = config("meter_base_price");
		$vars["ship_price_lo"] = config("ship_price_lo");
		
		$vars["promo_code_discount"] = 0;

		//get user city name
		$user->city_name = '';
		foreach ($cities as $city) {
			if( $user->city == $city->id )
				$user->city_name = $city->city_name;
		}

		// Fill out the promo code in landowner Dashboard if entered previously
		if(!empty($user->referred_by)){
			$Referred_by = User::find($user->referred_by);
			$user->promo_code = $Referred_by->promo_code;
			$user->payable_amount = ($vars["base_price"] - (($vars["base_price"] * config("promo_code_discount"))/100))+config("ship_price_lo");
			//$user->payable_amount = '$38.99';
		}else{
			$user->payable_amount = $vars["base_price"] + config("ship_price_lo");
		}

		if( $this->user->referred_by > 0 ){
			$vars["base_price"] = $vars["base_price"] - (($vars["base_price"] * config("promo_code_discount"))/100);
			$vars["promo_code_discount"] = config("promo_code_discount");
		}
		if( $vars['export'] == "Excel" ){
			header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
			header("Content-Disposition: attachment; filename=My-Meter Report.xls");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			
			$vars['user'] = $user;
			
			$data = View::make("landowner.report_by_meters",compact("data"))->with('vars',$vars);
			echo $data;
			exit;
		}
		if( $vars['export'] == "PDF" ){
			
			$vars['user'] = $user;
			$vars = array('vars'=>$vars);
			$paper_size = array(0,0,611.71,792);
			$pdf1 = PDF::setPaper($paper_size, 'portrait');
		
			
			$pdf = $pdf1->loadView('landowner.report_by_meters', $vars);
			return $pdf->download('Meter Report.pdf');
		}
		if($vars['export'] == "transaction_pdf"){
			$vars = array('meter_details'=>$meter_details,'export' => 'transaction_pdf','inputs'=>$input );
			$paper_size = array(0,0,611.71,792);
			$pdf1 = PDF::setPaper($paper_size, 'portrait');
			
			$pdf = $pdf1->loadView('landowner.meters_by_date', $vars);
			return $pdf->download('Meter Report.pdf');
		}
		$vars['user'] = $user;
		
		/* Location tab Map */
		Mapper::map(53.381128999999990000, -1.470085000000040000);
		
		$variable_rates = VariableRate::where('user_id',$userid)->where('lot_id',$vars["lot_id"])->where('status',1)->get(); 
		
		$variable_rates_meta = DB::table("variable_rates_meta")->get() ;
		
		/*$offer = new Offer;
		$offers = $offer->show_test();*/
		
		/*** landowner Account data email and mobile number  ***/
		
		$Lo_Settings = new LandownersSettings();
		$settings_select = $Lo_Settings->settings_select($userid);
		
		if(empty($settings_select)){
			$settings_select = Settings::orderBy("id","desc")->first();
		}
		
		// Removed as now we are using Group/Location address as meter's location
		
		/* List of locations : ( Use shipping address as meter's location ) */
		//$maping = DB::table('payments')->distinct()->select('id','shipping_city','shipping_address')->where('user_id', '=', $userid)->where('shipping_city', '!=', '')->get();
		
		
		
		return view('home', compact('user','mylots','mymeters','countries','payments','data','states','cities','banks','towing_companies','variable_rates','variable_rates_meta','settings_select'))->with('vars',$vars); // ,'offers' ,'maping'
	}
	
	
}

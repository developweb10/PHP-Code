<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;
use Session;
use DB;
use App\Payment; use App\PaymentItem;  use App\Meter;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();   
    }   

    public function callback()
    {
		
		session_start();
        // when facebook call us a with token   
		/* Returns facebook user name and id */
		$providerUser = Socialite::driver('facebook')->user(); //exit();
		
		//echo "<pre>"; print_r($providerUser); echo "</pre>"; exit();
		
		/* my-meter Feed */
		$access_token = $providerUser->token;
		
		$attachment =  array(
		'access_token' => $access_token,
		'name' => "My-Meter.com",
		'description' => "My-Meter.com lets anyone to rent out their parking space by the hour! Just set the hourly rate and post the&nbsp;parking sign at your parking space.&nbsp;It&#039;s that simple!",
		'link' => "https://my-meter.com/dev/public",
		'picture'=>"https://my-meter.com/dev/public/images/custom/20170615054029image.png",
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'https://graph.facebook.com/me/feed?access_token=$access_token');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output 
		$result = curl_exec($ch);
		curl_close ($ch);
		
		
		$json_result = json_decode($result);
		//echo "<pre>"; print_r($json_result); echo "</pre>"; exit();
		/* Check if id exists in response */
		if(isset($json_result->id) && !empty($json_result->id)){
			/* User have posted succesfully */
			//echo "ID : ".$json_result->id;
			//echo "<pre>"; print_r($_SESSION);
			if(isset($_SESSION['payment_id']) && !empty($_SESSION['payment_id'])){
				$payment_detail = Payment::find($_SESSION['payment_id']);
				//print_r($payment_detail); exit();
				//if($payment_detail->bonus_amount == 0){
					
					$_SESSION["bonus"] = 15;
					
					$current_time = date("Y-m-d H:i:s"); // when user click on share button 6:15
					
					$expire_time = $_SESSION['expiry_meter']; // suppose meter hire time is 1hr 7:00
					$diff = strtotime($expire_time) - strtotime($current_time); 
					
					/* calculate the new time in sec */
					
					$_SESSION["expiry_time"] = $diff+(15*60); // 15 min x 60sec
					
					$payment_detail->bonus_amount = 15;
					$payment_detail->update(); 
					$_SESSION['bonus'] = 1 ;
					
					/* Update expiry time in meter */
					
					$meter_ = Meter::where('meter_id',$_SESSION["meter_id"])->get();
					//echo "<pre>"; print_r($meter_); echo "</pre>"; exit();
					$meter_ = $meter_[0];
					$meter_->hours = $meter_->hours+.25;
		
					$meter_->expiry = date("Y-m-d H:i:s" ,strtotime($meter_->expiry) + 60*15);
		
					$meter_->update();
			
					
					return Redirect::to("https://my-meter.com/dev/public/my-meter");
				//}else{
				//	$_SESSION['bonus'] = 0 ;
				//	return Redirect::to("https://my-meter.com/dev/public/my-meter");
				//}
			}
		}else{
			print_r($json_result->error);
		}
		
    }
	
} 
<?php //echo $_SERVER['SERVER_NAME']; exit(); 
//$admin_path = $_SERVER['DOCUMENT_ROOT'] . "/admin/";
//	require_once($admin_path.'config/config.php'); 
//global $db,$org_id,$api;
//ini_set('display_errors',1); 
/*if(defined('ENVIRONMENT') && (ENVIRONMENT=="development"))
{
	
	$appID = $api['facebook_dev']['appId'];
	$appseret = $api['facebook_dev']['secret'];
}
else
{
	$appID = $api['facebook']['appId'];
	$appseret = $api['facebook']['secret'];	
}*/

$appID = "306504433134661";
$appseret = "242a4999e251af30db95a0c4be1da7c0";
	
$_SESSION['success'] = $_SESSION['errors']='';
if(isset($_GET['code'])){
$token = $_GET['code'];

	/* */// create curl resource 
	$ch = curl_init(); 
	// set url 
	curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token?client_id='.$appID.'&redirect_uri=https://'.$_SERVER['SERVER_NAME'].'/dev/public/third-party/token.php&client_secret='.$appseret.'&code='.$token); 

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	//return the transfer as a string 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	// $output contains the output string 
	$output = curl_exec($ch); 	
	// close curl resource to free up system resources 
	curl_close($ch);	
	
	//if(strpos($output,"access_token")){
	$ACCToken = json_decode($output);//explode("&",$output);
	//echo "accesstoken  ".$FinalaccessToken = str_replace("access_token=","",$ACCToken[0]); 
	//echo "OUtput -- " ;
	echo '<pre>';print_r(json_decode($output));	echo '</pre>';
	/*echo*/ $FinalaccessToken = json_decode($output)->access_token;
	//exit();
	$user_id = $_SESSION[ 'user_id' ];
		
 	$ch = curl_init(); 
	// set url 
	curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/me?access_token='.$FinalaccessToken); 

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	//return the transfer as a string 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
	$error_output = curl_error($ch);
	
	// $output contains the output string 
	$Foutput = curl_exec($ch); 	
	// close curl resource to free up system resources 
	curl_close($ch);  
	$mydetails =  json_decode($Foutput);
	echo '<pre>';
	print_r($mydetails);
	echo '</pre>';

	//header("Location:".$admin_url."social/social_connect.php");
	// store token in DB. 
	}
	//	retrive his name and pages if he wants to authurize his business name.
else if(isset($_GET['error'])){
	$_SESSION['errors'] = $_GET['error'] ;
	//header("Location:".$admin_url."social/social_connect.php");
}
else{
	//header("Location:".$admin_url."social/social_connect.php");
}	
	?>
<?php session_start(); 
if( !isset($_SESSION["request"]) ) exit("Don't Try! This page is not directly accessible.");

if( !isset($_SESSION["redirect"]) ) exit("Where did you come from? This page is not directly accessible.");



$request = $_SESSION["request"];

$redirect = $_SESSION["redirect"];



unset($_SESSION["request"]);

unset($_SESSION["redirect"]);





require "mpgClasses.php";

/**************************** Request Variables *******************************/

//Live
/*$store_id='monca88335';

$api_token='jE8hrW9n0Nk9LNFIrh1P';
*/

//development
$store_id='monca01037';
$api_token = 'ic7yJe5Uk8Hz8LpCnRBa';


/************************* Transactional Variables ****************************/



//$cust_id='01235478';

//$order_id='ord-'.date("dmy-G:i:s");

//$amount='1.00';

//$pan='4242424242424242';

//$expiry_date='1119';

//$dynamic_descriptor='123';





$type='purchase';

$crypt='7';

$status_check = 'false';

/*********************** Transactional Associative Array **********************/

$txnArray=array(

	'type'					=>$type,

	'order_id'				=>$request["order_id"],

	'cust_id'				=>$request["cust_id"],

	'amount'				=>$request["amount"],

	'pan'					=>$request["pan"],

	'expdate'				=>$request["expiry_date"],

	'crypt_type'			=>$crypt,

	'dynamic_descriptor'	=>$request["dynamic_descriptor"]

);





/**************************** Transaction Object *****************************/

$mpgTxn = new mpgTransaction($txnArray);

/****************************** Request Object *******************************/

$mpgRequest = new mpgRequest($mpgTxn);

/***************************** HTTPS Post Object *****************************/

/* Status Check Example*/

//$mpgHttpPost  =new mpgHttpsPostStatus($store_id,$api_token,$status_check,$mpgRequest);



$mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

/******************************* Response ************************************/

$mpgResponse=$mpgHttpPost->getMpgResponse();



$response = $mpgResponse->responseData;

/*print_r($response);
exit();*/
$_SESSION['response']	=	$response;

header("Location:".$redirect."?transaction=success");
?>
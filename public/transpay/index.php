<?php

$servername = "localhost";
$username = "mymeter9_appuser";
$password = "FNgKz)!thXtg";
$database = "mymeter9_staging";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

//fetch payable users
$users = mysqli_query($conn,'SELECT users.*, cities.city_id FROM users LEFT JOIN cities ON ( cities.id = users.city ) WHERE balance >= 50 and deleted=0');

//If any user with minimal payable $50 is found, then proceed 
if( count($users) ){
	while( $user = mysqli_fetch_assoc($users) ){
		$status = process_payment($user,$conn);
		echo $status;
	}
}


function encrypt_decrypt($action, $string) {
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

function process_payment( $user, $conn ){

	$errors = array();
	//echo '<pre>';print_r($user);echo '</pre>';

	$payable_amount = $user['balance'];	

	if( empty($user['name']) )
		$errors[] = "First Name is empty.";

	if( empty($user['last_name']) )
		$errors[] = "Last Name is empty.";

	if( empty($user['street']) )
		$errors[] = "Address field is empty.";

	if( empty($user['phone']) )
		$errors[] = "Phone field is empty.";

	if( !($user['country'] > 0) )
		$errors[] = "No country selected.";

	if( empty($user['state']) )
		$errors[] = "No State selected.";

	if( !($user['city'] > 0) )
		$errors[] = "No City selected.";

	if( empty($user['account_no']) )
		$errors[] = "Account number is empty.";

	if( empty($user['bank_code']) )
		$errors[] = "Bank is not selected.";

	if( empty($user['transit_no']) )
		$errors[] = "Transit # is empty.";
	
	if( count($errors) ){
		$text = " Payment can't be processed. User info not complete:<br> ";
		foreach( $errors as $err ){
			$text .=  "<br>". $err;
		}
		
		$text .= "<br><br>User Info: <br> <table cellpadding='5' cellspacing='0'>";

		$text .= "<tr><td>ID</td><td>". $user['id'] ."</td></tr>";
		$text .= "<tr><td>First Name</td><td>". $user['name'] ."</td></tr>";
		$text .= "<tr><td>Last Name</td><td>". $user['last_name'] ."</td></tr>";
		$text .= "<tr><td>Email</td><td>". $user['email'] ."</td></tr>";
		$text .= "<tr><td>Complete Address</td><td>". $user['street'] ."</td></tr>";
		$text .= "<tr><td>Country</td><td>". $user['country'] ."</td></tr>";
		$text .= "<tr><td>State</td><td>". $user['state'] ."</td></tr>";
		$text .= "<tr><td>City</td><td>". $user['city'] ."</td></tr>";
		$text .= "<tr><td>Phone</td><td>". $user['phone'] ."</td></tr>";
		$text .= "<tr><td>Payable Amount</td><td>". $user['balance'] ."</td></tr>";

		$text .= "</table>";

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		$headers .= "X-Mailer: PHP/" . phpversion();
		$headers .= "Content-Transfer-Encoding: 8bit\r\n";
		
		// More headers
		$headers .= 'From: My-meter.com <info@my-meter.com>' . "\r\n";
		$headers .= 'Cc: gurpreet.webtek@gmail.com' . "\r\n";
		
		$to = "matt@cibinel.com";		
		$subject = "Payout Notification";

		mail($to,$subject,$text,$headers);

		return "Not processed";
	}
	

	$token = "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxBdXRoZW50aWNhdGlvbj4NCiAgPElkPjM2YjliYmJkLWFlN2UtNGZjNy04NzVkLTczYmQ5YmEzNGM2MzwvSWQ+DQogIDxVc2VyTmFtZT5tSnlTY004NjR3cDgwY0E2dDIrV1JVekVydDFXWnFmTHBnZjRtTU1Bd2dvdkVwRVNkMkZtY2I5T2pvODNBdXI3SDBrYXJNVlBZeVNha0c4d1k0MUQvSDhXdXd5cTdHZm1ha0xYSnM2aXhub3dkMFRLdktoM2UyaFFhNVdBS1VIU1dpTW9hd2FXUEhDUWlmcEJxNG9YVEdwSlM2QWhWUFIvUFAxazdpMXNXdU09PC9Vc2VyTmFtZT4NCiAgPFBhc3N3b3JkPml5aGV6MWl3M1RPS1daT2hjOE0yQzF4NHRHek5wbDBvaWxKZUh0bDNLRUtTQzZIdUNXdkd2MERDK25QbGNTbFBCTmxoZXNHTDBrUVMxeDJYK0hiMGQ3TmFwVWEwbEJEbmpUZzA1VGl6S0dFbGdGSlptbzB2dTZWK1RYbXgvb0R4U1ZLb3VVNys5eStUdmhLSm9CVlZ5UXB1YVVLNkxHMG02N2R2d0FObmF0VT08L1Bhc3N3b3JkPg0KICA8QnJhbmNoSWQ+cEROdm5Kb2g4d3R3Yml0OU9lVndNTlh1WlNoTDk4L1lPNnNtNkg2NWthVlF2YTBjSC8xb01mQy9rYTBuRnpoMU1ENyt0eDM0WCtiNHBsdndLZTN3amIxR0VSU1dlZ0ZkU0xiU0F5b3grVVVlU2lSYjBRMnJGUFU2WUdFZjI5enFuMUdkT2FZZFp4cUljdFgrb21OanRkWXQ5cm1MRldCWEZMdEZMWDVQalF3PTwvQnJhbmNoSWQ+DQo8L0F1dGhlbnRpY2F0aW9uPg==";

	$endpoint = "https://demo-api.transfast.net";

	//create transaction
	$endpoint .= "/api/transaction/invoice";

	//decrypt account number
	$account_no = encrypt_decrypt('decrypt',$user['account_no']);

	$BankId = $user['bank_code'];
	$BankBranchId = encrypt_decrypt('decrypt',$user['transit_no']);

	//USA
	if( $user["country"] == 1 ){
		$CountryIsoCode = "US";
		$ReceiveCurrencyIsoCode = "USD";
		$PayingBranchId = "US030001";
		$PayerId = "US03";		

	//Canada
	}elseif( $user["country"] == 2 ){
		$CountryIsoCode = "CA";
		$ReceiveCurrencyIsoCode = "CAD";
		$PayingBranchId = "XA01000001";
		$PayerId = "XA01";

	//UK
	}elseif( $user["country"] == 3 ){
		$CountryIsoCode = "GB";
		$ReceiveCurrencyIsoCode = "GBP";
		$PayingBranchId = "AT010035";
		$PayerId = "AT01";

	}

	$request_array = array(

		"TransactionInfo"	=>	array(
			"ReceiveCurrencyIsoCode"	=>	$ReceiveCurrencyIsoCode,
			"PayingBranchId"			=>	$PayingBranchId,
			"SourceCurrencyIsoCode"		=>	"CAD",
			"PaymentModeId"				=>	"C",				
			"PayerId"					=>	$PayerId,
			"SentAmount"				=>	$payable_amount,
			"BankId"					=>	$BankId,
			"BankBranchId"				=>	$BankBranchId,
			"Account"					=>	$account_no
		),

		"Sender"	=>	array(
			"Name"				=>	"Hervert viveros",
			"Address"			=>	"test address",
			"ZipCode"			=>	"02138",
			"PhoneMobile"		=>	"123",
			"IsIndividual"		=>	"true",
			"CountryIsoCode"	=>	"CA",
			"StateId"			=>	"AB",
			"CityId"			=>	"61852",	
		),

		"Receiver"	=>	array(
			"FirstName"			=>	$user['name'],
			"LastName"			=>	$user['last_name'],
			"CompleteAddress"	=>	$user['street'],
			"MobilePhone"		=>	$user['phone'],
			"CountryIsoCode"	=>	$CountryIsoCode,
			"StateId"			=>	$user['state'],
			"CityId"			=>	$user['city_id'],
		)
	);

	//echo "<pre>"; print_r($request_array);
	$request_data = json_encode($request_array);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $endpoint);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $request_data);
	curl_setopt($ch, CURLOPT_TIMEOUT, '60');
	//curl_setopt($ch,	CURLOPT_USERAGENT,$gArray['API_VERSION']);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	$headers = array();
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Credentials '.$token;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$response=curl_exec ($ch);
	curl_close($ch);

	$response_arr = json_decode($response,true);

	echo "<pre><h1>User: ".$user['name'] . " " . $user['last_name']."</h1>"; print_r($response_arr);
	
	if( isset($response_arr['StatusName']) ){
		$TfPin = $response_arr['TfPin'];
		//$status = $response_arr['StatusName'];
		$status = 'Success';
	}else{
		$TfPin = '';
		$status = 'Failed';
	}

	$insert = "INSERT INTO payouts ( user_id, amount, trans_id, trans_status, trans_response, created_at ) VALUES( '". $user['id'] ."', '". $payable_amount ."', '". $TfPin ."', '". $status ."', '". $response ."', '". date('Y-m-d H:i:s') ."' )";

	mysqli_query($conn,$insert);

	if( $status == "Success" ){
		$update = "UPDATE users set balance = 0 WHERE id = ". $user['id'];
		mysqli_query($conn,$update);
	}

	$text .= "<br><br>Transaction Info: <br> <table cellpadding='5' cellspacing='0'>";

	$text .= "<tr><td>Status</td><td>". $status ."</td></tr>";
	$text .= "<tr><td>ID</td><td>". $user['id'] ."</td></tr>";
	$text .= "<tr><td>First Name</td><td>". $user['name'] ."</td></tr>";
	$text .= "<tr><td>Last Name</td><td>". $user['last_name'] ."</td></tr>";
	$text .= "<tr><td>Email</td><td>". $user['email'] ."</td></tr>";
	$text .= "<tr><td>Complete Address</td><td>". $user['street'] ."</td></tr>";
	$text .= "<tr><td>Country</td><td>". $user['country'] ."</td></tr>";
	$text .= "<tr><td>State</td><td>". $user['state'] ."</td></tr>";
	$text .= "<tr><td>City</td><td>". $user['city'] ."</td></tr>";
	$text .= "<tr><td>Phone</td><td>". $user['phone'] ."</td></tr>";
	$text .= "<tr><td>Payable Amount</td><td>". $user['balance'] ."</td></tr>";

	$text .= "<tr><td>Transaction ID</td><td>". $TfPin ."</td></tr>";
	$text .= "<tr><td>Transaction Status</td><td>". $status ."</td></tr>";
	$text .= "<tr><td>Transaction Response</td><td><pre>". print_r($response_arr,true) ."</pre></td></tr>";

	$text .= "</table>";

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
	$headers .= "X-Mailer: PHP/" . phpversion();
	$headers .= "Content-Transfer-Encoding: 8bit\r\n";
	
	// More headers
	$headers .= 'From: My-meter.com <info@my-meter.com>' . "\r\n";
	$headers .= 'Cc: gurpreet.webtek@gmail.com' . "\r\n";
	
	$to = "matt@cibinel.com";		
	//$to = 'gurpreet.webtek@gmail.com';
	$subject = "Payout Notification - ".$user['name'] . " " . $user['last_name'];

	mail($to,$subject,$text,$headers);

	$log_file = "logs/log_". date("Y_m_d").".txt";
	if( file_exists($log_file) ){
		$text = file_get_contents($log_file) . $response ;
		file_put_contents($log_file, $text);
	}else{
		$fp = fopen($log_file,"wb");
		fwrite($fp,$response);
		fclose($fp);
	}

	return "Processed";

}
?>
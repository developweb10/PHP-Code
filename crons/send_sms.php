<?php
	date_default_timezone_set('CST6CDT');
	$servername = "localhost";
/*	$username = "mymeter9_appuser";
	$password = "FNgKz)!thXtg";
	$database = "mymeter9_mymeterapp";
*/
	$username = "mymeter9_appuser";
	$password = "FNgKz)!thXtg";
	$database = "mymeter9_staging";
	// Create connection
	$conn = new mysqli($servername, $username, $password, $database);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	$body = " Message set corresponding to queries: <Br>";
	
	$date = date('Y-m-d H:i:s');
	$sms_queue = mysqli_query($conn,"SELECT * FROM sms_queue WHERE send_at <= timestamp(DATE_Add('".$date."', INTERVAL 1 MINUTE))"); 
	while( $sms = mysqli_fetch_assoc($sms_queue) ):
		
		$result = send_sms($sms['to'],$sms['sms_body']);
		$log_query = " INSERT INTO sms_log ( `to`, `sms_body`, `sms_status`, `meter_id`, `meter_owner`, `send_at`, `created_at`, `updated_at` ) VALUES( '". $sms['to'] ."', '". mysqli_real_escape_string($conn, $sms['sms_body']) ."', '". $result ."', '". $sms['meter_id'] ."', '". $sms['meter_owner'] ."', '". $sms['send_at'] ."', '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."'  )";
		
		$body .= $log_query . "<hr>";
		
		//create sms log
		mysqli_query($conn,$log_query); 
		//delete this sms record
		mysqli_query($conn,"DELETE FROM sms_queue WHERE id = '". $sms['id'] ."'"); 
		
	endwhile;
	
	
	function send_sms( $to, $body ){
		$body = ( strlen($body) > 140 ) ? substr($body,1,140) : $body;
		$account_key = "M7w7r64QveiZPrA4fEk5x0r762blIW4N";
		
		$result = file_get_contents("http://smsgateway.ca/sendsms.aspx?CellNumber=".$to."&MessageBody=".urlencode($body)."&AccountKey=".$account_key);
		return $result;
	}

	if( mysqli_num_rows($sms_queue) > 0 ){
		mail("gurpreet.webtek@gmail.com","Cron Job Stats",$body);
	}
?>
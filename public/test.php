<link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>
<script src="https://my-meter.com/dev/public/js/jquery-2.1.4.min.js" type="text/javascript"></script>
<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
<?php


	/*date_default_timezone_set('CST6CDT');

	$date = date('Y-m-d H:i:s');
	echo 'SELECT * FROM sms_queue WHERE send_at <= timestamp(DATE_Add('.$date.', INTERVAL 1 MINUTE))'; */

	// Always set content-type when sending HTML email
	/*$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
	$headers .= "X-Mailer: PHP/" . phpversion();
	$headers .= "Content-Transfer-Encoding: 8bit\r\n";
	
	// More headers
	$headers .= 'From: My-meter.com <info@my-meter.com>' . "\r\n";
	$headers .= 'Cc: gurpreet.webtek@gmail.com' . "\r\n";
	
	
	//$data['email']
	$to = 'manvindersingh80@gmail.com,mandeep.webtech@gmail.com,gurpreet.webtek@outlook.com';
	$subject = "Your account has been created!";
	$message = "
		<h3>Greetings!</h3>
		<p>Your account with my-meter.com has been created. Below are the login credentials:</p>
		<p>Email: gurpreet.webtek@outlook.com</p>
		<p>Password: 123456</p>
		<a href='http://staging.my-meter.com/account/login' target='_blank'>Click here</a> to Login.
		<br><br><br>
		Thanks<br>
		My-meter.com Support Team
	";
	
	mail($to,$subject,$message,$headers);*/	

?>

  <script>
$(document).ready(function () {
		
		
		var availableTags = [
		  "Perl",
		  "PHP",
		  "Python",
		  "Ruby",
		  "Scala",
		  "Scheme"
		];
		$( "#cities" ).autocomplete({
		  source: availableTags
		});
  } );
</script>

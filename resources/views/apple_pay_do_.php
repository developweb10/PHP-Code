<?php
	echo "Test"; exit();
  if(isset($_GET['u'])){
	  $validation_url = $_GET['u'];  
   }else{
	   $validation_url ="https://apple-pay-gateway-cert.apple.com/paymentservices/startSession";
   }
//NB check $validation_url is apple.com  
// create a new cURL resource  
$ch = curl_init();  
  
$data = '{"merchantIdentifier":"merchant.com.my-meter", "domainName":"my-meter.com", "displayName":"My-Meter"}';  
  
  
curl_setopt($ch, CURLOPT_URL, $validation_url);  
//curl_setopt($ch, CURLOPT_SSLCERT, PRODUCTION_CERTIFICATE_PATH);  
//curl_setopt($ch, CURLOPT_SSLKEY, PRODUCTION_CERTIFICATE_KEY);  
curl_setopt($ch, CURLOPT_SSLVERSION, 6);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_POST, 1);  
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
  
  
if(curl_exec($ch) === false)  
{  
    echo 'Curl error is gfdjf : ' . curl_error($ch);  
}  
  
  
// close cURL resource, and free up system resources  
curl_close($ch); 
?>
<?php

$token = "PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxBdXRoZW50aWNhdGlvbj4NCiAgPElkPjM2YjliYmJkLWFlN2UtNGZjNy04NzVkLTczYmQ5YmEzNGM2MzwvSWQ+DQogIDxVc2VyTmFtZT5tSnlTY004NjR3cDgwY0E2dDIrV1JVekVydDFXWnFmTHBnZjRtTU1Bd2dvdkVwRVNkMkZtY2I5T2pvODNBdXI3SDBrYXJNVlBZeVNha0c4d1k0MUQvSDhXdXd5cTdHZm1ha0xYSnM2aXhub3dkMFRLdktoM2UyaFFhNVdBS1VIU1dpTW9hd2FXUEhDUWlmcEJxNG9YVEdwSlM2QWhWUFIvUFAxazdpMXNXdU09PC9Vc2VyTmFtZT4NCiAgPFBhc3N3b3JkPml5aGV6MWl3M1RPS1daT2hjOE0yQzF4NHRHek5wbDBvaWxKZUh0bDNLRUtTQzZIdUNXdkd2MERDK25QbGNTbFBCTmxoZXNHTDBrUVMxeDJYK0hiMGQ3TmFwVWEwbEJEbmpUZzA1VGl6S0dFbGdGSlptbzB2dTZWK1RYbXgvb0R4U1ZLb3VVNys5eStUdmhLSm9CVlZ5UXB1YVVLNkxHMG02N2R2d0FObmF0VT08L1Bhc3N3b3JkPg0KICA8QnJhbmNoSWQ+cEROdm5Kb2g4d3R3Yml0OU9lVndNTlh1WlNoTDk4L1lPNnNtNkg2NWthVlF2YTBjSC8xb01mQy9rYTBuRnpoMU1ENyt0eDM0WCtiNHBsdndLZTN3amIxR0VSU1dlZ0ZkU0xiU0F5b3grVVVlU2lSYjBRMnJGUFU2WUdFZjI5enFuMUdkT2FZZFp4cUljdFgrb21OanRkWXQ5cm1MRldCWEZMdEZMWDVQalF3PTwvQnJhbmNoSWQ+DQo8L0F1dGhlbnRpY2F0aW9uPg==";

	$endpoint = "https://demo-api.transfast.net";

	//create transaction
	$endpoint .= "/api/transaction/invoice";

	$country = "Canada";
	$request_array = array(

		"TransactionInfo"	=>	array(
			"ReceiveCurrencyIsoCode"	=>	($country == "Canada") ? "CAD" : (($country == "USA") ? "USD" : "GBP"),
			"PayingBranchId"			=>	($country == "Canada") ? "XA01000001" : (($country == "USA") ? "US030001" : "AT010035"),
			"SourceCurrencyIsoCode"		=>	"CAD",
			"PaymentModeId"				=>	"C",				
			"PayerId"					=>	($country == "Canada") ? "XA01" : (($country == "USA") ? "US03" : "AT01"),
			"SentAmount"				=>	"20",
			"BankId"					=>	($country == "Canada") ? "C0001" : (($country == "USA") ? "U100" : "UK1"),
			"BankBranchId"				=>	($country == "Canada") ? "85294" : (($country == "USA") ? "323075097" : "BARCGB22"),
			"Account"					=>	"123456"
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
			"FirstName"			=>	"Erik",
			"LastName"			=>	"Hernandez",
			"CompleteAddress"	=>	"test",
			"MobilePhone"		=>	"123",
			"CountryIsoCode"	=>	"CA",
			"StateId"			=>	"ON",
			"CityId"			=>	"75335",
		)
	);

	echo "<pre>"; print_r($request_array); echo "</pre>";
	die();

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
	echo "<pre>"; print_r($response_arr);

?>
<script src="https://my-meter.com/dev/public/js/jquery-1.11.3.min.js" type="text/javascript"></script>
<!--	<script src="https://js.braintreegateway.com/web/3.22.2/js/client.min.js"></script>
<script src="https://js.braintreegateway.com/web/3.22.2/js/apple-pay.min.js"></script>-->
    <!--<label> Username :</label>
    <input type="text" name="username">-->
    <style>
	  #apple-pay-button {
		/*display: none;*/
		background-color: black;
		background-image: -webkit-named-image(apple-pay-logo-white);
		background-size: 100% 100%;
		background-origin: content-box;
		background-repeat: no-repeat;
		width: 100%;
		height: 44px;
		padding: 10px 0;
		border-radius: 10px;
	  }
	</style>
	<button id="apple-pay-button"></button>


<script>

	dump = function( obj ) {
		var out = "" ;
		if ( typeof obj == "object" ) {
			for ( key in obj ) {
				if ( typeof obj[key] != "function" ) out += key + ': ' + obj[key] + '\n' ;
			}
		}
		return out;
	}
	//alert(dump(window.ApplePaySession));
	if (window.ApplePaySession) {
		//alert("Apple pay session is set");
	   var merchantIdentifier = 'merchant.com.my-meter'; //merchant.com.meter.gurpreet	
	   var promise = ApplePaySession.canMakePaymentsWithActiveCard(merchantIdentifier);
	   promise.then(function (canMakePaymentsWithActiveCard) {
		  if (canMakePaymentsWithActiveCard){
			  
			  
			 // alert("you can make payments");
			  
		  }else{
			  //alert("FALSE");
		  }
			 // Display Apple Pay Buttons here…
			 $('#activation_steps .checkout_process #proceed_checkout').css('display','none');
	   }); 
	}else{
		//alert("Apple pay session is not set");
	}
	function beginApplePay() {
		//alert("Buy with apple : ");
		 var session;
		 var paymentRequest = {  
		  currencyCode: 'CAD',  
		  countryCode: 'CA',  
		  
	   requiredShippingContactFields: ['postalAddress'],
	   lineItems: [{label: "dub toal description", amount: 1 }],
		  total: {  	
		 label: "COMPANY, INC.",
		  amount: 1
		  
		  
		  },  
		 /* supportedNetworks: ['amex', 'discover', 'masterCard', 'visa'],  
		   merchantCapabilities: [ 'supports3DS' ]  */
		   
		   
	   supportedNetworks: ['amex', 'masterCard', 'visa' ],
	   merchantCapabilities: [ 'supports3DS', 'supportsEMV', 'supportsCredit', 'supportsDebit' ]
		   
		   
	   
		};  
		
		 //alert("paymentRequest");
		// alert(dump(paymentRequest));
		 //supportsVersion(1);
		 //alert(" version: "+supportsVersion('1'));
		session = new ApplePaySession(1, paymentRequest);  
		//alert("Welcome");
		
		// Merchant Validation  
	  	session.onvalidatemerchant = function (event) {  
	  		//alert(event.validationURL);  
			//alert('onvalidatemerchant');
			//alert(dump(event));  
			var promise = performValidation(event.validationURL);  
			//alert("Promise");
			//alert(dump(promise));  
			promise.then(function (merchantSession) {  
			 // alert("Merchant session: "+dump(merchantSession));
			  session.completeMerchantValidation(merchantSession);  
			  //alert('Starting session.completeMerchantValidation');  
		  });  
	  	}  
		
		session.begin();
		 alert(dump(session));
		 
		 
		function performValidation(valURL) {  
		 // alert("Performing validation "+valURL);
		  return new Promise(function(resolve, reject) {  
			var xhr = new XMLHttpRequest();  
			xhr.onreadystatechange = function() {  
				 // alert(this.readyState+" "+this.status);
				  if (this.readyState == 4 && this.status == 200) {
					//  alert(this.responseText);
					  var data = JSON.parse(this.responseText);  
					  
				  }
				  resolve(data);  
			};  
			xhr.onerror = reject;  
			xhr.open('GET', 'https://my-meter.com/dev/public/apple_pay_do?u=' + valURL);  //
			xhr.send();  
		  });  
		} 
 /* session.onshippingcontactselected = function(event) {}
  session.onshippingmethodselected = function(event) {}*/
  
  		session.onshippingcontactselected = function(event) {
		alert('starting session.onshippingcontactselected');
		alert('NB: At this stage, apple only reveals the Country, Locality and 4 characters of the PostCode to protect the privacy of what is only a *prospective* customer at this point. This is enough for you to determine shipping costs, but not the full address of the customer.');
		alert(dump(event));
		alert( event.shippingContact.countryCode);
		getShippingOptions( event.shippingContact.countryCode );
		
		var status = ApplePaySession.STATUS_SUCCESS;
		alert("status : "+status);
		var newShippingMethods = shippingOption;
		var newTotal = { type: 'final', label: 'My Meter', amount: 1 };
		var newLineItems =[{type: 'final',label: 'My Meter', amount: 1 }, {type: 'final',label: 'P&P', amount: 0 }];
		
		session.completeShippingContactSelection(status, newShippingMethods, newTotal, newLineItems );
		
		
	}
	
	session.onshippingmethodselected = function(event) {
		logit('starting session.onshippingmethodselected');
		logit(event);
		
		getShippingCosts( event.shippingMethod.identifier, true );
		
		var status = ApplePaySession.STATUS_SUCCESS;
		var newTotal = { type: 'final', label: 'My Meter', amount: 1 };
		var newLineItems =[{type: 'final',label: 'My Meter', amount: 1 }, {type: 'final',label: 'P&P', amount: 0 }];
		
		session.completeShippingMethodSelection(status, newTotal, newLineItems );
		
		
	}
	
	session.onpaymentmethodselected = function(event) {
		alert('starting session.onpaymentmethodselected');
		alert(dump(event));
		
		var newTotal = { type: 'final', label: 'My Meter', amount: 1 };
		var newLineItems =[{type: 'final',label: 'My Meter', amount: 1 }, {type: 'final',label: 'P&P', amount: 0 }];
		
		session.completePaymentMethodSelection( newTotal, newLineItems );
		
		
	}
	
	session.onpaymentauthorized = function (event) {

		alert('starting session.onpaymentauthorized');
		alert('NB: This is the first stage when you get the *full shipping address* of the customer, in the event.payment.shippingContact object');
		alert(event);

		var promise = sendPaymentToken(event.payment.token);
		promise.then(function (success) {	
			var status;
			if (success){
				status = ApplePaySession.STATUS_SUCCESS;
				document.getElementById("applePay").style.display = "none";
				document.getElementById("success").style.display = "block";
			} else {
				status = ApplePaySession.STATUS_FAILURE;
			}
			
			logit( "result of sendPaymentToken() function =  " + success );
			session.completePayment(status);
		});
	}

	function sendPaymentToken(paymentToken) {
		return new Promise(function(resolve, reject) {
			logit('starting function sendPaymentToken()');
			logit(paymentToken);
			
			logit("this is where you would pass the payment token to your third-party payment provider to use the token to charge the card. Only if your provider tells you the payment was successful should you return a resolve(true) here. Otherwise reject;");
			logit("defaulting to resolve(true) here, just to show what a successfully completed transaction flow looks like");
			if ( debug == true )
			resolve(true);
			else
			reject;
		});
	}
	
	session.oncancel = function(event) {
		//alert('test from cancel');logit('starting session.cancel');
		logit(event);
	}
	
		session.begin();
	alert("after");
		alert(dump(session));  
		
		
	}
	document.getElementById('apple-pay-button').addEventListener('click', beginApplePay);
	
	/*$('#apple-pay-button').click(function(e) {
			alert("Apple pay");
			var request = {
			  countryCode: 'CA',
			  currencyCode: 'CAD',
			  supportedNetworks: ['visa', 'masterCard'],
			  merchantCapabilities: ['supports3DS'],
			  total: { label: 'Your Label', amount: '10.00' },
			}
			var session = new ApplePaySession(2, request); // version number , payment request
			alert(typeof(session));
			alert(JSON.stringify(session));
	});*/
	function logit( data ){
	
	if( debug == true ){
		console.log(data);
	}	

};
</script>
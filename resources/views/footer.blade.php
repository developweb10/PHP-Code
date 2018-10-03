<?php $footer = App\PageContent::where("page_name","footer")->pluck("page_content"); ?>

<div class="container-fluid" id="footer">

	<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 footer-logo">

    	<a href="{{ url('/') }}"><img src="{{ asset('/images/Logo.png') }}" style='height:40px;'></a>

    </div>

    <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">

        @if ( Auth::guest() ) 

            <p class=""><!--smaldisnone-->

                <a href="{{ URL::to('/') }}">Home</a> |
				
                <a href="#" data-toggle="modal" data-target="#openParkingMeterModal">Feed the meter</a> | 

                <a href="{{ URL::to('/faq') }}">FAQ</a> | 

                <a href="{{ URL::to('/contact-us') }}">Contact</a> | 

                <a href="{{ URL::to('/terms') }}">Terms</a> | 

                <a href="{{ URL::to('/privacy') }}">Privacy</a>

            </p>

         @else

             <Br />

        @endif

        <p>@if( $footer != '' ) {{ json_decode($footer)->footer_text  }} @endif</p>

    </div>
	
    
    
	<div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 footer-social-icons margin-top-20">
        @if ( Auth::user() ) 
            <!--<br />-->
        @endif
        <div class="pull-right">
            <ul class="social-ul pull-left">
    
                <li>
    
                    <a href="javascript:open_facebook_window();void(0);" target="_blank">
    
                        <span class="fa-stack fa-lg facebook">
    
                        </span>
    
                    </a>
    
                </li>
    
                <li>
    
                    <a href="javascript:open_linkedin_window();void(0);" target="_blank">
    
                        <span class="fa-stack fa-lg linkedin">
    
                        </span>
    
                    </a>
    
                </li>
    
                <li>
    
                    <a href="javascript:open_twitter_window();void(0);" target="_blank">
    
                        <span class="fa-stack fa-lg twitter">
    
                        </span>
    
                    </a>
    
                </li>
    
            </ul>
            @if ( Auth::guest() ) 
            <p class="pull-left margin-top-20">
                <!--<a href="#" class="small-link sign_up_button pull-right" data-toggle="modal" data-target="#openSignUpModal" data-static="true" data-keyboard="false" style="padding: 10px 41px !important; margin:0;">Order Parking Signs</a>-->
                <a href="#" class="small-link sign_up_button" data-toggle="modal" data-target="#newLotModal" data-static="true" data-keyboard="false">Order Parking Signs</a>
            </p>
          
			@endif
        </div>
    </div>

</div>
<!--
<form id="test_apple">
	<button id='apple-pay-button' lang="en" style="-webkit-appearance: -apple-pay-button; -apple-pay-button-type: buy;"></button> 
</form>
-->
<script>
	
		
/*
		if (window.ApplePaySession) {
			//alert("Apple pay session is set");
		   var merchantIdentifier = 'merchant.com.meter.gurpreet';
		   var promise = ApplePaySession.canMakePaymentsWithActiveCard(merchantIdentifier);
		   promise.then(function (canMakePayments) {
			  if (canMakePayments){
				 // alert("Apple Pay session is set");
				  
			  }else{
				  //alert("FALSE");
			  }
				 // Display Apple Pay Buttons here…
				 $('#activation_steps .checkout_process #proceed_checkout').css('display','none');
		   }); 
		}else{
			//alert("Apple pay session is not set");
		}
		*/
		/*$(document).ready(function(e) {
            //document.getElementById('apple-pay-button').addEventListener('click', beginApplePay);
			$('#apple-pay-button').click(function(e) {
				//alert("Apple pay");
				return false;
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
			});
        });
		*/
		
		
	</script>
<style>

	.footer-logo{ line-height:84px; }

	

	.footer-social-icons .fa-stack.facebook{

		background-size: 129px;

		background-position: -2px -1px;

	}

	.footer-social-icons .fa-stack.linkedin{

		background-size: 129px;

		background-position: -46px -1px;

	}

	.footer-social-icons .fa-stack.twitter {

		background-size: 129px;

		background-position: 39px -1px;

	}

	footer .footer-social-icons ul li{

		padding: 10px 3px 2px 3px;
		/*padding: 25px 5px;*/

	}

	footer .footer-social-icons span.IN-widget{

		top:28px !important;

		left:172px !important;	

	}

	@media screen and (min-width:1170px){

		body #footer.container{

			width: 1170px;

		}

	}

	@media screen and (max-width:480px){

		.footer-social-icons .fa-stack.facebook{

			background-size: 101px !important;

			background-position: -2px -1px !important;

		}

		.footer-social-icons .fa-stack.linkedin{

			background-size: 101px !important;

			background-position: -37px -1px !important;

		}

		.footer-social-icons .fa-stack.twitter {

			background-size: 101px !important;

			background-position: 29px -1px !important;

		}

	}

	

	.footer-logo a{

		line-height: 87px;

		height: 87px;

		display: table-cell;

		vertical-align: middle;

	}

	

	@media screen and (max-width:768px){

		.footer-logo a{

			display: block;

			width: 100%;	

		}

	}

</style>



<script type="text/javascript">

	function open_facebook_window(){

		window.open('https://www.facebook.com/sharer/sharer.php?u={{ URL::to('/') }}','Facebook Share','top=100,width=500,height=400');

	}

	function open_twitter_window(){

		window.open('https://twitter.com/intent/tweet?url={{ URL::to('/') }}&text=My-Meter.com','Twitter Share','top=100,width=500,height=400');

	}

	function open_linkedin_window(){

		window.open('https://www.linkedin.com/cws/share?url={{ URL::to('/') }}&{{ str_random(2) }}','LinkedIn Share','top=100,width=500,height=400');

	}

</script>

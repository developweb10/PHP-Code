<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="en" prefix="og: http://ogp.me/ns#" >

<head>

	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=no; target-densityDpi=device-dpi" />

	<meta name="csrf-token" content="{{ csrf_token() }}" />

	<?php $footer = json_decode(App\PageContent::where("page_name","footer")->pluck("page_content"),true); ?>

	<meta name="author" content="{{ $footer["meta_author"] or "" }}">

	<meta name="description" content="{{ $footer["meta_description"] or "" }}">

	<meta name="keywords" content="{{ $footer["meta_keywords"] or "" }}">

	<title>My Meter</title>

	@include('favicons')

	<?php

		$meta_tags = json_decode(App\PageContent::where("page_name","social_sharing")->pluck("page_content"));

	?>
 
	<meta property="og:image" content="{{ $meta_tags->image or asset('/images/metercar.png') }}" />

	<meta property="og:image:width" content="200" />

	<meta property="og:image:height" content="200" />

	<meta property="og:image" content="{{ $meta_tags->image_linkedIn or asset('/images/metercar.png') }}" /> <!-- 180x110 Image for Linkedin -->

	<meta property="og:image:width" content="180" />

	<meta property="og:image:height" content="150" />
    

	<!-- for Facebook -->          

	<meta property="og:title" content="{{ $meta_tags->title or 'My-meter.com' }}" />

	<meta property="og:type" content="website" />

	<meta property="og:url" content="{{ URL::to('/') }}" />

	<meta property="og:description" content="{{ $meta_tags->description }}" />

	

	<!-- for Twitter -->          

	<meta name="twitter:card" content="summary" />

	<meta name="twitter:title" content="{{ $meta_tags->image or 'My-meter.com' }}" />

	<meta name="twitter:description" content="{{ $meta_tags->description }}" />

	<meta name="twitter:image" content="{{ $meta_tags->image or asset('/images/metercar.png') }}" />

	

	<link href="{{ asset('css/app.css') }}" rel="stylesheet">

	<link href="{{ asset('css/overrides.css') }}" rel="stylesheet">

	<link href="{{ asset('css/datepicker.css') }}" rel="stylesheet">

	<link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

	<link href="{{ asset('vendors/magnific-popup/magnific-popup.css') }}" rel="stylesheet">

	

	<?php /*?><link href="{{ asset('vendors/owl-carousel/assets/owl.carousel.css') }}" rel="stylesheet"><?php */?>

	<link href="{{ asset('/css/responsiveCarousel.min.css') }}" rel="stylesheet">



	<link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel="stylesheet">

	<!-- Fonts -->

	<link href='{{ asset('/css/fonts.css?v=1') }}' rel='stylesheet' type='text/css'>



	<link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->

	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

	<!--[if lt IE 9]>

		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

	<![endif]-->

    

    <style>

		body .container {

			font-family: 'Lato',sans-serif;

		}
		.header-top{

			background-color: rgba(1, 4, 1, 0.42);

			padding: 10px 10px;	

		}

		.header-top img{ 

			float: left; 

			margin-left:10px;

		} 

		.banner-img .header-top .social-ul{

			position: initial;

			float: left;

			line-height: 73px;

			margin-left: 20px;

			margin-bottom:0px;

		}

		.banner-img .header-top .social-ul li{

			position:relative;	

		}

		.header-top .navbar{

			float:right;

			width: 47%;

			margin-top: 12px;

			margin-bottom: 0px;

		}

		body .header-top .navbar a{

			color: #fff;

			font-weight: 600;
		    font-family: "Open Sans", sans-serif;
			padding-left:10px;
			padding-right:10px;

		}
		#navbar .nav > li > a:hover, #navbar .nav > li > a:focus{
			background:none;
			background-color:transparent;
			outline:none;
		}
		.header-top .navbar a:hover{

			color: #fff;	
			opacity:1;
			background:none;
			text-decoration:underline;

		}

		.banner-img .header-top .nav{

			position: initial;	

		}
 
		.content-container {

			/*position: relative;

			top: -265px;*/

		}

		.content-container .steps-container{ 

			/*background-color: rgba(1, 4, 1, 0.42);*/

			color: #fff; 
			margin-top: 52%;
			/*width:100%;*/
		}

	</style>

   	<style>

		.sliderbodyclass .crsl-item .thumbnail img{     max-width: 160px; width: 160px; min-height:160px; height:160px; border-radius: 88px; }

		.sliderbodyclass .crsl-item .thumbnail{ border:none; }

		.sliderbodyclass .slidernav a{ 

			background-color: #fff;

			box-shadow: none;

		}

		.sliderbodyclass .slidernav a i{

			font-size: 40px;

		}
		body.welcome .banner-img{ background-image:none;
			    z-index: 1111 !important;
		 }

		body.welcome .panel{

			margin-bottom: 0px;

		}

		body.welcome  .content-container {	padding-bottom: 0%; }

		body.welcome  .content-container .container-fluid{

			margin-top: 15px;

			margin-bottom: 15px;

		}

		@media screen and (max-width:768px)

		{
			
			body.welcome .banner-img {
				position:fixed;

			}
			body.welcome{ background-image:none; /*background-color: #fff !important;*/ }	

			body.welcome .header-top{

				background-color:transparent;

				padding:0px;

				color: #333;

			}

			.header-top .navbar{ width:96px; margin-top:0px; }

			.content-container .steps-container{
				  	/*margin-top: 0px;*/
					background-color: transparent;	 
			}
		

			.header-top .navbar{ width:100%; }

			.banner-img .header-top .social-ul{

				    margin-left: 0px;

			}

			.banner-img .header-top > a, .banner-img .header-top > .social-ul{

				display:none;

				text-align:center;	

			}

			.banner-img a img{ float:none; }

			.panel-default > .panel-heading{

				display:block;

				text-align:center;	

			}
			

			body.welcome .content-container .container-fluid{  

				margin-top: 0px;

				margin-bottom: 0px;

			}

			body.welcome .content-container .container-fluid > div > div{  

				margin: 0px;

				padding: 0px;

			}

			@if ( Auth::guest() ) 

				.navbar-toggle{

					 padding: 0px 0px !important;	

					 margin-left: 15px;

				}

			@endif

			.panel, body.welcome .banner-img{

				background-color: #fff !important;	

			}

		}
		@media screen and (max-width: 736px){
			.banner-img .navbar-collapse ul li{
				display:block;
				float:left;
				width:100%;
			}
			.paddinlrsmall {
				padding: 0 15px;
			}
			#customer_pay_form input::-webkit-input-placeholder {
			  color: #fff;
			}
			#customer_pay_form{
				padding: 8% 0 8% 0;
			}
			#customer_pay_form input[name="meter_id"], #customer_pay_form select[name='expiry_time'], .sleectText, form button, input[name="cc_number"], select.select_center_text {
				height: 65px !important;
			}
			body #customer_pay_form .form-control, button.expiry_time , #pay_button{
				    color: #fff !important;
					border: 3px solid #fff !important;
					background: transparent;
					/*padding: 8%;*/
					width: 82%;
					margin: 0 auto;
					border-radius:3px;
					font-size: 23px !important;
			}
			body.welcome .banner-img{ /* .panel,  */

				/*background-color: #87D236 !important;*/

			}
		}
		@media screen and (max-width: 600px){
			
			.content-container .steps-container{ 
				margin-top: 52%;
				color: #fff;
			}
		}

		@media screen and (max-width: 480px){

			body.welcome .banner-img {

			    min-height: 60px !important;

			}

		}

	</style>
       
    <link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>

	<script src="{{ asset('/js/jquery-1.11.3.min.js') }}" type="text/javascript"></script>

	<!--<script src="{{ asset('/js/jssor.slider-21.1.6.mini.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
        jQuery(document).ready(function ($) {
            
            var jssor_1_SlideoTransitions = [
              [{b:-1,d:1,o:-1},{b:0,d:1000,o:1}],
              [{b:1900,d:2000,x:-379,e:{x:7}}],
              [{b:1900,d:2000,x:-379,e:{x:7}}],
              [{b:-1,d:1,o:-1,r:288,sX:9,sY:9},{b:1000,d:900,x:-1400,y:-660,o:1,r:-288,sX:-9,sY:-9,e:{r:6}},{b:1900,d:1600,x:-200,o:-1,e:{x:16}}]
            ];
            
            var jssor_1_options = {
              $AutoPlay: true,
              $SlideDuration: 800,
              $SlideEasing: $Jease$.$OutQuint,
              $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions
              },
              $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
              },
              $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
              }
            };
            
            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);
            
            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizing
            function ScaleSlider() {
                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                if (refSize) {
                    refSize = Math.min(refSize, 1920);
                    jssor_1_slider.$ScaleWidth(refSize);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }
            ScaleSlider();
            $(window).bind("load", ScaleSlider);
            $(window).bind("resize", ScaleSlider);
            $(window).bind("orientationchange", ScaleSlider);
            //responsive code end
        });
        
    </script>-->
    <!--
    <style>
        /* jssor slider bullet navigator skin 05 css */
        /*
        .jssorb05 div           (normal)
        .jssorb05 div:hover     (normal mouseover)
        .jssorb05 .av           (active)
        .jssorb05 .av:hover     (active mouseover)
        .jssorb05 .dn           (mousedown)
        */
        .jssorb05 {
            position: absolute;
        }
        .jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
            position: absolute;
            /* size of bullet elment */
            width: 16px;
            height: 16px;
			
            background: url("{{ asset('/images/b05.png') }}") no-repeat;
            overflow: hidden;
            cursor: pointer;
        }
        .jssorb05 div { background-position: -7px -7px; }
        .jssorb05 div:hover, .jssorb05 .av:hover { background-position: -37px -7px; }
        .jssorb05 .av { background-position: -67px -7px; }
        .jssorb05 .dn, .jssorb05 .dn:hover { background-position: -97px -7px; }
        
        /* jssor slider arrow navigator skin 22 css */
        /*
        .jssora22l                  (normal)
        .jssora22r                  (normal)
        .jssora22l:hover            (normal mouseover)
        .jssora22r:hover            (normal mouseover)
        .jssora22l.jssora22ldn      (mousedown)
        .jssora22r.jssora22rdn      (mousedown)
        */
        .jssora22l, .jssora22r {
            display: block;
            position: absolute;
            /* size of arrow element */
            width: 40px;
            height: 58px;
            cursor: pointer;
            background: url("{{ asset('/images/a22.png') }}") center center no-repeat;
            overflow: hidden;
        }
        .jssora22l { background-position: -10px -31px; }
        .jssora22r { background-position: -70px -31px; }
        .jssora22l:hover { background-position: -130px -31px; }
        .jssora22r:hover { background-position: -190px -31px; }
        .jssora22l.jssora22ldn { background-position: -250px -31px; }
        .jssora22r.jssora22rdn { background-position: -310px -31px; }
		.slidermainouterdiv{
			
			width:100%;
			float:left;
			max-height: 580px;
			}
    </style>
    -->
    <style>
	
		#slideshow img{
		  position: fixed;
		  top: 0em;
		  left: 0;
		  width: 100%;
		  z-index:-9999;
		  display:block !important;
		}
		
		header {
		  font-family: Arial, Helvetica, sans-serif;
		  text-align:center;
		  height: 10em;
		  padding: 2em;
		} 
		#overlay {
			/* position: fixed; */
			/* z-index: 99999; */
			top: 0;
			left: 0;
			bottom: 0;
			right: 0;
			margin: 0 auto;
			text-align: center;
			background: rgba(14, 14, 14, 0.73);
			transition: 1s 0.4s;
			/*min-height: 800px;*/
			float: left;
			width: 100%;
		}
		#loading_img {
			width: 125px;
			height: 125px;
			margin: 0 auto;
			position: absolute;
			left: 0;
			right: 0;
			top: 52%;
		}
		body #loading_img img{
			margin: 0 auto;
		}

	
	</style>
    
    <script src="{{ asset('/js/jquery-1.11.3.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/jssor.slider-21.1.6.mini.js') }}" type="text/javascript"></script> 
	
	<?php 
		$home_page = json_decode(App\PageContent::where("page_name","home")->pluck("page_content"),true); 

		//$home_slider_images = explode(",",$home_page["section0"]);
		 $amount = Session::has('amount') ? Session::get('amount') : '0.00';
		 Session::forget('amount'); // distroy the amount set in session session 
	?>
    
    
    <?php /* ?>
    <script>
	
		var duration = <?php if(isset($home_page["transition"]) && !empty($home_page["transition"])) { echo $home_page["transition"]; } else { echo 20; }?>;  // duration in seconds
		var fadeAmount = 0.3; // fade duration amount relative to the time the image is visible
	
		$(document).ready(function (){
			//alert(jQuery('#slideshow').height());
			var overlay_height = jQuery('.content-container').offset().top;
			jQuery('#overlay').fadeIn('fast');
			jQuery('#overlay').css('height',overlay_height);
			
		  var images = $("#slideshow img");
		  var numImages = images.size();
		  var durationMs = duration * 1000;
		  var imageTime = durationMs / numImages; // time the image is visible 
		  var fadeTime = imageTime * fadeAmount; // time for cross fading
		  var visibleTime = imageTime  - (imageTime * fadeAmount * 2);// time the image is visible with opacity == 1
		  var animDelay = visibleTime * (numImages - 1) + fadeTime * (numImages - 2); // animation delay/offset for a single image 
		  
		  images.each( function( index, element ){
			
			if(index != 0){
			  $(element).css("opacity","0");
			  setTimeout(function(){
				doAnimationLoop(element,fadeTime, visibleTime, fadeTime, animDelay);
			  },visibleTime*index + fadeTime*(index-1));
			}else{
			  setTimeout(function(){
				$(element).animate({opacity:0},fadeTime, function(){
				  setTimeout(function(){
					doAnimationLoop(element,fadeTime, visibleTime, fadeTime, animDelay);
				  },animDelay )
				});
			  },visibleTime);
			}
		  });
		});
	
		// creates a animation loop
		function doAnimationLoop(element, fadeInTime, visibleTime, fadeOutTime, pauseTime){
		  fadeInOut(element,fadeInTime, visibleTime, fadeOutTime ,function(){
			setTimeout(function(){
			  doAnimationLoop(element, fadeInTime, visibleTime, fadeOutTime, pauseTime);
			},pauseTime);
		  });
		}
	
		// shorthand for in- and out-fading
		function fadeInOut( element, fadeIn, visible, fadeOut, onComplete){
		  return $(element).animate( {opacity:1}, fadeIn ).delay( visible ).animate( {opacity:0}, fadeOut, onComplete);
		}
	
	
	</script>
	<?php */ ?>
	<!-- Purchase event will work if user make an order otherwise pageview event will works -->
    
    @if($amount == '0.00')
    
    	<!-- Facebook Pixel Code -->

		<script>
    
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
    
        
    
        fbq('init', '1068883966491728');
    
        fbq('track', "PageView");
    
        </script>
    
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1068883966491728&ev=PageView&noscript=1" /></noscript>
    
        <!-- End Facebook Pixel Code -->
        
    @else
    	
        @include('includes.fb_pixel_purchase')
        
    @endif
        
  	<!-- Google Code for My-Meter Signups Conversion Page -->

    <script type="text/javascript">

    /* <![CDATA[ */

    var google_conversion_id = 980977799;

    var google_conversion_language = "en";

    var google_conversion_format = "3";

    var google_conversion_color = "ffffff";

    var google_conversion_label = "I2lgCILAwGYQh5Hi0wM";

    var google_remarketing_only = false;

    /* ]]> */

    </script>

    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">

    </script>

    <noscript>

    <div style="display:inline;">

    <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/980977799/?label=I2lgCILAwGYQh5Hi0wM&amp;guid=ON&amp;script=0"/>

    </div>

    </noscript>  

</head>

<body class="welcome">

	@include('includes.analyticstracking')
	
    <script>
		/*if (window.ApplePaySession) {
			//alert("Apple pay session is set");
		   var merchantIdentifier = 'merchant.com.meter.gurpreet';
		   var promise = ApplePaySession.canMakePaymentsWithActiveCard(merchantIdentifier);
		   promise.then(function (canMakePayments) {
			  if (canMakePayments){
				  alert("TRUE");
			  }else{
				  alert("FALSE");
			  }
				 // Display Apple Pay Buttons here…
		   }); 
		}else{
			alert("Apple pay session is not set");
		}*/
	</script>

	<!-- Image for linkediN share, should be hidden -->

	<img alt="Meter Car" src="{{ $meta_tags->image_linkedIn or asset('/images/metercar.png') }}" style="display:none;">

	@if ( Session::has('register_order_success'))
    	
        @include('includes.register_order_success')
        
    @endif
    
    @include('agree_to_terms_modal')
	
    @if (count($errors) > 0)

        <div id="errorsModal" class="modal fade" role="dialog" >
    
            <div class="modal-dialog">
    
                <div class="modal-content">
    
                    <div class="modal-header">
    
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
    
                        <h4 class="modal-title text-center"><i class="fa fa-hand-o-down"></i> Warning</h4>
    
                    </div>
    
                    <div class="modal-body">
    
                        <div class="alert alert-danger">
    
                            @foreach ($errors->all() as $error)
    
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    
                                    <i class="fa fa-arrow-right"></i> {{ $error }}
    
                                </div>
    
                            @endforeach
    
                            <div class="clearfix"></div>
    
                        </div>
    
                    </div>
    
                </div>
    
            </div>
    
        </div>
    
        <script type="application/javascript">
    
            document.addEventListener( "DOMContentLoaded", function(){
    
                $("#errorsModal").modal("show");
    
            }, false );
    
        </script>

    @endif

    @if (count($errors) > 0)

        <div id="errorsModal" class="modal fade" role="dialog" >
    
            <div class="modal-dialog">
    
                <div class="modal-content">
    
                    <div class="modal-header">
    
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
    
                        <h4 class="modal-title text-center"><i class="fa fa-bars"></i> Errors</h4>
    
                    </div>
    
                    <div class="modal-body">
    
                        <div class="alert alert-danger">
    
                            <strong>Whoops!</strong> There were some problems with your input.
    
                            <br clear="all"><hr>
    
                            @foreach ($errors->all() as $error)
    
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    
                                    <i class="fa fa-arrow-right"></i> {{ $error }}
    
                                </div>
    
                            @endforeach
    
                            <div class="clearfix"></div>
    
                        </div>
    
                    </div>
    
                </div>
    
            </div>
    
        </div>
    
        <script type="application/javascript">
    
            document.addEventListener( "DOMContentLoaded", function(){
    
                $("#errorsModal").modal("show");
    
            }, false );
    
        </script>

    @endif

	<div class=" clearfix banner-img">

    	<div class="header-top">

			<a href="{{ url('/') }}"><img alt="Logo" src="{{ asset('/images/Logo.png') }}" style='height:60px;'></a>

            

            <ul class="social-ul">

                <li>
					<?php if(!isset($_SESSION["payment_id"])){ ?>
                    <a href="javascript:open_facebook_window();void(0);" target="_blank">

                        <span class="fa-stack fa-lg facebook">

                        </span>

                    </a>
                    <?php }else{ ?>
					
					<a href="redirect" class="facebook_share"> 

                        <span class="fa-stack fa-lg facebook">

                        </span>

                    </a>
                    <?php } ?>
					

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

            

            <nav class="navbar">

                @include("navigation")

            </nav>

            

            <div class="clearfix"></div>

        </div>

        

      

      

        

		<?php /*?><div class="login-image">

			<a href="#" data-toggle="modal" data-target="#openLoginModal" data-static="true" data-keyboard="false" class="text-center">LOG IN<br><img src="{{ asset('/images/login-key.png') }}" ></a>

		</div><?php */?>

	</div>
    
    @if (!config('is_mobile'))  
        <div>
            <!--<span id="overlay" style="display:none;">
                <span id="loading_img"><img src="{{ asset('/images/load.gif') }}" width="50px" height="50px" /></span>
              </span>-->
            <div id="slideshow">
        
                     <?php /* foreach($home_slider_images as $img) { ?>
                    <div data-p="225.00">
                        <img data-u="image" src="<?php echo $img; ?>" style="display:none;" />
                       
                    </div>
                    <?php } */ ?>
					<img alt="Section0" src="<?php echo $home_page["section0"]; ?>" />                      
            </div>
        </div>
    @endif
	<div class="content-container">

		@yield('content')

	</div>

	

	

	<footer>

		@include("footer")

    </footer>

	<!-- Scripts -->

    

	<script type="text/javascript">

		var home_url = "{{ url('/home') }}";

    </script>

	<script src="{{ asset('/js/jquery.min.js') }}"></script>

	<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    
	<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
	
	<script src="{{ asset('vendors/magnific-popup/jquery.magnific-popup.min.js')}}"></script>

	<!--<script src="{{ asset('vendors/owl-carousel/owl.carousel.min.js')}}"></script>-->

	<script src="{{ asset('/js/datepicker.js') }}"></script>

	<script src="{{ asset('/js/moment-with-locales.js') }}"></script>

	<script src="{{ asset('/js/bootstrap-datetimepicker.js') }}"></script>

	<script src="{{ asset('/js/bootstrap-timepicker.js') }}"></script>

	

	<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>

	

	<script type="text/javascript" src="{{ asset('/js/responsiveCarousel.min.js') }}"></script>



	<script src="{{ asset('/js/chart.js') }}"></script>

	<script src="{{ asset('/js/config.js') }}"></script>

	<script src="{{ asset('/js/app.js') }}"></script>

	<script type="text/javascript">

		$(document).ready(function () {
			
			var fb_window = '';
		   $('.facebook_share').on('click',function(e){
				e.preventDefault()
				// Open new tab - in old browsers, it opens a popup window
				fb_window = window.open($(this).attr('href'),'popUpWindow','height=500,width=500,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
				
				//fb_window = window.open($(this).attr('href'),'_blank');
				
				
				// reload current page
				//location.reload();
		   });

			//alert($('html').html());

			$('.datepicker').datepicker({

				format: "dd/mm/yyyy"

			});





			$('.popup-youtube').magnificPopup({

	          disableOn: 700,

	          type: 'iframe',

	          mainClass: 'mfp-fade',

	          removalDelay: 160,

	          preloader: false,

	          fixedContentPos: false

	        });



			  $('.crsl-items').carousel({

					visible: 3,

					itemMinWidth: 180,

					itemEqualHeight: 370,

					itemMargin: 50,

					autoRotate: 5000,

					speed:	'slow'

			  });

				  

			  $(".sliderbodyclass a[href=#]").on('click', function(e) {

					e.preventDefault();

			  });





   <?php /*?> 	

			$('.owl-carousel').owlCarousel({

	    	    loop:true,

			    margin:10,

			    nav:true,

			    dots:false,

			    items:3,

			    navText:['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>']

	        });

	<?php */?>

		});

 	</script>

	@yield('additionalJS')   

    @if( !(isset($is_home) and $is_home) )

    	<style>

			body.welcome{

				background-color: #041704;	

			}

			.panel{

				background-color: rgba(255, 255, 255, 0.94);	

			}

			/*.panel-body{

				min-height: 518px;

			}*/

		</style>

    @endif
    
 	<div id="newLotModal" class="modal fade landowner_first_UI" role="dialog" style="">

	<div class="modal-dialog">

		<div class="modal-content">
    
			@include('includes.landowner_step_ui')
        </div> 
          
	</div>
    
 </div>

</body>

</html>


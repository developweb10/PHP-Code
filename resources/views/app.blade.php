<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="en">

<head>

	<meta charset="utf-8">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

	<meta name="csrf-token" content="{{ csrf_token() }}" />



	<title>My Meter</title>



	@include('favicons')

	

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<link href="{{ asset('/css/overrides.css') }}" rel="stylesheet">

	<link href="{{ asset('/css/datepicker.css') }}" rel="stylesheet">

	<link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

	<link href="{{ asset('/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">

	<link href="{{ asset('/css/bootstrap-timepicker.css') }}" rel="stylesheet">
    
    <link href="{{ asset('/js//date_piker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

	

	<link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel="stylesheet">

	<link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet"></link>



	<!-- Fonts -->

	<link href='{{ asset('/css/fonts.css?v=1') }}' rel='stylesheet' type='text/css'>

	<link href='//fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
    
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
    
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    
   <!-- <link rel="stylesheet" type="text/css" href="{{ asset('/css/jquery.qtip.css') }}" />-->

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->

	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

	<!--[if lt IE 9]>

		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

	<![endif]-->
    
    <?php 
		$amount = Session::has('amount') ? Session::get('amount') : '0.00';
		 Session::forget('amount'); // distroy the amount set in session session
		 //echo (Session::has('amount') ? Session::get('amount') : 'I am unset');
	?>
    @if($amount)
   		@include('includes.fb_pixel_purchase')
    @endif

</head>



<body class="app @if( Auth::user() and Auth::user()->role_id == 1 ) admin @endif">
	
    <noscript>Sorry, your browser does not support JavaScript!</noscript>
    
    @include('includes.analyticstracking')
    
	<?php

		$mobile = config('is_mobile');
		$Ipad = config('is_Ipad');
	
	?>
	<div class="@if(  Auth::Guest() || Auth::user() && Auth::user()->role_id != 1 ) container @endif">

	@if( Auth::Guest() )

		<div class=" clearfix banner-img">

			<ul style='bottom: 43%;left: 18%;' class="logo-ul">

				<img alt="Logo" src="{{ asset('/images/Logo.png') }}" style='height:60px;'>

			<?php /*?><li ><span><img src="{{ asset('/images/whitecar.png') }}" style='width:60px;'></span><span style='color: white;font-size: 30px;position: relative;top: 17px;'>MY-METER.com</span></li><?php */?>

			

			</ul>

			<ul class="social-ul">

				<li>

					<a href="#">

					<span class="fa-stack fa-lg facebook">

					  <?php /*?><i class="fa fa-circle fa-stack-2x"></i>

					  <i class="fa fa-facebook fa-stack-1x fa-inverse"></i><?php */?>

					</span>

					</a>

				</li>

				<li>

					<a href="#">

						<span class="fa-stack fa-lg linkedin">

						  <?php /*?><i class="fa fa-circle fa-stack-2x"></i>

						  <i class="fa fa-linkedin fa-stack-1x fa-inverse"></i><?php */?>

						</span>

					</a>

				</li>

				<li>

					<a href="#">

						<span class="fa-stack fa-lg twitter">

						  <?php /*?><i class="fa fa-circle  fa-stack-2x"></i>

						  <i class="fa fa-twitter fa-stack-1x fa-inverse"></i><?php */?>

						</span>

					</a>

               

				</li>

			</ul>

		</div>

	@endif


    <nav class="navbar navbar-default @if( Auth::user() and Auth::user()->role_id == 1 ) admin_nav @endif">
        <div class="@if( Auth::user() and Auth::user()->role_id == 1 ) container @endif">
            @include("navigation")
        </div>

    </nav>

	<div class="@if( Auth::user() and Auth::user()->role_id == 1 ) container @endif">
	
        <div class="content-container">
    
            @yield('content')
    
        </div>

	</div>

	</div>



	<footer>

		@include("footer")

    </footer>

	

	<div class='loaderimg'>

		<span>

			<img alt="Loading" src={{ asset('/images/loading.gif') }}>

		</span>

	</div>



	<!-- Scripts -->

	<script type="text/javascript">var home_url = "{{ url('/home') }}"; var server_date = "{{ date('Y-m-d h:i A') }}";</script>

	<script src="{{ asset('/js/jquery.min.js') }}"></script>
    
	<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
    
	<script src="{{ asset('/js/bootstrap.min.js') }}"></script>

	<script src="{{ asset('/js/datepicker.js') }}"></script>

	<script src="{{ asset('/js/moment-with-locales.js') }}"></script>

	<script src="{{ asset('/js/bootstrap-datetimepicker.js') }}"></script>

	<script src="{{ asset('/js/bootstrap-timepicker.js') }}"></script>
    <script src="{{ asset('/js/date_piker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('/js/date_piker/js/bootstrap-datetimepicker.fr.js') }}"></script>
    <script src="{{ asset('/js/date_piker/js/bootstrap-datetimepicker.js') }}"></script>

	<script src="{{ asset('/js/chart.js') }}"></script>

	<script src="{{ asset('/js/config.js') }}"></script>
    
    <!--<script type="text/javascript" src="{{ asset('/js/jquery.qtip.js') }}"></script>-->
	
    <!--<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>-->
	<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
	<!--<script src="{{ asset('/js/jquery.creditCardValidator.js') }}"></script>-->

	

	<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>

	

	<script type="text/javascript" src="{{ asset('/js/jspdf.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('/js/html2canvas.min.js') }}"></script>

	<script type="text/javascript" src="{{ asset('/js/app_pdf.js') }}"></script>

		

	<script src="{{ asset('/js/app.js') }}"></script>
	
    <!-- Custom js file -->
    
    <script src="{{ asset('/js/custom.js') }}"></script>
    
	<script src="{{ asset('/js/bootstrap-toggle.min.js') }}"></script>

	@yield('additionalJS')

	

    <style type="text/css">

		body {

			font-family: 'Lato' !important;

		}

		.panel-default{

			border-top: 0px;

		}

		body:not(.welcome) .navbar-nav > li:not(.li-logo) > a, #bs-example-navbar-collapse-1 {

			padding-left: 0px;

			padding-right: 0px;

		}

	</style>



	

</body>

</html>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}" />

	<?php $footer = json_decode(App\PageContent::where("page_name","footer")->pluck("page_content"),true); ?>
	<meta name="author" content="{{ $footer["meta_author"] or "" }}">
	<meta name="description" content="{{ $footer["meta_description"] or "" }}">
	<meta name="keywords" content="{{ $footer["meta_keywords"] or "" }}">
	
	<title>Rent Out My Parking Space</title>
	
	@include('favicons')
	
	<?php
		$meta_tags = json_decode(App\PageContent::where("page_name","social_sharing")->pluck("page_content"));
	?>

	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/overrides.css') }}" rel="stylesheet">
	<link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
	
	<?php /*?><link href="{{ asset('vendors/owl-carousel/assets/owl.carousel.css') }}" rel="stylesheet"><?php */?>
	<link href='{{ asset('/css/fonts.css') }}' rel='stylesheet' type='text/css'>


	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="welcome">
	<nav class="navbar navbar-default">
		@include("navigation")
	</nav>
	<div class="content-container">
        <div class="container">
        	@include("includes.welcome-partial")
        </div>
	</div>
	
	<footer>
		@include("footer")
    </footer>
	<!-- Scripts -->
	<script type="text/javascript">var home_url = "{{ url('/home') }}";</script>
	<script src="{{ asset('/js/jquery.min.js') }}"></script>
	<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
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
	</style>
	
	@yield('additionalJS')
	
</body>
</html>

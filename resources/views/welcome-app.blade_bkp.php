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
	
	<title>My Meter</title>
	
	@include('favicons')
	
	<?php
		$meta_tags = json_decode(App\PageContent::where("page_name","social_sharing")->pluck("page_content"));
	?>
	<!-- for Facebook -->          
	<meta property="og:title" content="{{ $meta_tags->title or 'My-meter.com' }}" />
	<meta property="og:type" content="website" />
	<meta property="og:image" content="{{ $meta_tags->image or asset('/images/metercar.png') }}" />
	<meta property="og:url" content="{{ URL::to('/') }}" />
	<meta property="og:description" content="{{ $meta_tags->description }}" />
	
	<!-- for Twitter -->          
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:title" content="{{ $meta_tags->image or 'My-meter.com' }}" />
	<meta name="twitter:description" content="{{ $meta_tags->description }}" />
	<meta name="twitter:image" content="{{ $meta_tags->title or asset('/images/metercar.png') }}" />
	

	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/overrides.css') }}" rel="stylesheet">
	<link href="{{ asset('css/datepicker.css') }}" rel="stylesheet">
	<link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ asset('vendors/magnific-popup/magnific-popup.css') }}" rel="stylesheet">
	
	<?php /*?><link href="{{ asset('vendors/owl-carousel/assets/owl.carousel.css') }}" rel="stylesheet"><?php */?>
	<link href="{{ asset('/css/responsiveCarousel.min.css') }}" rel="stylesheet">

	<link href="{{ asset('/css/jquery.dataTables.min.css') }}" rel="stylesheet">
	<!-- Fonts -->
	<link href='{{ asset('/css/fonts.css') }}' rel='stylesheet' type='text/css'>


	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="welcome">
	<div class=" clearfix banner-img">
		<ul style='bottom: 43%;left: 12%;' class="logo-ul">
			<img src="{{ asset('/images/Logo.png') }}" style='height:60px;'>
		<?php /*?><li ><span><img src="{{ asset('/images/whitecar.png') }}" style='width:60px;'></span><span style='color: white;font-size: 30px;position: relative;top: 17px;'>MY-METER.com</span></li><?php */?>
		
		</ul>
      
      
		<ul class="social-ul">
			<li>
				<a>
               <span class="fa-stack fa-lg facebook">
                 <?php /*?><i class="fa fa-circle fa-stack-2x"></i>
                 <i class="fa fa-facebook fa-stack-1x fa-inverse"></i><?php */?>
               </span>
               <iframe src="https://www.facebook.com/plugins/share_button.php?href={{ URL::to('/') }}&layout=button&mobile_iframe=true&width=57&height=20&appId" width="57" height="40" style="border:none;overflow:hidden;opacity: 0;position: absolute;left: -5px;height: 40px;top: 10px;" scrolling="no" frameborder="0" allowTransparency="true"></iframe>

				</a>
			</li>
			<li>
				<a href="#">
					<span class="fa-stack fa-lg linkedin">
					  <?php /*?><i class="fa fa-circle fa-stack-2x"></i>
					  <i class="fa fa-linkedin fa-stack-1x fa-inverse"></i><?php */?>
					</span>
				</a>
           		 <script type="text/javascript" src="http://platform.linkedin.com/in.js"></script>
				<script type="in/share" data-url="{{ URL::to('/') }}?{{ str_random(2) }}"></script>
			</li>
			<li>
         <style>
				span.IN-widget {
					 opacity: 0;
					 position: absolute;
					 top: 11px;
				    width: 46px;
				}
			</style>
				<a href="#">
					<span class="fa-stack fa-lg twitter">
					  <?php /*?><i class="fa fa-circle  fa-stack-2x"></i>
					  <i class="fa fa-twitter fa-stack-1x fa-inverse"></i><?php */?>
					</span>
               
				</a>
            <div style="    position: absolute;    top: 16px;    opacity: 0;">
	            <a href="https://twitter.com/share" class="twitter-share-button" data-url="{{ URL::to('/') }}" data-text="My-Meter.com" data-dnt="true">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				</div>
			</li>
		</ul>
		<div class="login-image">
			<a href="#" data-toggle="modal" data-target="#openLoginModal" data-static="true" data-keyboard="false" class="text-center">LOG IN<br><img src="{{ asset('/images/login-key.png') }}" ></a>
		</div>
	</div>
	<nav class="navbar navbar-default">
		@include("navigation")
	</nav>
	<div class="content-container">
		@yield('content')
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

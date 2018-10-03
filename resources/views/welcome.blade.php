

@extends('welcome-app')

@section('content')


<?php
	//echo "WELCOME<pre>";
	//print_r($data); echo "</pre>"; exit();
?>

    @include("includes.welcome-partial")

	<?php /*?><div class="row clearfix your-dashboard text-centered">

		<h1>Your Dashboard</h1>

		<ul class="nav nav-tabs" role="tablist">

			<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Meters</a></li>

			<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Reports</a></li>

			<li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Signage</a></li>

			<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Account</a></li>

			<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Support</a></li>

		</ul>

		<div class="tab-content">

			<div role="tabpanel" class="tab-pane active" id="home"> </div>

			<div role="tabpanel" class="tab-pane" id="profile"> </div>

			<div role="tabpanel" class="tab-pane" id="messages"> </div>

			<div role="tabpanel" class="tab-pane" id="settings"> </div>

		</div>

	</div><?php */?>



@if( !empty($data->section5->image) )

	<div class="clearfix"></div>

    <img class="img-responsive" src="{{ $data->section5->image or "" }}" alt="image" style="width:100%;"/>

	<div class="clearfix"></div>

@endif



<div class="testimonial-section">

<div class="container">

	<?php 

		$testimonials = App\PageContent::where("page_name","testimonials")->get(); 

		$testimonials = json_decode($testimonials[0]->page_content,true)["testimonials"];

	?>

	<div id="testimonials" class="row clearfix testimonials text-centered" style='  padding-bottom: 60px;'>

    	<h1>What Our Members Are Saying</h1>

		<div class='sliderbodyclass'>

			<div id="w">

				<nav class="slidernav">

					<div id="navbtns" class="clearfix"> 

						<a href="#" class="previous">

							<i class="fa fa-angle-left icon" style="font-size:75px;"></i>

						</a> 

						<a href="#" class="next">

							<i class="fa fa-angle-right icon" style="font-size:75px;"></i>

						</a> 

					</div>

				</nav>

				<div class="crsl-items" data-navigation="navbtns">

					<div class="crsl-wrap">

						@if( count($testimonials) )

							@foreach( $testimonials as $test  )

								<div class="crsl-item">

									<div class="thumbnail"> <img src="{{ $test['image'] }}" alt="" style="max-width:260px;"> </div>

									<p>{{ $test['quotes'] }}</p>

									<h3><a href="#">{{ $test['auther_name'] }}</a></h3>

								</div>

							@endforeach

						@endif

					</div>

					<!-- @end .crsl-wrap -->

				</div>

				<!-- @end .crsl-items -->

			</div>

			<!-- @end #w -->

		</div>

	</div>

	<?php /*?><div class="row clearfix steps-container clearfix" style='border-bottom:none;padding-top:40px;'>

		<h1>{{ $data->section4->heading }}</h1>

		<br/>

		<div class="col-md-2 report-bordered steps col-centered">

			<input type="text" placeholder="Meter#" class="btn btn-success full-width">

		</div>

		<div class="col-md-2 report-bordered steps col-centered">

			<input type="email" placeholder="Time" class="btn btn-success full-width">

			<img src="{{ asset('/images/small-clock-icon.png') }}" style='position: absolute;right: 37px;top: 12px;opacity: 0.3;'> </div>

		<div class="col-md-2 report-bordered steps last col-centered">

			<input type="submit" value="Checkout" class="btn btn-green btn-success full-width"  style='color: white;background: #87D236 !important;'>

		</div>

		<br/>

		<br/>

		{{ $data->section4->description }}

	</div><?php */?>

</div>

</div>

@if ((!config('is_Ipad')) && config('is_mobile')) 
<div class="row" style="margin-left:0; margin-right:0;">
	<div class="col-xs-12 text-centered" style="background:#fff;">
    	 <a href="#" data-toggle="modal" data-target="#newLotModal"><img src="{{asset('images/mobplace_order.png')}}" alt="" style="max-width:260px;"></a>
	</div>
</div>
<div class="row" style="margin-left:0; margin-right:0;">
	<div class="col-xs-12 text-centered socials_container">
			<ul class="social-ul">
    
                <li>
    
                    <a href="javascript:open_facebook_window();void(0);" target="_blank">
    
                        <i class="fa fa-facebook mob_socials"></i>
    
                    </a>
    
                </li>
    
                <li>
    
                    <a href="javascript:open_linkedin_window();void(0);" target="_blank">
    				  
                       <i class="fa fa-linkedin mob_socials"></i>
    
                    </a>
    
                </li>
    
                <li>
    
                    <a href="javascript:open_twitter_window();void(0);" target="_blank">
    
                        <i class="fa fa-twitter mob_socials"></i>
    
                    </a>
    
                </li>
    
            </ul>
      </div> 
  </div>         
  @endif          

<style>

	.mob_socials{
		color: #fff;
		background: gray;
		font-size: 27px;
		line-height: 54px;
		width: 55px;
		border-radius: 50%;
		height: 55px;
	}

	#navbtns{ position: relative; width:100%; }

	a.previous, a.next{

		position: absolute;

		top: 150px;

		z-index: 111111;

		width: 50px;

		background:transparent !important;

	}

	

	a.previous{

		left: -56px;

	}

	a.next{

		right: -56px;

	}

	body.welcome .container{

	    width: 970px;

	}

	@media (max-width: 2200px) {
		
	.vertical-center-table .float_left {
		float: left !important;
		transform: translateY(32%);
	}
		
	}
	
	@media (max-width: 2200px) and (min-width: 420px) {

	.vertical-center-table .float_right{
		float:right !important;
	}	
	.vertical-center-table .float_left{
		float:left !important;
	}		

	}

	@media (max-width: 1060px) {

		a.previous{

			left: -23px;

		}

		a.next{

			right: -23px;

		}

	}

	@media (max-width: 768px) {

		a.previous{

			left: 0px;

		}

		a.next{

			right: 0px;

		}

		.report-bordered

		{

			width:75% !important;

		}

		.container

		{

			width:750px !important;

		}

		.info-mechanic .col-md-6

		{

			width:50%;

		}

		/*body.welcome 

		{

			background-image: url('{{ asset('/images/bg-img.png') }}') !important;

		}*/

		.steps:after 

		{

			border:none !important;

		}

		a.previous, a.next{

			display: none !important;

		}
		
		.vertical-center-table .float_left {
			transform: translateY(17%);
		}
		
		

	}

	@media (max-width: 1025px) and (min-width: 769px) {

		

		.header-top .navbar a

		{

			padding-right:10px !important;

			padding-left:10px !important;

		}

	}

	@media (max-width: 500px) {

		

		.container 

		{

			width:83% !important;

		}

		.info-mechanic .col-md-6 

		{

			width:100% !important;

			display:block !important;

		}

		.vertical-center-table

		{

			display:block !important;

		}

		.vertical-center-table .float_left {
			transform: translateY(0);
		}
		

	}

	@media (max-width: 670px) {

		.container 

		{

			width:95% !important;

		}

		.vertical-center-table .float_left {
			transform: translateY(-8%);
		}

	}

	@media (max-width: 360px) {

		.container 

		{

			width:100% !important;

		}

		

	}

	.testimonial-section{ background: #fff;  }

	

</style>





<?php  $is_home = true; ?>

@endsection 
<?php

$mobile = config('is_mobile');

?>

<div >

	<div class="">

		<div class="navbar-header">

        	@if( $mobile )

            	<a class="small-link pull-left to-text navbar-toggle" data-toggle="modal" data-target="#termsDialog" data-static="true" data-keyboard="false" href="#" style="display:none;" id="terms-link" ><i class="fa fa-check-square-o" aria-hidden="true"></i> Terms</a>

            @endif

			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">

				<span class="sr-only">Toggle Navigation</span>

				<span class="fa fa-bars menu-border"></span>

			</button>

		</div>

		<div id="bs-example-navbar-collapse-1">

			<div id = "navbar" class="collapse navbar-collapse">

				<ul class="nav navbar-nav">

					@if ( Auth::guest() ) 

						<li><a class="small-link" data-toggle="modal" data-target="#openParkingMeterModal" href="#"><i class="fa fa-tachometer"></i> Parking Meter</a></li>
						<?php /* {{ url('/my-meter') }} */ ?>

						<?php /*?><li><a class="small-link" href="{{ url('/') }}"><i class="fa fa-home"></i> <?php if( $mobile ): ?>Pay Meter<?php else: ?>Home<?php endif; ?></a></li> <?php */?>

						<li><a class="small-link" href="{{ url('/faq') }}"><?php /*?><i class="fa fa-file"></i><?php */?> FAQ</a></li>

						<li><a class="small-link" href="{{ url('/contact-us') }}"><?php /*?><i class="fa fa-envelope-o"></i><?php */?> Contact Us</a></li>

						
						<li class="hidden-md hidden-lg"><a class="small-link" href="{{ url('/rent-out-a-parking-space') }}">Rent out my space</a></li>

						

						<?php /*?><li><a class="small-link" href="{{ url('/terms') }}"><i class="fa fa-book"></i> Terms</a></li>

						<li><a class="small-link" href="{{ url('/privacy') }}"><i class="fa fa-lock"></i> Privacy</a></li><?php */?>

	                    <li><a href="#" class="small-link" data-toggle="modal" data-target="#openLoginModal" data-static="true" data-keyboard="false" ><?php /*?><i class="fa fa-sign-in"></i> <?php */?>Login</a></li>
	                    
	                    
	                    <li><a href="#" class="small-link sign_up_button" data-toggle="modal" data-target="#openSignUpModal" data-static="true" data-keyboard="false" ><?php /*?><i class="fa fa-sign-in"></i> <?php */?>Sign Up</a></li>
	                    

					@endif

					@if (Auth::user() && Auth::user()->role_id == 1 ) 

						<li class="li-logo"><a href="{{ url('/admin/dashboard') }}"><img src="{{ asset('/images/gray-logo.png') }}" style='max-height:60px;max-width: 100%;'></a></li>

						<li><a href="{{ url('/admin/dashboard') }}">Dashboard</a></li> 

						<li><a href="{{ url('/admin/users') }}">Users</a></li> 

						<li><a href="{{ url('/admin/reports') }}">Reports</a></li> 

						<li><a href="{{ url('/admin/payments') }}">Payments</a></li> 

						<li><a href="{{ url('/admin/managecontent') }}">Content Manager</a></li> 

						<li><a href="{{ url('/admin/settings') }}">Settings</a></li> 

					@elseif (Auth::user() && Auth::user()->role_id == 2 ) 

						<li><a href="{{ url('/sa-home') }}"><img src="{{ asset('/images/gray-logo.png') }}" style='max-height:60px;max-width: 100%;'></a></li>

						<?php /*?><li><a href="{{ url('/sa-home') }}">My Meter</a></li> 

						<li><a href="{{ url('/sa-agreement') }}">Agreement</a></li> <?php */?>

					@elseif (Auth::user() && Auth::user()->role_id == 3 )

						<li><a href="{{ url('/home') }}"><img src="{{ asset('/images/gray-logo.png') }}" style='max-height:60px;max-width: 100%;'></a></li>

						<?php /*?><li><a href="{{ url('/') }}">My Meter</a></li> 

						<li><a href="{{ url('/owner-agreement') }}">Agreement</a></li> <?php */?>
	                @elseif (Auth::user() && Auth::user()->role_id == 5 )

						<li><a href="{{ url('/home') }}"><img src="{{ asset('/images/gray-logo.png') }}" style='max-height:60px;max-width: 100%;'></a></li>

						<?php /*?><li><a href="{{ url('/') }}">My Meter</a></li> 

						<li><a href="{{ url('/owner-agreement') }}">Agreement</a></li> <?php */?>

					@endif

				</ul>

				

				<ul class="nav navbar-nav pull-right">

					@if (Auth::guest())

						<?php /*?><li><a href="{{ url('/account/login') }}">Login</a></li><?php */?>

					@else

						<?php /*?><li><a>{{ Auth::user()->name }}</a></li><?php */?>

						<li><a href="{{ url('/account/logout') }}" class="logout-link"><i class="fa fa-sign-out"></i> Logout</a></li>

					@endif

				</ul>

				

				<?php /*?><ul class="nav navbar-nav navbar-right">

					@if (Auth::guest())

						<li><a href="{{ url('/account/login') }}">Login</a></li>

						<li><a href="{{ url('/account/register') }}">Register</a></li>

					@else

						<li class="dropdown">

							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>

							<ul class="dropdown-menu" role="menu">

								<li><a href="{{ url('/account/logout') }}">Logout</a></li>

							</ul>

						</li>

					@endif

				</ul><?php */?>

			</div>
		</div>
	</div>

</div>



<div id="openLoginModal" style="display:none" class="modal fade" role="dialog" >

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title text-center"><i class="fa fa-sign-in"></i> Login</h4>

			</div>

			<div class="modal-body">

				<?php /*?>@if (count($errors) > 0)

					<div class="alert alert-danger">

						<strong>Whoops!</strong> There were some problems with your input.<br><br>

						<ul>

							@foreach ($errors->all() as $error)

								<li>{{ $error }}</li>

							@endforeach

						</ul>

					</div>

				@endif<?php */?>



				<form class="form-horizontal" role="form" method="POST" action="{{ url('/account/login') }}">

					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<input type="hidden" name="login_form" value="true" />



					<div class="form-group">

						<label class="col-md-4 control-label">E-Mail Address</label>

						<div class="col-md-6">

							<input type="email" class="form-control" name="email" value="{{ old('email') }}">

						</div>

					</div>



					<div class="form-group">

						<label class="col-md-4 control-label">Password</label>

						<div class="col-md-6">

							<input type="password" class="form-control" name="password">

						</div>

					</div>



					<div class="form-group">

						<div class="col-md-6 col-md-offset-4">

							<div class="checkbox">

								<label>

									<input type="checkbox" name="remember"> Remember Me

								</label>

							</div>

						</div>

					</div>



					<div class="form-group">

						<div class="col-md-6 col-md-offset-4">

							<button type="submit" class="btn btn-primary pull-left">Login</button>



							<a class="btn btn-link pull-left" href="{{ url('/password/email') }}">Forgot Password?</a>

						</div>

					</div>

				</form>

			</div>

		</div>

	</div>

</div>


<div id="openSignUpModal" style="display:none" class="modal fade" role="dialog" >

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title text-center"><i class="fa fa-sign-in"></i> Sign Up </h4>

			</div>

			<div class="modal-body">

				<form class="form-horizontal" action="{{ URL::to('/home/register') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="reg_type" value="quick"  />
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                        	Name
                        </label>
                        <div class="col-md-6">
                            <input type="text" name="name" required="required" class="form-control">
                        </div>
                     </div>
                     
                      <div class="form-group">
                        <label class="col-md-4 control-label">
                        	Email
                        </label>
                        <div class="col-md-6">
                            <input type="email" name="email" required="required" class="form-control">
                        </div>
                     </div>
                     
                     <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <input type="submit" value = "SignUp" required="required" class="btn btn-primary pull-left">
                        </div>
                     </div>
                    
                    
                </form>

			</div>

		</div>

	</div>

</div>

@if(!$mobile)
<div id="openParkingMeterModal" style="display:none" class="modal fade" role="dialog" >
	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<p style="text-align:center;"><img src="{{ asset('images/metercar.png') }}" style="max-height:100px; float:none;" > </p>

			</div>
			<div class="modal-body"> 
	<?php

	$price_array = array(

		"0.5"		=> 		"30 min",

		"1"			=> 		"1 Hr",

		"1.5"		=> 		"1.5 Hr",

		"2"			=> 		"2 Hr",

		"2.5"		=> 		"2.5 Hr",

		"3"			=> 		"3 Hr",

		"4"			=> 		"4 Hr",

		"5"			=> 		"5 Hr",

		"6"			=> 		"6 Hr",

		"7"			=> 		"7 Hr",

		"8"			=> 		"8 Hr",

		"9"			=> 		"9 Hr",

		"10"		=> 		"10 Hr",

	);

?>

<div style="position:relative">


<form class="form-horizontal" role="form" method="POST" action="{{ url('/home/pay') }}" id="customer_pay_form">

	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<div class='row'>


		<div class='col-md-7 col-lg-7 text-center' style="margin:0 auto; float:none;">

			

            <div class="clearfix"></div>

            <br clear="all" />



			<div>

		

				<div>

					<div class="meter_message to-text padinglr alert alert-success " style="display:none; ">

						@if( isset($meter) && $meter['meter_id'] > 0 )

							<div class="meter_price_message to-text text-center" style="display:none;"></div>

						@else

							<span></span>

						@endif

					</div>

				</div>

		

				<div>

					@if( isset($meter) && $meter['meter_id'] > 0 )

                    	<br />

						<div class="add-more-time-div">

							<div class="to-text text-center" style="font-size:20pt;">Meter #: {{ $meter['meter_id'] }}</div>

							

							<div class="clearfix"></div>

		

							<div class="meter_message to-text text-center" style="border:1px solid green;">Price: ${{ $meter['hour_price'] }} /hr</div>

		

							<div class="clearfix"></div>

							

							<input type="hidden" name="meter_id" value="{{ $meter['meter_id'] }}" >

							<input type="hidden" name="add_more_time" value="true" />

						</div>

						<div class="clearfix"></div>

						<br />

						<div class="mobile-item"><br /></div>

						<div class="text-center" style="position: relative;">

                       		<button type="button" class="sleectText expiry_time" >

                       			@if( isset($price_array[old('expiry_time')]) ) {{ old('expiry_time') }} @else Time @endif

                       			<i class="fa fa-tachometer" aria-hidden="true" style="font-size: 22px;padding-left: 10px;"></i>

                       		</button>

                       		<div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i></div>

							<select name="expiry_time" class="form-control centered-select select_center_text" required onchange="calculate_price()" >

								<option value="">Time</option>

                                @foreach( $price_array as $price=>$time )

                                	<option value="{{ $price }}" @if( old('expiry_time') === '0.5' ) selected="selected" @endif  >{{ $time }}</option>	

                                @endforeach

                            </select>

						</div>

		

						<div class="clearfix"></div>

		

					@else

						

						<div class="text-center">

							<input type="text" class="form-control" name="meter_id" pattern="\d{6}" value="{{ old('meter_id') }}" required placeholder="Meter #" >

						</div>

						<div class="clearfix"></div>

						<br />

						<div class="text-center"  style="position: relative;">

							<button type="button" class="sleectText expiry_time" >

                       			@if( isset($price_array[old('expiry_time')]) ) {{ old('expiry_time') }} @else Time @endif

                       			<i class="fa fa-tachometer" aria-hidden="true" style="font-size: 22px;padding-left: 10px;"></i>

                       		</button>



                        	<!-- <input type="button" class="sleectText expiry_time" value="@if( isset($price_array[old('expiry_time')]) ) {{ $price_array[old('expiry_time')] }} @else Time @endif"> -->

                        	<div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i>

                        </div>









							<select name="expiry_time" class="form-control centered-select select_center_text" required onchange="calculate_price()" >

								<option value="">Time</option>

                                @foreach( $price_array as $price=>$time )

                                	<option value="{{ $price }}" @if( old('expiry_time') == $price ) selected="selected" @endif  >{{ $time }}</option>	

                                @endforeach

								

								<?php /*?><option value="1" @if( old('expiry_time') === '1' ) selected="selected" @endif >1 Hr</option>

								<option value="1.5" @if( old('expiry_time') === '1.5' ) selected="selected" @endif >1.5 Hr</option>

								<option value="2" @if( old('expiry_time') === '2' ) selected="selected" @endif >2 Hr</option>

								<option value="2.5" @if( old('expiry_time') === '2.5' ) selected="selected" @endif >2.5 Hr</option>

								<option value="3" @if( old('expiry_time') === '3' ) selected="selected" @endif >3 Hr</option>

								<option value="4" @if( old('expiry_time') === '4' ) selected="selected" @endif >4 Hr</option>

								<option value="5" @if( old('expiry_time') === '5' ) selected="selected" @endif >5 Hr</option>

								<option value="6" @if( old('expiry_time') === '6' ) selected="selected" @endif >6 Hr</option>

								<option value="7" @if( old('expiry_time') === '7' ) selected="selected" @endif >7 Hr</option>

								<option value="8" @if( old('expiry_time') === '8' ) selected="selected" @endif >8 Hr</option>

								<option value="9" @if( old('expiry_time') === '9' ) selected="selected" @endif >9 Hr</option>

								<option value="10" @if( old('expiry_time') === '10' ) selected="selected" @endif >10 Hr</option><?php */?>

							</select>

						</div>

                        

                        @if( !empty(old('meter_id')) and !empty(old('expiry_time')) )

                        	<script type="text/javascript">

                            	document.addEventListener("DOMContentLoaded",function(){ 

                            		setTimeout(function(){ validate_meter() }, 1000);

                            	},false);

                            </script>

                        @endif

                        

					@endif

					

				</div>



			</div>



			<div class="clearfix"></div>



            <div class="meter_price_message price-msg to-text" style="display:none;margin-top:20px;"></div>





            <div class="paddinlrsmall" style="position: relative; float:left; width:100%;">

                <div id="paypal_form" style="margin-top:20px;@if (count($errors) == 0) display:none; @endif">

                    <div class="text-center" >

                        <img src="{{ asset('/images/card_types.png') }}" border="0" class="cart_types_img"  />

                    </div>

                    <div>

                        <input type="text" class="form-control text-center" name="cc_number" value="{{ old('cc_number') }}" placeholder="Card Number" required="required" pattern="\d*" style="font-size:18px;" >

                    </div>

                    <br />

                    <div>

                    <input type="button" class="sleectText" value="@if( !empty(old('expiry_month')) ) {{ old('expiry_month') }} @else Expiry Month @endif" style=" width:100%;"><div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i></div>

                        <select name="expiry_month" class="form-control select_center_text" style="text-align-last:center;" required="required" >

                            <option value="">Expiry Month</option>

                            @for ($i=1; $i<=12;$i++)

                            <option @if (old('expiry_month') == $i) selected="selected" @endif>{{ $i}}</option>

                            @endfor

                        </select>

                    </div>

                    <br />  

                    <div>

                    <input type="button" class="sleectText" value="@if( !empty(old('expiry_year')) ) {{ old('expiry_year') }} @else Expiry Year @endif" style=" width:100%;"><div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i></div>

                        <select name="expiry_year" class="form-control select_center_text" style="text-align-last:center;" required="required" >

                            <option value="">Expiry Year</option>

                            @for ($i=2016; $i<=2040;$i++)

                            <option @if (old('expiry_year') == $i) selected="selected" @endif>{{ $i}}</option>

                            @endfor

                        </select>

                        

                    </div>

        

                </div>	

            </div>



            <div class="clearfix"></div>



            <div style="position:relative;float:left; width:100%;">

            	<br />

                <button type="button" class="btn btn-primary btn-block green-button" id="pay_button">Pay <i class="fa fa-credit-card" aria-hidden="true"></i></button>

            </div>





		</div>





        <div class="col-md-4 col-lg-4">

        </div>

		

        <div class="clearfix"></div>

		<br /><br />

	</div>

	

	

</form>

</div>
</div>
<style>

	.meter_price_message.price-msg .alert{ margin-bottom:0px; }

</style>
</div>
</div>
</div>

@endif 

@if(Auth::user())

	<style type="text/css">

		body:not(.welcome) .navbar-nav > li:not(.li-logo) > a{ line-height: 62px; margin-top: 15px; }

		body:not(.welcome) .navbar-nav > li:not(.li-logo) > a.logout-link{     font-size: 18px;   color: red !important; }

	</style>

@endif

<style>

	@media (min-width: 768px){

		.banner-img ul.nav {

			float:right !important

		}

	}

	#openLoginModal .btn-link {

		color: #23527c !important;

	}

</style>
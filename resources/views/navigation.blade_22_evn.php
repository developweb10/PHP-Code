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

						<li><a class="small-link" href="{{ url('/my-meter') }}"><i class="fa fa-tachometer"></i> Parking Meter</a></li>
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
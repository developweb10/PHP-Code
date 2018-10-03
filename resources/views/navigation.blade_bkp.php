<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
	$mobile = 1;
}else{
	$mobile = 0;
}

$nav_container = isset($nav_container) ? $nav_container : 1;
?>
<div <?php if($nav_container): ?> class="container" <?php endif; ?> >
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle Navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			@if (Auth::guest()) 
				<a class="navbar-brand" href="{{ url('/my-meter') }}">My Meter</a> 
			@endif
		</div>
		
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				@if ( Auth::guest() ) 
					<li><a href="{{ url('/') }}"><?php if( $mobile ): ?>Pay Meter<?php else: ?>Home<?php endif; ?></a></li> 
					<li><a href="{{ url('/faq') }}">FAQ</a></li>
					<li><a href="{{ url('/contact-us') }}">Contact</a></li>
					<li><a href="{{ url('/terms') }}">Terms</a></li>
					<li><a href="{{ url('/privacy') }}">Privacy</a></li>
                    <li class="mobile-item_strict"><a href="#" data-toggle="modal" data-target="#openLoginModal" data-static="true" data-keyboard="false" >Login</a></li>
				@endif
				@if (Auth::user() && Auth::user()->role_id == 1 ) 
					<li class="li-logo"><a href="{{ url('/admin/dashboard') }}"><img src="{{ asset('/images/gray-logo.png') }}" style='height:40px;'></a></li>
					<li><a href="{{ url('/admin/dashboard') }}">Dashboard</a></li> 
					<li><a href="{{ url('/admin/users') }}">Users</a></li> 
					<li><a href="{{ url('/admin/reports') }}">Reports</a></li> 
					<li><a href="{{ url('/admin/payments') }}">Payments</a></li> 
					<li><a href="{{ url('/admin/managecontent') }}">Content Manager</a></li> 
					<li><a href="{{ url('/admin/settings') }}">Settings</a></li> 
				@elseif (Auth::user() && Auth::user()->role_id == 2 ) 
					<li><a href="{{ url('/sa-home') }}"><img src="{{ asset('/images/gray-logo.png') }}" style='height:40px;'></a></li>
					<?php /*?><li><a href="{{ url('/sa-home') }}">My Meter</a></li> 
					<li><a href="{{ url('/sa-agreement') }}">Agreement</a></li> <?php */?>
				@elseif (Auth::user() && Auth::user()->role_id == 3 )
					<li><a href="{{ url('/home') }}"><img src="{{ asset('/images/gray-logo.png') }}" style='height:40px;'></a></li>
					<?php /*?><li><a href="{{ url('/') }}">My Meter</a></li> 
					<li><a href="{{ url('/owner-agreement') }}">Agreement</a></li> <?php */?>
				@endif
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				@if (Auth::guest())
					<?php /*?><li><a href="{{ url('/account/login') }}">Login</a></li><?php */?>
				@else
					<?php /*?><li><a>{{ Auth::user()->name }}</a></li><?php */?>
					<li><a href="{{ url('/account/logout') }}" class="link-line-height"><i class="fa fa-sign-out"></i> Logout</a></li>
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

<div id="openLoginModal" style="display:none" class="modal fade" role="dialog" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fa fa-sign-in"></i> Login</h4>
			</div>
			<div class="modal-body">
				@if (count($errors) > 0)
					<div class="alert alert-danger">
						<strong>Whoops!</strong> There were some problems with your input.<br><br>
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

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
							<button type="submit" class="btn btn-primary">Login</button>

							<a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
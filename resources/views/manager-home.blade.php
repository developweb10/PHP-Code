@extends('app')



@section('content')

<style type="text/css">

	.logout-link{ display: none !important;  }

</style>

<div class="">

    <div>
		@if ((!config('is_Ipad')) && config('is_mobile')) 
        <div class="navbar-header">
            <button type="button" class="navbar-toggle cust_top" data-toggle="collapse" data-target="#users_menus">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars menu-border menu-button-black"></span>
            </button>
        </div>
   		@endif 
    	<div class="collapse navbar-collapse" id="users_menus">
        <ul class="nav nav-tabs main-tabs" role="tablist">

            <li role="presentation" @if ($vars['tab'] === '' || $vars['tab'] === 'overview') class="active" @endif ><a href="#overview" aria-controls="overview" role="tab" data-toggle="tab">Overview</a></li>

			<li role="presentation" @if ($vars['tab'] === 'payments') class="active" @endif ><a href="#payments" aria-controls="payments" role="tab" data-toggle="tab">Payments</a></li>


            <li role="presentation" @if ($vars['tab'] === 'account') class="active" @endif ><a href="#account" aria-controls="settings" role="tab" data-toggle="tab">Account</a></li>


            <li class="pull-right">

            	<a href="{{ url('/account/logout') }}" class="logout-link-sa" style="color:red;"><i class="fa fa-sign-out"></i> Logout</a>

            </li>

            <li class="pull-right">

            	<a href="{{ URL::to('/sm/createuser') }}?show_layout=false" class="btn btn-default btn-sm" data-toggle="modal" data-target="#createNewUserModal" data-static="false" data-keyboard="false" onclick="openModalLoader()"  >Create New Sales Account</a>
                
            </li>

            </ul>
        </div>

        <div class="tab-content">

            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === '' || $vars['tab'] === 'overview') active @endif "id="overview">

				

				@include('sm.manager')

			</div>

            
			<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'payments') active @endif" id="payments">

				

				@include('includes.payments')

			</div>

            

            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'account') active @endif " id="account">

				

				@include('includes.account')

			</div>

        </div>

    </div>

</div>



<style type="text/css">

	@media screen and (max-width:768px) {

	 	ul.nav-tabs.main-tabs{

			padding-top: 20px;

		    padding-bottom: 45px;

		}

		ul.nav-tabs.main-tabs > li{

			width: 32%;

			border: 1px solid rgba(128, 128, 128, 0.16);

    		text-align: center;

		}

		ul.nav-tabs.main-tabs > li > a, .filter-form{

			padding: 10px 10px;

		}

		body:not(.welcome) .navbar-nav > li:not(.li-logo) > a, #bs-example-navbar-collapse-1{

			/*padding-left:10px !important;*/

		}

		.nav-tabs.main-tabs > li.active > a, .nav-tabs.main-tabs > li.active > a:hover, .nav-tabs.main-tabs > li.active > a:focus{

			background-color: darkgray;

			color: #fff;

			margin-right: 0px;

		}

		ul.nav-tabs.main-tabs > li.pull-right{

			float: left !important;

		}

		body.app .navbar ul.nav{

			width:100%;

			text-align:center;

		}

		.report_statistics1 > div{

			border-bottom: 1px solid #fff;

		}

		.tabs-left > .nav-tabs {

		    width: 100%;

		    float: none;

		}

		.tabs-left > .nav-tabs > li, .tabs-right > .nav-tabs > li {

		    float: left;

		    width: 33%;

		    border: 1px solid #ddd;

		}

		.tab-content #support.tab-pane .tab-content .tab-pane {

		    margin-left: 0;

		}

		.tabs-left > .nav-tabs .active > a, .tabs-left > .nav-tabs .active > a:hover, .tabs-left > .nav-tabs .active > a:focus, .tabs-left > .nav-tabs > li > a{

			border: 0px;

   			margin-right: 0px;

   			text-align: center;

		}

		.button-row{

			margin-top:40px;

		}

		.button-row button{

			width:100%;

		}

		.top_section h2, .top_section h4{

			text-align: center;

			border-bottom: 1px solid #ddd;

    		padding-bottom: 20px;

		}

		.top_section h4 strong{

			display: block;

		}

		.top_section h4 span{

			margin-left: 0px !important;

		}

		.top_section h4 #tracking_url_box input{

			text-align: center;

		}

		body.app .navbar ul.nav {

		    width: 100%;

		    text-align: center;

		    margin-left: 0;

		    margin-right: 0;

		}

		.navbar-nav > li{

			float: none !important;

		}



	 }

</style>
<div id="createNewUserModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>



@endsection
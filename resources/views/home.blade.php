@extends('app')

@section('content')

<?php


$mobile = config('is_mobile');
$Ipad = config('is_Ipad');
?>

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
        	
            <li role="presentation" @if ($vars['tab'] === '') class="active" @endif ><a href="#meter" aria-controls="home" role="tab" data-toggle="tab">Reports</a></li>
            <?php   /*?><li role="presentation" @if ($vars['tab'] === 'report' || $vars['tab'] === 'report_by_groups' || $vars['tab'] === 'report_by_meters') class="active" @endif ><a href="#report" aria-controls="profile" role="tab" data-toggle="tab"  @if( !($vars['tab'] === 'report_by_groups' || $vars['tab'] === 'report_by_meters') ) class="load-report" @endif   >Reports</a></li><?php */?>
			
             <li role="presentation" @if ($vars['tab'] === 'locations') class="active" @endif ><a href="#locations" aria-controls="messages" role="tab" data-toggle="tab">Locations</a></li>
            <li role="presentation" @if ($vars['tab'] === 'qrCode') class="active" @endif ><a href="#qrCode" aria-controls="messages" role="tab" data-toggle="tab">Fee Schedule</a></li>
            <li role="presentation" @if ($vars['tab'] === 'signage') class="active" @endif ><a href="#signage" aria-controls="messages" role="tab" data-toggle="tab">Parking Signs</a></li>
            
            <li role="presentation" @if ($vars['tab'] === 'account') class="active" @endif ><a href="#account" aria-controls="settings" role="tab" data-toggle="tab">Account</a></li>
            <li role="presentation" @if ($vars['tab'] === 'payments') class="active" @endif ><a href="#payments" aria-controls="profile" role="tab" data-toggle="tab">Payments</a></li>
            <li role="presentation" @if ($vars['tab'] === 'support') class="active" @endif ><a href="#support" aria-controls="settings" role="tab" data-toggle="tab">Support</a></li>
            <!--<li class="pull-right">
            	<a href="{{ url('/account/logout') }}" class="logout-link-sa" style="color:red;"> <?php /* <i class="fa fa-sign-out"></i> Logout */ ?>
                	<img src="{{ asset('/images/log_out_final.png') }}">
                </a>
            </li>-->
           @if ((!config('is_Ipad')) && config('is_mobile')) 
           	<li role="presentation">
           @endif 
            <a href="{{ url('/account/logout') }}" class="log_out_img" style="color:red;background: #fff;"> <?php /* <i class="fa fa-sign-out"></i> Logout */ ?>
                <img src="{{ asset('/images/log_out_final.png') }}">
            </a>
			 @if ((!config('is_Ipad')) && config('is_mobile')) 
           	</li>
           @endif 
        </ul>
        </div>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === '') active @endif "id="meter">
				@include('landowner.report_by_meters')
			</div>
            <!--<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'inspections') active @endif" id="inspections">
				
				@include('includes.inspections_ui')
			</div>-->
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'locations') active @endif" id="locations">
				
				@include('includes.locations')
                
                

			</div>
            
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'qrCode') active @endif" id="qrCode">
				
				@include('includes.qrCode')
                
                

			</div>
			<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'payments') active @endif" id="payments">
				
				@include('includes.payments')
			</div>
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'signage') active @endif " id="signage">
				
				@include('includes.landowner_parking_meter')
			</div>
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'account') active @endif " id="account">
				
				@include('includes.account')
			</div>
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'support') active @endif " id="support">
            	<?php if ($vars['tab'] != 'support') $hide_error = true; ?>
				
            	<div class="tabbable tabs-left">
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active" ><a href="#faq" aria-controls="home" role="tab" data-toggle="tab">FAQ</a></li>
						<li role="presentation" ><a href="#contactUs" aria-controls="home" role="tab" data-toggle="tab">Contact Us</a></li>
						<li role="presentation" ><a href="#aggrement" aria-controls="home" class="aggrement" role="tab" data-toggle="tab">Agreement</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane col-md-offset-2 active" id="faq">
						@include('includes.faq-sub')
						</div>
						<div role="tabpanel" class="tab-pane col-md-offset-2" id="contactUs">
						@include('includes.contact-us-form')
						<div class="clearfix"></div>
						
						</div>
						<div role="tabpanel" class="tab-pane col-md-offset-2 " id="aggrement">
						<div id="view_agreement" >
						   <div class="panel panel-default">
							  <div class="panel-heading">{{ $vars["agreement_data"]->page_title or "" }}</div>
							  <div class="panel-body" id="body_container">
								 @if( isset($vars["agreement_data"]->page_content) )  {{ app('App\Http\Controllers\UtilsController')->html_decode($vars["agreement_data"]->page_content) }} @endif
							  </div>
						   </div>
						</div>
						</div>   
					</div>
               </div>
			</div>
        </div>
    </div>
</div>

<style type="text/css">
	 @media screen and (max-width:768px) {
		ul.nav-tabs.main-tabs{
			padding-top: 45px;
		    padding-bottom: 45px;
		}
		body .main-tabs .inspections-div{
			width: 100%;
			text-align: center;
			margin-top: 20px;
			right: 0px !important;
		}
		body .main-tabs .inspections-div strong{
			display: block;
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
		.meter-buttons{
			text-align: center;
		    min-height: 70px;
		    border-bottom: 1px solid #dddddd;
		}
		.filter-form{
			padding-bottom: 20px;
		    border-bottom: 1px solid #dddddd;
		}
		#meter_search{
			width: 80px !important;
		}
		.table > thead > tr > th {
    		vertical-align: middle !important;
    	}
    	.export-buttons{
    		top:-48px !important;
    	}
    	.towing-number-form, form.towing-number-form input[name="towing_company_number"]{
    		text-align:center;
    	}
    	form.towing-number-form input[type="submit"] {
		    width: 100%;
		    margin-top: 15px;
		}
		#signage_form{
			text-align: center;
		    padding-left: 0px;
		    padding-right: 0px;
		}
		.signage-right-section{
			margin-top: 30px;
		}
		.tabs-left > .nav-tabs {
		    width: 100%;
		    float: none;
		}
		.tabs-left > .nav-tabs > li, .tabs-right > .nav-tabs > li {
		    float: left;
		    width: 33.3%;
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
		ul.nav-tabs.main-tabs > li.pull-right{
			float: left !important;
			display: inline-block !important;
		}
		.logout-link {
		    display: none !important;
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
	@media screen and (max-width:736px) {
		body .export-buttons{
    		top:-20px !important;
			position: inherit;
    	}
	 }
</style>

<div class="modal fade" id="share_incpt" role="dialog">
    <div class="modal-dialog modal-md" style="max-width:400px; margin-left: auto;  margin-right: auto;"> 
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Share Live Feed</h4>
        </div>
        <div class="modal-body">
        	<form name="share" class="form-horizontal" role="form" method="POST" action="{{ url('/home/Sharefeed') }}">
                <input type="hidden" name="inspections_url" value="{{ $vars['inspectionsURL'] }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <div class="col-xs-9 col-md-9">
						<input type="email" class="form-control" name="email" value="" placeholder="E-Mail Address">
					</div>
                    <div class="col-xs-3 col-md-3">
						<button type="submit" class="btn btn-primary pull-left">Send</button>
					</div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

@endsection

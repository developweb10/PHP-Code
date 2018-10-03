@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" @if ($vars['tab'] === '') class="active" @endif ><a href="#meter" aria-controls="home" role="tab" data-toggle="tab">Meters</a></li>
            <li role="presentation" @if ($vars['tab'] === 'report' || $vars['tab'] === 'report_by_groups' || $vars['tab'] === 'report_by_meters') class="active" @endif ><a href="#report" aria-controls="profile" role="tab" data-toggle="tab"  @if( !($vars['tab'] === 'report_by_groups' || $vars['tab'] === 'report_by_meters') ) class="load-report" @endif   >Reports</a></li>
			<li role="presentation" @if ($vars['tab'] === 'payments') class="active" @endif ><a href="#payments" aria-controls="profile" role="tab" data-toggle="tab">Payments</a></li>
            <li role="presentation" @if ($vars['tab'] === 'signage') class="active" @endif ><a href="#signage" aria-controls="messages" role="tab" data-toggle="tab">Signage</a></li>
            <li role="presentation" @if ($vars['tab'] === 'account') class="active" @endif ><a href="#account" aria-controls="settings" role="tab" data-toggle="tab">Account</a></li>
            <li role="presentation" @if ($vars['tab'] === 'support') class="active" @endif ><a href="#support" aria-controls="settings" role="tab" data-toggle="tab">Support</a></li>
			@if( $vars['inspectionsURL'] != '' )
				<div class="inspections-div"><strong>Inspections URL:</strong> <a href="{{ $vars['inspectionsURL'] }}" target="_blank">{{ $vars['inspectionsURL'] }}</a></div>
			@endif
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === '') active @endif "id="meter">
				<br />
				@include('landowner.meter')
			</div>
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'report' || $vars['tab'] === 'report_by_groups' || $vars['tab'] === 'report_by_meters') active @endif" id="report">
				<br />
				@if ( $vars['tab'] === 'report_by_groups' )
					@include('landowner.report_by_groups')
				@elseif ( $vars['tab'] === 'report_by_meters' )
					@include('landowner.report_by_meters')
				@else
					@include('landowner.report')
				@endif
			</div>
			<div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'payments') active @endif" id="payments">
				<br />
				@include('includes.payments')
			</div>
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'signage') active @endif " id="signage">
				<br />
				@include('includes.signage')
			</div>
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'account') active @endif " id="account">
				<br />
				@include('includes.account')
			</div>
            <div role="tabpanel" class="tab-pane @if ($vars['tab'] === 'support') active @endif " id="support">
				<br />
            	<div class="tabbable tabs-left">
						<ul class="nav nav-tabs" role="tablist">
                     <li role="presentation" class="active" ><a href="#faq" aria-controls="home" role="tab" data-toggle="tab">Faq</a></li>
                     <li role="presentation" ><a href="#contactUs" aria-controls="home" role="tab" data-toggle="tab">Contact Us</a></li>
                     <li role="presentation" ><a href="#aggrement" aria-controls="home" class="aggrement" role="tab" data-toggle="tab">Aggrement</a></li>
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

@endsection
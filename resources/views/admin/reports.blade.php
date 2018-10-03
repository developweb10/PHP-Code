@extends('app')



@section('content')

<div class="container-fluid">

	<div class="row">

		<div class="col-md-12">

			<div class="panel panel-default">

				<div class="panel-heading">

					<?php /*?>@if( $vars['report_type'] == "report_by_lots" )

						Report By Groups

					@elseif( $vars['report_type'] == "report_by_meters" )

						Report By Meters

					@elseif( $vars['report_type'] == "report_by_sa" )

						Report By Sales Associates

					@elseif( $vars['report_type'] == "report_by_mass_payments" )

						Report By Mass Payments

					@elseif( $vars['report_type'] == "report_by_city" )

						Report By City

					@else

						Report By Land Owners

					@endif<?php */?>



					<div>

						<a href="{{ URL::to('/admin/reports') }}?report_type=report_by_lo" class="btn btn-default btn-sm @if( $vars['report_type'] == 'report_by_lo' ) selected @endif">Report By Landowners</a>

						<a href="{{ URL::to('/admin/reports') }}?report_type=report_by_sm" class="btn btn-default btn-sm @if( $vars['report_type'] == 'report_by_sm' ) selected  @endif">Report By Sales Managers</a>

						<a href="{{ URL::to('/admin/reports') }}?report_type=report_by_sa" class="btn btn-default btn-sm @if( $vars['report_type'] == 'report_by_sa' ) selected  @endif">Report By Sales Associates</a>

						<a href="{{ URL::to('/admin/reports') }}?report_type=report_by_lots" class="btn btn-default btn-sm @if( $vars['report_type'] == 'report_by_lots' ) selected @endif" style="    background-color:#000;color: #fff;" >Report By Groups</a>

						<a href="{{ URL::to('/admin/reports') }}?report_type=report_by_meters" class="btn btn-default btn-sm @if( $vars['report_type'] == 'report_by_meters' ) selected @endif">Report By Meters</a>

						<a href="{{ URL::to('/admin/reports') }}?report_type=report_by_mass_payments" class="btn btn-default btn-sm @if( $vars['report_type'] == 'report_by_mass_payments' ) selected @endif">Report By Mass Payments</a>

						<a href="{{ URL::to('/admin/reports') }}?report_type=report_by_city" class="btn btn-default btn-sm @if( $vars['report_type'] == 'report_by_city' ) selected @endif">Report By City</a>

						<a href="{{ URL::to('/admin/reports') }}?report_type=report_by_country" class="btn btn-default btn-sm @if( $vars['report_type'] == 'report_by_country' ) selected @endif">Report By Country</a>
                        
                       

					</div>
					<div class="clearfix"></div>

				</div>

				<div class="panel-body">

					

					@if( $vars['report_type'] == "report_by_lots" )

						@include('admin.report_by_lots')

					@elseif( $vars['report_type'] == "report_by_meters" )

						@include('admin.report_by_meters')

					@elseif( $vars['report_type'] == "report_by_sm" )

						@include('admin.report_by_sa')

					@elseif( $vars['report_type'] == "report_by_sa" )

						@include('admin.report_by_sa')

					@elseif( $vars['report_type'] == "report_by_city" )

						@include('admin.report_by_city')

					@elseif( $vars['report_type'] == "report_by_country" )

						@include('admin.report_by_country')

					@elseif( $vars['report_type'] == "report_by_mass_payments" )

						<h2>Report Coming soon</h2>

					@else

						@include('admin.report_by_lo')

					@endif

					

				</div>

			</div>

		</div>

		<div class="clearfix"></div>

	</div>

</div>



<style>

	.banner-img{ display:none; }

	.panel-heading{ height: 51px; }

	a.disabled{ box-shadow:0px 4px 3px #888 !important; }

	body .panel-heading {
		height: auto;
	    min-height: 51px;
	}

</style>



@endsection
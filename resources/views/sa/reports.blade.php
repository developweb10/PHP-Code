@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="com"></div>
			<div class="panel panel-default">
				<div class="panel-heading">
					@if( $vars['report_type'] == "report_by_affiliates" )
						Report By Affiliates
					@elseif( $vars['report_type'] == "report_by_payments" )
						Report By Payments
					@endif
					<div class="pull-right">
						<a href="{{ URL::to('/sa/reports') }}?report_type=report_by_affiliates" class="btn btn-primary btn-sm">Report By Affiliates</a>
						<a href="{{ URL::to('/sa/reports') }}?report_type=report_by_payments" class="btn btn-warning btn-sm">Payments Received</a>
					</div>
				</div>
				<div class="panel-body">
					
					@if( $vars['report_type'] == "report_by_affiliates" )
						
						<div class="col-md-2"></div>
						<div class="col-md-8 text-center">
							<form role="form" class="form-inline filter-form">
								<fieldset>
									<legend>Filters</legend>
									<input type="hidden" name="report_type" value="{{ $vars['report_type'] }}" />
									<input type="text" class="form-control datepicker1" name="start_date" value="{{ $vars['start_date'] }}" placeholder="Start Date" />
									<input type="text" class="form-control datepicker1" name="end_date" value="{{ $vars['end_date'] }}" placeholder="End Date" />
									<input type="submit" class="btn btn-info"  value="Submit" />
									<a href="{{ URL::to('/sa/reports') }}?report_type={{ $vars['report_type'] }}" class="btn btn-danger">Clear</a>
								</fieldset>
							</form>
						</div>
						<div class="col-md-2"></div>
						
						<table id="datatable" class="display" cellspacing="0" width="100%">
							<thead>
								<th class="text-center">Referral Name</th>
								<th class="text-center">Referred On</th>
								<th class="text-center">Referral Commission (%)</th>
								<th class="text-center">Earned ($)</th>
							</thead>
							<tbody>
								<?php $totals = array("commission"=>0.00); ?>
								@foreach( $vars['referrals'] as $referral )
									<tr>
										<td class="text-center">{{ $referral->name }}</td>
										<td class="text-center">{{ date("Y-m-d",strtotime($referral->created_at)) }}</td>
										<td class="text-center">{{ number_format($referral->commission,2) }}</td>
										<td class="text-center">{{ number_format($referral->total_commission,2) }}</td>
									</tr>
									<?php $totals["commission"] += $referral->total_commission; ?>
								@endforeach
							</tbody>
							<tfoot>
								<td class="text-center">TOTALS</td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center">{{ number_format($totals["commission"],2) }}</td>
							</tfoot>
						</table>
					
					@elseif( $vars['report_type'] == "report_by_payments" )
						<div class="col-md-2"></div>
						<div class="col-md-8 text-center">
							<form role="form" class="form-inline filter-form">
								<fieldset>
									<legend>Filters</legend>
									<input type="hidden" name="report_type" value="{{ $vars['report_type'] }}" />
									<input type="text" class="form-control datepicker1" name="start_date" value="{{ $vars['start_date'] }}" placeholder="Start Date" />
									<input type="text" class="form-control datepicker1" name="end_date" value="{{ $vars['end_date'] }}" placeholder="End Date" />
									<input type="submit" class="btn btn-info"  value="Submit" />
									<a href="{{ URL::to('/sa/reports') }}?report_type={{ $vars['report_type'] }}" class="btn btn-danger">Clear</a>
								</fieldset>
							</form>
						</div>
						<div class="col-md-2"></div>
						
						<table id="datatable" class="display" cellspacing="0" width="100%">
							<thead>
								<th class="text-center">Payment Date</th>
								<th class="text-center">Amount Paid ($)</th>
								<th class="text-center">Payment Email</th>
							</thead>
							<tbody>
								<?php $totals = array("payment"=>0.00); ?>
								@foreach( $vars['pay_details'] as $pay )
									<tr>
										<td class="text-center">{{ date("Y-m-d",strtotime($pay['created_at'])) }}</td>
										<td class="text-center">{{ number_format($pay['amount'],2) }}</td>
										<td class="text-center">{{ $pay['payment_email'] }}</td>
									</tr>
									<?php $totals["payment"] += $pay['amount']; ?>
								@endforeach
							</tbody>
							<tfoot>
								<td class="text-center">TOTALS</td>
								<td class="text-center">{{ number_format($totals["payment"],2) }}</td>
								<td class="text-center"></td>
							</tfoot>
						</table>
					@else
						
					@endif
					
				</div>
				
				<div class="panel-footer">
					* Net revenue is calculated by deducting 20% of service tax.
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<style>
	.banner-img{ display:none; }
	.panel-heading{ height: 51px; }
</style>

@endsection
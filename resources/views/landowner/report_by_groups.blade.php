@if( $vars['export'] === '' )
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="com"></div>
			<div class="panel panel-default">
				<div class="panel-heading panel-heading-lg">
					<a href="{{ URL::to('/home/report') }}" class="btn btn-default btn-sm">Overview</a>
					<a href="{{ URL::to('/home/report_by_groups') }}" class="btn btn-default btn-sm selected">Report By Groups</a>
					<a href="{{ URL::to('/home/report_by_meters') }}" class="btn btn-default btn-sm">Report By Meters</a>
				</div>
				<div class="panel-body">
					@if ( $vars['tab'] === 'report_by_groups' && count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					@if( $vars['tab'] === 'report_by_groups' && Session::has('success'))
						<div class="alert alert-success">
							<strong>Success!</strong> {{ Session::get('success') }}
						</div>
					@endif
						
					<div class="text-center">
						<form role="form" class="form-inline filter-form" id="filter-form">
							<input type="hidden" name="export" id="export" value="">
							<fieldset>
								<span class="to-text"><b>Filter Results</b></span>
								<input type="text" class="form-control datepicker1" name="start_date" value="{{ $vars['start_date'] }}" placeholder="Start Date" />
								<input type="text" class="form-control datepicker1" name="end_date" value="{{ $vars['end_date'] }}" placeholder="End Date" />
								<input type="submit" class="btn btn-default"  value="Submit" />
								<input type="reset" class="btn btn-default" value="Clear">
							</fieldset>
						</form>
					</div>
						<br />
@else

	<h2>Report By Groups ( @if( $vars['start_date'] !== '' ) From: {{ $vars['start_date'] }} @endif  @if( $vars['end_date'] !== '' ) To: {{ $vars['end_date'] }} @endif  ) </h2>
	
	@if( $vars['export'] === 'PDF' )
		<html>
		<head>
		</head>
		<body style="padding:0px; margin:0px;border: 3px solid gray;">
	@endif
	
@endif

					<table id="datatable" class="display" cellspacing="0" width="100%" @if( $vars['export'] !== '' ) border="1" @endif >
						<thead>
							<tr>
								<th class="text-center">Group Name</th>
								<th class="text-center">Group Price ( $/hr )</th>
								<th class="text-center">No. of Meters</th>
								<th class="text-center">No. of Transactions</th>
								<?php /*?><th class="text-center">Total Revenue($)</th><?php */?>
								<th class="text-center">Revenue($)</th>
							</tr>
						</thead>
						
						<tbody>
							<?php $totals = array("revenue"=>0.00,"transactions"=>0.00,"meters"=>0,"net_revenue"=>0.00); ?>
							
							@foreach( $vars['lot_details'] as $lot )
								<tr>
									<td class="text-center">
										@if( $vars['export'] === '' )
											<a href="/home/report_by_meters?group_id={{ $lot->id }}&start_date={{ $vars['start_date'] }}&end_date={{ $vars['end_date'] }}">{{ $lot->lot_name }}</a>
										@else
											{{ $lot->lot_name }}
										@endif
									</td>
									<td class="text-center">{{ $lot->price }}</td>
									<td class="text-center">{{ $lot->meter_count }}</td>
									<td class="text-center">{{ $lot->transactions }}</td>
									<?php /*?><td class="text-center">{{ number_format($lot->trans_amount,2) }}</td><?php */?>
									<?php $net_revenue =  ( $lot->trans_amount * 80 )/100;  ?>
									<td class="text-center">{{ number_format($net_revenue,2) }}</td>
								</tr>
								<?php 
									$totals["revenue"] += $lot->trans_amount;  $totals["transactions"] += $lot->transactions; $totals["meters"] += $lot->meter_count; $totals["net_revenue"] += $net_revenue; 
								?>
							@endforeach
							
						</tbody>
						<tfoot>
							<tr>
								<td class="text-center">TOTALS</td>
								<td class="text-center"></td>
								<td class="text-center">{{ $totals["meters"] }}</td>
								<td class="text-center">{{ $totals["transactions"] }}</td>
								<?php /*?><td class="text-center">{{ number_format($totals["revenue"],2) }}</td><?php */?>
								<td class="text-center">{{ number_format($totals["net_revenue"],2) }}</td>
							</tr>
						</tfoot>
						
					</table>

@if( $vars['export'] === '' )
					@include('includes.export-buttons')
				</div>
				
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

@else
		@if( $vars['export'] === 'PDF' )
			</body>
			</html>
		@endif
@endif
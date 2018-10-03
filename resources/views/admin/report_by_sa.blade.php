@if( $vars['export'] === '' )
<div class="col-md-12 text-center">
	<form role="form" class="form-inline filter-form" id="filter-form">
		<input type="hidden" name="export" id="export" value="">
		<fieldset>
			<legend>Filters</legend>
			<input type="hidden" name="report_type" value="{{ $vars['report_type'] }}" />
			<input type="text" class="form-control datepicker1" name="start_date" value="{{ $vars['start_date'] }}" placeholder="Start Date" />
			<input type="text" class="form-control datepicker1" name="end_date" value="{{ $vars['end_date'] }}" placeholder="End Date" />
			<input type="submit" class="btn btn-default"  value="Submit" />
			<input type="reset" class="btn btn-default" value="Clear">
		</fieldset>
	</form>
</div>

@else

	<h2>Report By @if( $vars['report_type'] === 'report_by_sm' )  Sales Managers @else Sales Associates @endif ( @if( $vars['start_date'] !== '' ) From: {{ $vars['start_date'] }} @endif  @if( $vars['end_date'] !== '' ) To: {{ $vars['end_date'] }} @endif  ) </h2>


@endif

<table id="datatable" class="display" cellspacing="0" width="100%" @if( $vars['export'] !== '' ) border="1" @endif >
	<thead>
		<tr>
			<th class="text-center">Name</th>
			<th class="text-center">Email</th>
			<th class="text-center">No of Referrals</th>
			<th class="text-center">$ Earned</th>
			<th class="text-center">$ Received</th>
			<th class="text-center">Current Balance ($)</th>
		</tr>
	</thead>
	<tbody>
		<?php $totals = array("paid"=>0.00,"received"=>0.00,"balance"=>0.00,"referrals"=>0,); ?>
		@foreach( $vars['users'] as $user )
			<tr>
				<td class="text-center">
					@if( $vars['export'] === '' )
						@if( $vars['report_type'] === 'report_by_sm' )
						@else
							<a href="?report_type=report_by_lo&referred_by={{ $user->id }}&start_date={{ $vars['start_date'] }}&end_date={{ $vars['end_date'] }}">{{ $user->name }}</a>
						@endif
					@else
						{{ $user->name }}
					@endif
				</td>
				<td class="text-center">{{ $user->email }}</td>
				<td class="text-center">{{ $vars["referred"][$user->id] }}</td>
				<td class="text-center">{{ number_format($vars["total_paid"][$user->id],2) }}</td>
				<td class="text-center">{{ number_format($vars["total_received"][$user->id],2) }}</td>
				<td class="text-center">{{ number_format($user->balance,2) }}</td>
			</tr>
			<?php 
				$totals["paid"] += $vars["total_paid"][$user->id];  $totals["received"] += $vars["total_received"][$user->id]; $totals["balance"] += $user->balance; $totals["referrals"] += $vars["referred"][$user->id];
			?>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<td class="text-center">TOTALS</td>
			<td class="text-center"></td>
			<td class="text-center">{{ $totals["referrals"] }}</td>
			<td class="text-center">{{ number_format($totals["paid"],2) }}</td>
			<td class="text-center">{{ number_format($totals["received"],2) }}</td>
			<td class="text-center">{{ number_format($totals["balance"],2) }}</td>
		</tr>
	</tfoot>
</table>

@if( $vars['export'] === '' )
	@include('includes.export-buttons')
@endif

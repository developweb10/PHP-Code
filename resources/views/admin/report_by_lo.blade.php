@if( $vars['export'] === '' )
<div class="col-md-12 text-center">
	<form role="form" class="form-inline filter-form" id="filter-form">
		<input type="hidden" name="export" id="export" value="">
		<fieldset>
			<legend>Filters</legend>
			<input type="hidden" name="report_type" value="{{ $vars['report_type'] }}" />

			&nbsp;&nbsp;&nbsp;&nbsp;
			<select name="referred_by" class="form-control">
				<option value="">Referred By</option>
				@foreach( $vars["sales_assoicates"] as $sa )
					<option value="{{ $sa->id }}" @if( $vars['referred_by'] === $sa->id ) selected="selected" @endif >{{ $sa->name }}</option>
				@endforeach
			</select>
			&nbsp;&nbsp;&nbsp;&nbsp;

			<input type="text" class="form-control datepicker1" name="start_date" value="{{ $vars['start_date'] }}" placeholder="Start Date" />
			<input type="text" class="form-control datepicker1" name="end_date" value="{{ $vars['end_date'] }}" placeholder="End Date" />
			<input type="submit" class="btn btn-default"  value="Submit" />
			<input type="reset" class="btn btn-default" value="Clear">
		</fieldset>
	</form>
</div>

@else

	<h2>Report By Landowners ( @if( $vars['start_date'] !== '' ) From: {{ $vars['start_date'] }} @endif  @if( $vars['end_date'] !== '' ) To: {{ $vars['end_date'] }} @endif  ) </h2>


@endif

<table id="datatable" class="display" cellspacing="0" width="100%" @if( $vars['export'] !== '' ) border="1" @endif >
	<thead>
		<tr>
			<th class="text-center">Name</th>
			<th class="text-center">Email</th>
			<th class="text-center">No of Groups</th>
			<th class="text-center">No of Meters</th>
			<th class="text-center">Current Balance ($)</th>
			<th class="text-center">$ Spent</th>
			<th class="text-center">$ Earned</th>
			<th class="text-center">$ Received</th>
		</tr>
	</thead>
	<tbody>
		<?php $totals = array("paid"=>0.00,"received"=>0.00,"balance"=>0.00,"lots"=>0,"meters"=>0,"spent"=>0); ?>
		@foreach( $vars['users'] as $user )
			<tr>
				<td class="text-center">
					@if( $vars['export'] === '' )
						<a href="?report_type=report_by_lots&lo={{ $user->id }}&start_date={{ $vars['start_date'] }}&end_date={{ $vars['end_date'] }}">{{ $user->name }}</a>
					@else
						{{ $user->name }}
					@endif
				</td>
				<td class="text-center">{{ $user->email }}</td>
				<td class="text-center">{{ $user->lots }}</td>
				<td class="text-center">{{ $user->meters }}</td>
				<td class="text-center">{{ number_format($user->balance,2) }}</td>
				<td class="text-center">{{ number_format($vars["total_spent"][$user->id],2) }}</td>
				<td class="text-center">{{ number_format($vars["total_paid"][$user->id],2) }}</td>
				<td class="text-center">{{ number_format($vars["total_received"][$user->id],2) }}</td>
			</tr>
			<?php 
				$totals["paid"] += $vars["total_paid"][$user->id];  $totals["received"] += $vars["total_received"][$user->id]; $totals["balance"] += $user->balance; $totals["lots"] += $user->lots; $totals["meters"] += $user->meters;  $totals["spent"] += $vars["total_spent"][$user->id];
			?>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<td class="text-center">TOTALS</td>
			<td class="text-center"></td>
			<td class="text-center">{{ $totals["lots"] }}</td>
			<td class="text-center">{{ $totals["meters"] }}</td>
			<td class="text-center">{{ number_format($totals["balance"],2) }}</td>
			<td class="text-center">{{ number_format($totals["spent"],2) }}</td>
			<td class="text-center">{{ number_format($totals["paid"],2) }}</td>
			<td class="text-center">{{ number_format($totals["received"],2) }}</td>
		</tr>
	</tfoot>
</table>


@if( $vars['export'] === '' )
	@include('includes.export-buttons')
@endif
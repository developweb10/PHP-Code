@if( $vars['export'] === '' )
<div class="col-md-12 text-center">
	<form role="form" class="form-inline filter-form" id="filter-form">
		<input type="hidden" name="export" id="export" value="">
		<fieldset>
			<legend>Filters</legend>
			<input type="hidden" name="report_type" value="{{ $vars['report_type'] }}" />
			
			&nbsp;&nbsp;&nbsp;&nbsp;
			<select name="lo" class="form-control">
				<option value="">Select Landowner</option>
				@foreach( $vars["landowners"] as $lo )
					<option value="{{ $lo->id }}"  @if( $lo->id == $vars['lo'] ) selected="selected" @endif >{{ $lo->name }}</option>
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

	<h2>Report By Groups ( @if( $vars['start_date'] !== '' ) From: {{ $vars['start_date'] }} @endif  @if( $vars['end_date'] !== '' ) To: {{ $vars['end_date'] }} @endif  ) </h2>
	
@endif

<table id="datatable" class="display" cellspacing="0" width="100%" @if( $vars['export'] !== '' ) border="1" @endif >
	<thead>
		<tr>
			<th class="text-center">Group Name</th>
			<th class="text-center">Group Price ( $/hr )</th>
			<th class="text-center">Landowner</th>
			<th class="text-center">No. of Meters</th>
			<th class="text-center">No. of Transactions</th>
			<th class="text-center">Total Revenue($)</th>
		</tr>
	</thead>
	<tbody>
		<?php $totals = array("revenue"=>0.00,"transactions"=>0.00,"meters"=>0); ?>
		@foreach( $vars['lot_details'] as $lot )
			<tr>
				<td class="text-center">
					@if( $vars['export'] === '' )
						<a href="?report_type={{ $vars['report_type'] }}&lot_id={{ $lot->id }}">{{ $lot->lot_name }}</a>
					@else
						{{ $lot->lot_name }}
					@endif
				</td>
				<td class="text-center">{{ $lot->price }}</td>
				<td class="text-center">{{ $lot->name }}</td>
				<td class="text-center">{{ $lot->meter_count }}</td>
				<td class="text-center">{{ $lot->transactions }}</td>
				<td class="text-center">{{ number_format($lot->trans_amount,2) }}</td>
			</tr>
			<?php 
				$totals["revenue"] += $lot->trans_amount;  $totals["transactions"] += $lot->transactions; $totals["meters"] += $lot->meter_count; 
			?>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			<td class="text-center">TOTALS</td>
			<td class="text-center"></td>
			<td class="text-center"></td>
			<td class="text-center">{{ $totals["meters"] }}</td>
			<td class="text-center">{{ $totals["transactions"] }}</td>
			<td class="text-center">{{ number_format($totals["revenue"],2) }}</td>
		</tr>
	</tfoot>
</table>

@if( $vars['export'] === '' )
	@include('includes.export-buttons')
@endif

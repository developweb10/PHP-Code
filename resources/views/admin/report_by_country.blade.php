@if( $vars['export'] === '' )
	<div class="col-md-12 text-center">
		<form role="form" class="form-inline filter-form" id="filter-form" style="display:none;">
			<input type="hidden" name="export" id="export" value="">
			<input type="hidden" name="report_type" value="{{ $vars['report_type'] }}" />
		</form>
	</div>
@else

	<h2>Report By Country</h2>

@endif

<table id="datatable" class="display" cellspacing="0" width="100%" @if( $vars['export'] !== '' ) border="1" @endif >
	<thead>
		<tr>
			<th>Country</th>
			<th class="text-center">Landowners</th>
			<th class="text-center">Associates</th>
			<th class="text-center">Active Meters</th>
			<th class="text-center">Total Revenue</th>
			<th class="text-center">Profit</th>
		</tr>
	</thead>
	<tbody>
		<?php $totals = array("landowners"=>0.00,"associates"=>0.00,"active_meters"=>0.00,"revenue"=>0.00,"profit"=>0.00); ?>
		@if( isset($vars['country_list']) and count( $vars['country_list'] ) )
			@foreach( $vars['country_list'] as $country=>$details )
				<tr>
					<td>{{ $country }}</td>
					<td class="text-center">{{ $details["lo"] }}</td>
					<td class="text-center">{{ $details["sa"] }}</td>
					<td class="text-center">{{ $details["active_meters"] }}</td>
					<td class="text-center">{{ number_format($details["revenue"],2) }}</td>
					<td class="text-center">{{ number_format($details["profit"],2) }}</td>
				</tr>
				<?php 
					$totals["landowners"] += $details["lo"];  $totals["associates"] += $details["sa"]; $totals["active_meters"] += $details["active_meters"]; $totals["revenue"] += $details["revenue"]; $totals["profit"] += $details["profit"];
				?>
			@endforeach
		@endif								
	</tbody>
	<tfoot>
		<tr>
			<td class="text-center">TOTALS</td>
			<td class="text-center">{{ $totals["landowners"] }}</td>
			<td class="text-center">{{ $totals["associates"] }}</td>
			<td class="text-center">{{ $totals["active_meters"] }}</td>
			<td class="text-center">{{ number_format($totals["revenue"],2) }}</td>
			<td class="text-center">{{ number_format($totals["profit"],2) }}</td>
		</tr>
	</tfoot>
</table>
@if( $vars['export'] === '' )
	@include('includes.export-buttons')
@endif
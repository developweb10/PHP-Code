@if( $vars['export'] === '' )
<div class="col-md-12 text-center">
	<form role="form" class="form-inline filter-form" id="filter-form">
		<input type="hidden" name="export" id="export" value="">
		<input type="hidden" name="report_type" value="{{ $vars['report_type'] }}" />
		<fieldset>
			<legend>Filters</legend>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<select name="country" id="country_list" class="form-control">
				<option value="">Select Country</option>
				@foreach( $vars['countries'] as $country )
					<option value="{{ $country->id }}" @if( $vars['country'] == $country->id ) selected="selected" @endif >{{ $country->nicename }}</option>
				@endforeach
			</select>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<select name="state" id="state_list" class="form-control">
				<option value="">Select State/Province</option>
				@if( count($vars['states']) )
					@foreach( $vars['states'] as $state )
						<option value="{{ $state->state_code }}" @if( $vars['state'] == $state->state_code ) selected="selected" @endif >{{ $state->state_code }}</option>
					@endforeach
				@endif
			</select>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<select name="city" id="city_list" class="form-control">
				<option value="">Select City</option>
				@if( count($vars['cities']) )
					@foreach( $vars['cities'] as $city )
						<option value="{{ $city->city_name }}" @if( $vars['city'] == $city->city_name ) selected="selected" @endif >{{ $city->city_name }}</option>
					@endforeach
				@endif
			</select>

			<input type="submit" class="btn btn-default"  value="Submit" />
			<input type="reset" class="btn btn-default" value="Clear" onclick="window.location='?report_type=report_by_city'">
		</fieldset>
	</form>
</div>

@else

	<h2>Report By City</h2>

@endif
<table id="datatable" class="display" cellspacing="0" width="100%" @if( $vars['export'] !== '' ) border="1" @endif >
	<thead>
		<tr>
			<th>City</th>
			<th class="text-center">Landowners</th>
			<th class="text-center">Associates</th>
			<th class="text-center">Active Meters</th>
			<th class="text-center">Total Revenue</th>
			<th class="text-center">Profit</th>
		</tr>
	</thead>
	<tbody>
		<?php $totals = array("landowners"=>0.00,"associates"=>0.00,"active_meters"=>0.00,"revenue"=>0.00,"profit"=>0.00); ?>
		@if( isset($vars['city_list']) and count( $vars['city_list'] ) )
			@foreach( $vars['city_list'] as $city=>$details )
				<tr>
					<td>{{ $city }}</td>
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
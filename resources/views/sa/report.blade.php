<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="com"></div>
			<div class="panel panel-default">
				<div class="panel-heading">
					Report By Affiliates
				</div>
				<div class="panel-body">
					<div class="col-md-2"></div>
					<div class="col-md-8 text-center">
						<form role="form" class="form-inline filter-form" action="{{ URL::to('/sa-home/report') }}" >
							<fieldset>
								<legend>Filters</legend>
								<input type="text" class="form-control datepicker1" name="start_date" value="{{ $vars['start_date'] }}" placeholder="Start Date" />
								<input type="text" class="form-control datepicker1 smallmargin" name="end_date" value="{{ $vars['end_date'] }}" placeholder="End Date" />
								<input type="submit" class="btn btn-info smallmargin"  value="Submit" />
								<input type="reset" class="btn btn-danger smallmargin" value="Clear">
							</fieldset>
						</form>
					</div>
					<div class="col-md-2"></div>
					<table id="datatable" class="display" cellspacing="0" width="100%">
						<thead>
							<th class="text-center">Referral Name</th>
							<th class="text-center displaynoneonsmall">Referred On</th>
							<th class="text-center">Referral Commission (%)</th>
							<th class="text-center">Earned ($)</th>
						</thead>
						<tbody>
							<?php $totals = array("commission"=>0.00); ?>
							@foreach( $vars['referrals'] as $referral )
								<tr>
									<td class="text-center">{{ $referral->name }}</td>
									<td class="text-center displaynoneonsmall">{{ date("Y-m-d",strtotime($referral->created_at)) }}</td>
									<td class="text-center">{{ number_format($referral->commission,2) }}</td>
									<td class="text-center">{{ number_format($referral->total_commission,2) }}</td>
								</tr>
								<?php $totals["commission"] += $referral->total_commission; ?>
							@endforeach
						</tbody>
						<tfoot>
							<td class="text-center">TOTALS</td>
							<td class="text-center displaynoneonsmall"></td>
							<td class="text-center"></td>
							<td class="text-center">{{ number_format($totals["commission"],2) }}</td>
						</tfoot>
					</table>
				</div>
				
				<div class="panel-footer">
					* Net revenue is calculated by deducting 20% of service tax.
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
                	Admin Dashboard
                </div>
				<div class="panel-body">
					
					<div class="row">
						<div class="col-md-4 text-center">
							<h3>Sales Managers</h3>
							<div class="total-value blue-text">{{ $vars["statistics"]->sm }}</div>
						</div>
						<div class="col-md-4 text-center">
							<h3>Sales Associates</h3>
							<div class="total-value blue-text">{{ $vars["statistics"]->sa }}</div>
						</div>
						<div class="col-md-4 text-center">
							<h3>Landowners</h3>
							<div class="total-value blue-text">{{ $vars["statistics"]->lo }}</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<hr />
					<div class="row">
						<div class="col-md-6 text-center">
							<h3>Groups</h3>
							<div class="total-value blue-text">{{ $vars["statistics"]->groups }}</div>
						</div>
						<div class="col-md-6 text-center">
							<h3>Meters</h3>
							<div class="total-value blue-text">{{ $vars["statistics"]->meters }}</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<hr />
					<div class="row">
						<div class="col-md-6 text-center">
							<h3>Meters Purchased</h3>
							<div class="total-value">{{ $vars["statistics"]->meter_trans }}</div>
						</div>
						<div class="col-md-6 text-center">
							<h3>Purchase Value</h3>
							<div class="total-value">${{ number_format($vars["statistics"]->meter_trans_amnt,2) }}</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<hr />
					<div class="row">
						<div class="col-md-6 text-center">
							<h3>Meter Transactions</h3>
							<div class="total-value">{{ $vars["statistics"]->trans }}</div>
						</div>
						<div class="col-md-6 text-center">
							<h3>Meter Transaction Amount</h3>
							<div class="total-value">${{ number_format($vars["statistics"]->trans_amnt,2) }}</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<hr />
					<div class="row">
						<div class="col-md-6 text-center">
							<h3>Total Payouts</h3>
							<div class="total-value red-text">{{ $vars["statistics"]->payouts }}</div>
						</div>
						<div class="col-md-6 text-center">
							<h3>Total Payout Amount</h3>
							<div class="total-value red-text">${{ number_format($vars["statistics"]->paid_amnt,2) }}</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<hr />
					<div class="row">
						<div class="col-md-6 text-center">
							<h3>Total Revenue TETS</h3>
							<div class="total-value grand-total">${{ number_format($vars["statistics"]->total_revenue,2) }}</div>
						</div>
						<div class="col-md-6 text-center">
							<h3>Total Profit</h3>
							<div class="total-value grand-total red-text">${{ number_format($vars["statistics"]->total_profit,2) }}</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-2"></div>
		<div class="clearfix"></div>
	</div>
</div>
@endsection
<div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 text-center bottomborderonsmall">
	<h3>Meters</h3>
	<h1>{{ $vars['meters'] }}</h1>
</div>
<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 text-center bottomborderonsmall">
	<h3>Transactions</h3>
	<h1>{{ $vars['transactions'] }}</h1>
</div>
<div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 text-center bottomborderonsmall">
	<h3>Hours Rented</h3>
	<h1>{{ number_format($vars['hours']) }}</h1>
</div>
<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-center">
	<h3>Total Revenue</h3>
	<h1><span>$</span>{{ number_format($vars['total_revenue'],2) }}</h1>
</div>
<div class="clearfix"></div>

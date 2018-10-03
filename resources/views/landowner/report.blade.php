<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading panel-heading-lg">
					<a href="{{ URL::to('/home/report') }}" class="btn btn-default btn-sm selected">Overview</a>
					<a href="{{ URL::to('/home/report_by_groups') }}" class="btn btn-default btn-sm">Report By Groups</a>
					<a href="{{ URL::to('/home/report_by_meters') }}" class="btn btn-default btn-sm">Report By Meters</a>
				</div>
				<div class="panel-body">
					@if ( $vars['tab'] === 'report' && count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					@if( $vars['tab'] === 'report' && Session::has('success'))
						<div class="alert alert-success">
							<strong>Success!</strong> {{ Session::get('success') }}
						</div>
					@endif

					<form class="form-inline text-center filter-form" role="form" method="POST" action="{{ url('/home/report') }}" id="report_filter_form">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<fieldset>
							<span class="to-text"><b>Filter Results</b></span>
							<input type="text" class="form-control datepicker1" name="start_date" value="<?php echo date("Y-m-d",strtotime(date("Y-m-d")." -0 years -0 months -30 days")); ?>" placeholder="Start Date">
							<input type="text" class="form-control datepicker1" name="end_date" value="<?php echo date("Y-m-d"); ?>" placeholder="End Date" >
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" class="form-control" name="meter_id" value="" placeholder="Meter #">
							&nbsp;&nbsp;&nbsp;&nbsp;
							<select class="form-control" name="lot_id" placeholder="Group">
								<option value="">Select Group</option>
								@foreach( $mylots as $lot )
									<option value="{{ $lot->id }}">{{ $lot->lot_name }}</option>
								@endforeach
							</select>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="button" class="btn btn-default"  value="Submit" id="submit_report" />
							<input type="reset" class="btn btn-default" value="Clear">
						</fieldset>
					</form>
					
					<br />
					
					<div class="report_statistics">
					</div>
					
					<br />
					
					<div class="report_chart text-center">
						<br /><br />
						<div id="canvas_container">
							<canvas id="canvas" height="300" width="600"></canvas>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

@if ( $vars['tab'] === 'report' )
	<script>var chartInitialized = true;</script>
@endif
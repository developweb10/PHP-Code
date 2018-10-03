<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Report Dashboard
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

					<form class="form-inline text-center" role="form" method="POST" action="{{ url('/home/report') }}" id="report_filter_form">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div>
							<div class="col-md-5 report-bordered">
								<div class="col-md-5"><input type="text" class="form-control datepicker1 full-width" name="start_date" value="" placeholder="When?"></div>
								<div class="col-md-2 to-text">to</div>
								<div class="col-md-5"><input type="text" class="form-control datepicker1 full-width" name="end_date" value="" placeholder="When?" ></div>
							</div>
							<div class="col-md-2 report-bordered">
								<input type="text" class="form-control full-width" name="meter_id" value="" placeholder="Meter #">
							</div>
							<div class="col-md-2 report-bordered">
								<select class="form-control full-width" name="lot_id" placeholder="Group">
									<option value="">Select Group</option>
									@foreach( $mylots as $lot )
										<option value="{{ $lot->id }}">{{ $lot->lot_name }}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-2 report-bordered">
								<button type="button" class="btn btn-success full-width" id="submit_report">
									Submit
								</button>
							</div>
							<div class="clearfix"></div>
						</div>
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
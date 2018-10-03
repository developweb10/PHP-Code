@extends('welcome-app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Add More Time</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					@if( Session::has('success'))
						<div class="alert alert-success">
							<strong>Success!</strong> {{ Session::get('success') }}
						</div>
					@endif
					
					@include('includes.my-meter-form')
					
				</div>
			</div>
		</div>
    </div>
</div>
<script>var meter_per_hour_price = {{ $meter['hour_price'] }};</script>

<style type="text/css">
	@if( config('is_mobile') )
		#footer{ display: none; }
	@endif
</style>
@endsection
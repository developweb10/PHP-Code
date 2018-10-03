@extends('welcome-app')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading"><i class="fa fa-lock"></i> {{ $data->page_title }}</div>
					<div class="panel-body" id="body_container">
						{{ app('App\Http\Controllers\UtilsController')->html_decode($data->page_content) }}
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
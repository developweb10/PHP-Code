@extends('app')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">{{ $data->page_title }}</div>
					<div class="panel-body" id="body_container">
						{{ app('App\Http\Controllers\UtilsController')->html_decode($data->page_content) }}
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
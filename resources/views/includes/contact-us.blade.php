@extends('welcome-app')


@section('content')
	<div class="container-fluid">
        <div class="row">
			<div class="col-md-8 col-md-offset-2">
            	<?php $hide_error = true; $show_success = true; ?>
                @include("includes.contact-us-form")
            </div>
        </div>
    </div>
@endsection
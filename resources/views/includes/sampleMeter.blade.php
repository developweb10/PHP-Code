@extends('welcome-app')
@section('content')
<div class="container-fluid">
	<div class="row">
    	<div class="col-md-8 col-md-offset-2">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile-padding-0">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="">
                            @include('includes.sampleMeter-form') 
                        </div>		
                     </div>                 
                </div>
              </div>		
         </div>
	</div>
</div>
@endsection
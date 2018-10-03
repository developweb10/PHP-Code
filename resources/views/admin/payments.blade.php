@extends('app')

<?php

	$user_role = "";
	if(isset($_GET["pay"])){
		$user_role = $_GET["pay"];
	}
?>

@section('content')

<div class="container-fluid">

	<div class="row">

		

		<div class="col-md-12">

			<div class="panel panel-default">

				<div class="panel-heading">Payment Details</div>

				<div class="panel-body">

					@if ( count($errors) > 0)

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

					

					<Br />

					<div class="col-md-2">

						<a href="?pay=sm" class="btn btn-default btn-block">Sales Managers</a>

					</div>


					<div class="col-md-2">

						<a href="?pay=sa" class="btn btn-default btn-block">Sales Associates</a>

					</div>


					<div class="col-md-2">

						<a href="?pay=lo" class="btn btn-default btn-block">Landowners</a>

					</div>

					
					<div class="col-md-2">

						<a href="?pay=" class="btn btn-default btn-block">All</a>

					</div>
                    
                    <div class="col-md-2">

						<a href="?pay=exported" class="btn btn-default btn-block">Exported Payables</a>

					</div>

					<div class="pull-right">
                    	<a href="payoutsemt?pay=<?php echo $user_role; ?>" class="btn btn-primary" name="export_payments" value="Export"> <i class="fa fa-download"></i> Export Payables </a>
                    </div>
                    
					<div class="clearfix"></div>

					

					<Br /><Br />

					<div class="text-right">

						<a href="?pay=@if( isset( $vars["pay"] ) ){{ $vars["pay"] }}@endif&export=PDF" class="btn btn-default" >PDF</a>

						<a href="?pay=@if( isset( $vars["pay"] ) ){{ $vars["pay"] }}@endif&export=Excel" class="btn btn-default" >Excel</a>					

					</div>

					<Br /><Br />
					@if( isset($payouts) )
                    	@include('admin.payoutsemt')
                        
                    @else
                    	@include('admin.payments-table')
                    @endif
					

				</div>

			</div>

		</div>

		<div class="col-md-2"></div>

		<div class="clearfix"></div>

	</div>

</div>



@endsection
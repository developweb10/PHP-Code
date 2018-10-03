@extends('app')

@section('content')

<div class="">

	<div class="row">

		<div class="col-md-12">

			<div class="com"></div>

			<div class="panel panel-default">

				<div class="col-md-12 meter-buttons text-left">

					<br>
                    
                    <a href="https://my-meter.com/dev/public/admin/createuser?show_layout=false&admin=1" class="btn btn-default btn-sm" data-toggle="modal" data-target="#createNewUserModal" data-static="false" data-keyboard="false" onclick="openModalLoader()">Add Landowner</a>

				</div>

				<div class="clearfix"></div>

				<div class="panel-body">
                	
                    <?php /* ?> isset($vars['tab']) && $vars['tab'] === '' &&   <?php */ ?>
					
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

					<?php /*?> isset($vars['tab']) && $vars['tab'] === '' &&  <?php */?>

					@if( Session::has('success'))

						<div class="alert alert-success">

							<strong>Success!</strong> {{ Session::get('success') }}

						</div>

					@endif
					
                    
                    <!-- List of Landowners created by admin -->
					<div class="table-responsive">
                        <table id="datatable" width="100%" class="display compact" cellspacing="0" >
                            <thead>
                              <tr>
                                <th class="col-md-2 text-center">City</th>
                                <th class="col-md-2 text-center">Name</th>
                                <th class="col-md-2 text-center">Email</th>
                                <th class="col-md-2 text-center">Meters</th>
                                <th class="col-md-2 text-center">Total Revenue</th>
                                <th class="col-md-2 text-center">Next Payout</th>
                              </tr>
                            </thead>
                            <tbody>
                            	<?php // echo "<pre>"; print_r($landowner_users); echo "</pre>"; ?>
                                @foreach( $landowner_users as $user )
                                <tr>
                                  <td class="col-md-2 text-center">{{ $user->city_name }}</td> 
                                  <td class="col-md-2 text-center"><a href="{{ url('/admin/client?id=') }}{{ $user->id }}">{{ $user->name }}</a></td> <!-- Click on name to view and edit meter/group page -->
                                  <td class="col-md-2 text-center">{{ $user->email }}</td>
                                  <td class="col-md-2 text-center">{{ $user->meter_count }}</td> <!--$vars["revenue"][$user->id]-->
                                  <td class="col-md-2 text-center">  <!-- Total Income -->
                                  	@if($user->trans_amnt)
	                                    {{ $user->trans_amnt }}
                                    @else
                                    	0.00
                                    @endif
                                  </td>
                                  <td class="col-md-2 text-center">{{ $user->balance }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        
                      </table>
                    </div>
                </div>
                
            </div>
       
       </div>     

    </div>     

</div>     

<div id="createNewUserModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>
        
@endsection
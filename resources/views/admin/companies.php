@extends('app')

@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
                	Towing Companies
                    
				</div>
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
					
					<table id="companies_datatable" width="100%" class="display compact companies_table" cellspacing="0" >
						<thead>
							<tr>
	                            <th class="col-md-2 text-center">ID</th>
                                <th class="col-md-1 text-center">Province</th>
                            	<th class="col-md-2 text-center">City </th>
								<th class="col-md-2 text-center">Towing Company</th>
								<th class="col-md-2 text-center">Contact Number</th>
								<!--<th class="col-md-2 text-center">Action</th>-->
							</tr>
						</thead>
						<tbody>
							
							@foreach( $companies as $order )
								<tr>
                                	<td class="col-md-2 text-center">{{ $order->id }}</td>
                                    <td class="col-md-2 text-center"><a data-toggle="modal" data-target="#view-modal" data-id="{{ $order->id }}" id="getOrder"> {{ $order->state_code }} </a></td>
									<td class="col-md-2 text-center">{{ $order->city_name }}</td>
									<td class="col-md-2 text-center">{{ $order->company }}</td>
									<td class="col-md-1 text-center">{{ $order->contact_no }}</td>
									
								</tr>
							@endforeach
							
						</tbody>
					</table>
					
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
     <div class="modal-dialog"> 
          <div class="modal-content"> 
          
               <div class="modal-header"> 
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> 
                    <h4 class="modal-title">
                        <i class="glyphicon glyphicon-info-sign"></i> User Profile
                    </h4> 
               </div> 
               <div class="modal-body"> 
               
                    <div id="modal-loader" style="display: none; text-align: center;">
                        <img src="../images/ajax-loader.gif">
                    </div>
                    
                    <div id="dynamic-content">
                                             
                     <div class="row"> 
                          <div class="col-md-12"> 
                               
                               <table class="table table-striped table-bordered">
                                    <tr>
                                        <td>Shipping Address</td>
                                        <td id="shipping_address"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Meter Numbers</td>
                                        <td id="shipping_meters"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Reffered By</td>
                                        <td id="shipping_reference"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Province</td>
                                        <td id="shipping_province"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>City</td>
                                        <td id="shipping_city"></td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Postal Code</td>
                                        <td id="shipping_zip"></td>
                                    </tr>
                                    
                               </table>
                               
                          </div> 
                     </div> 
                     
                     </div>
                     
                </div> 
                <div class="modal-footer"> 
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div> 
                
         </div> 
      </div>
</div><!-- /.modal -->

<style>.panel-heading{ height: 51px; }</style>
<!--<script>
jQuery(document).ready(function(){
	initDatatable("#order_datatable");	
	})


</script>-->
@endsection
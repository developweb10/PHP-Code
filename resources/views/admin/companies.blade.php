<!-- Converting this page to tab content -->
<?php /* @extends('app')

@section('content') */

?>
<div id="createNewCompanyModal" class="modal fade in" role="dialog" aria-hidden="false" style="display: none;"><div class="modal-backdrop fade in" style="height: 1200px;"></div>
	<div class="modal-dialog">
		<div class="modal-content">
            <div class="modal-header">
        
                <button type="button" class="close" data-dismiss="modal">×</button>
        
                <h4 class="modal-title text-center">Add New Company</h4>
        
            </div>
        
            <div class="modal-body">
        
                <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/admin/createnewcompany') }}">
        
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                           
        
                    <div class="form-group">
        
                        <label class="col-md-4 control-label">Towing Company</label>
        
                        <div class="col-md-6">
        
                            <input type="text" class="form-control" name="company" value="" placeholder="Towing Company">
        
                        </div>
        
                    </div>
                    
                  
            
        
                    <div class="form-group">
        
                        <label class="col-md-4 control-label">Contact Number</label>
        
                        <div class="col-md-6 to-text">
        
                            <input type="text" class="form-control" name="contact_no" value="" placeholder="Contact Number">
        
                        </div>
        
                    </div>
        
        
                    <div class="form-group">
        
                        <label class="col-md-4 control-label">State/Province</label>
        
                        <div class="col-md-6">
        
                            <select name="state" id="state_list" class="form-control">
        
                                <option value="">Select State/Province</option>
        
                                
                                    @foreach( $vars['states'] as $state )
                                        <option value="{{ $state->state_code }}">{{ $state->state_code }}</option>
                                    @endforeach
        
                             </select>
        
                        </div>
        
                    </div>
        
        
        
                    <div class="form-group">
        
                        <label class="col-md-4 control-label">City</label>
        
                        <div class="col-md-6">
        
                            <select name="city" id="city_list" class="form-control">
        
                                <option value="">Select City</option>
        
                                
                            </select>
        
                        </div>
        
                    </div>
        
                    
        
                    <div class="form-group">
        
                        <div class="col-md-6 col-md-offset-4">
        
                            <button type="submit" class="btn btn-default">
        
                                Submit
        
                            </button>
        
                        </div>
        
                    </div>
        
                    
        
                    <div class="clearfix"></div>
        
                </form>
        
            </div>
        

    	</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default" style="position:relative;">
				<div class="panel-heading">
                	<div class="col-md-2">
	                	
                    </div>
                    <div class="pull-right"> 
                    	<!--<a href="https://my-meter.com/dev/public/admin/createuser?show_layout=false" class="btn btn-default btn-sm" data-toggle="modal" data-target="#createNewUserModal" data-static="false" data-keyboard="false" onclick="openModalLoader()">Create New User</a>-->
                        <a href="" class="btn btn-default btn-sm" data-toggle="modal" data-target="#createNewCompanyModal" data-static="false" data-keyboard="false" onclick="">Add New Company</a>
                    </div>
				</div>
				<div class="panel-body">
                	<?php /* ?>
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
					<?php */ ?>
					<table id="towing_datatable" width="100%" class="display compact companies_table" cellspacing="0" >
						<thead>
							<tr>
	                            <th class="col-md-2 text-center">City </th>
                                <th class="col-md-1 text-center">Province</th>
								<th class="col-md-2 text-center">Towing Company</th>
								<th class="col-md-2 text-center">Contact Number</th>
								<th class="col-md-2 text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach( $vars["companies"] as $order )
								<tr>
                                	<td class="col-md-2 text-center">{{ $order->city_name }}</td>
                                    <td class="col-md-2 text-center"><a data-toggle="modal" data-target="#view-modal" data-id="{{ $order->id }}" id="getOrder"> {{ $order->state_code }} </a></td>
									<td class="col-md-2 text-center">{{ $order->company }}</td>
									<td class="col-md-2 text-center">
                                    	<?php 
											if(isset($order->contact_no) && !empty($order->contact_no)){
												$number = preg_replace("/[^\d]/","",$order->contact_no); 
												$length = strlen($number);
												$number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
											}else{
												$number = "";	
											}
										?>
                                        {{$number}}
                                    </td>
                                    <td class="col-md-2 text-center">
                                        <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">Options
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{ URL::to('/admin/editcompany') }}?id={{ $order->id }}">Edit</a></li> <?php // ?>
                                                    <li>
                                                    	<a href="javascript:if( confirm('Are you sure you want to delete this user permanentaly from system?') ){ window.location='{{ URL::to('/admin/companydelete') }}?id={{ $order->id }}'};void(0);">Delete</a> <?php //  ?> 
                                                    </li>
                                                </ul>
                                        </div>
                                    </td>
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
<?php
//	@endsection
?>
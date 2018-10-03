<?php // echo "<pre>"; print_r($mymeters); echo "</pre>"; ?> 
<div class="container-fluid">
    <div class="row">
    	<div class="panel panel-default">
            <div class="panel-body"> 
            	
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="shipping_city">List of locations:</label>
                            
                            <!-- List of Group addresses/locations -->
                            <select id="shipped_location" name="shipping_city" class="form-control">
                             @foreach( $mylots as $lot )
                                <option value="{{ $lot->id }}">
                                    {{$lot->lot_address}} 
                                    {{{ isset($lot->lot_city) && !empty($lot->lot_city) ? ','.$lot->lot_city : '' }}}
                                </option>
                            @endforeach
                            </select>
                            <br />
                            
                            <div class="row">
                                <div class="col-lg-12">
                                
                                  <div class="col-lg-6">
                                    <div class="row">
                                        <button class="btn btn-default" data-toggle="modal" data-target="#order_parking_form" style="width: 100%;">+ New Meter</button>
                                    </div>
                                  </div>
                                  
                                  <div class="col-lg-6">
                                    <div class="row">
                                        <button class="btn btn-default addlocation" data-toggle="modal" data-target="#newLotModal" style="width: 100%;">Add Location</button>
                                    </div>
                                
                                </div>
                                
                                </div>
                            </div>
                            
                            @if(isset($mymeters) && count($mymeters)>0)
                                <div style="max-height: 361px;overflow-y: auto;border: 1px solid #eee;">
                                    <table class="table datatable">
                                        <tbody>
                                            
                                            @foreach($mymeters as $meter)
                                            <tr>
                                                <td width="50%" align="center">{{$meter->meter_id}}</td>
                                                <td width="50%" align="center" style="background-color: rgba(241, 241, 241, 0.55);"><a>Change Location</a></td>
                                            </tr>
                                            @endforeach
                                            
                                        
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-lg-8">
                            <div style="width:100%; height: 500px; float:left;">
                        	{!! Mapper::render() !!}
                            </div>
                        </div>
                
                    </div>
                </div> 
                
            </div>        
        </div>
    </div>     
</div>  


<!-- New Lot Modal / Add Location Modal -->
<div id="newLotModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Create New Group</h4>
			</div>
			<form class="form-horizontal text-center" role="form" method="POST" action="{{ url('/home/newlot') }}">
				<div class="modal-body">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<label class="col-md-4 control-label">Group Name</label>
						<div class="col-md-6">
							<input type="text" class="form-control" required name="lot_name" value="" placeholder="Group Name" >
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label">Location</label>
						<div class="col-md-6">
							<input type="text" class="form-control" required name="lot_address" value="" placeholder="Street Address" >
						</div>
					</div>
					
					
					
					<div class="form-group">
						<label class="col-md-4 control-label">Price ( Per Hour ) </label>
						<div class="col-md-6">
							<div class="col-md-9 row">
							<select class="form-control" name="price" required>
								<option value="">Price</option>
								
								<option value="2.00">$2.00</option>
								<option value="2.50">$2.50</option>
								<option value="3.00">$3.00</option>
								<option value="3.50">$3.50</option>
								<option value="4.00">$4.00</option>
								<option value="4.50">$4.50</option>
								<option value="5.00">$5.00</option>
								<option value="5.50">$5.50</option>
								<option value="6.00">$6.00</option>
                            </select>
							</div>
						</div>
					</div>
					
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">
						Create
					</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

              
<!-- New Meter Modal -->						
<div id="order_parking_form" class="modal fade landowner_first_UI" role="dialog" style="">

	<div class="modal-dialog">

		<div class="modal-content">
    
			@include('includes.landowner_step_ui')
        </div> 
          
	</div>
    
</div>

<!-- Lots management in admin panel -->
<!-- New Lot Modal -->
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
						<label class="col-md-4 control-label">Commission</label>
						<div class="col-md-6">
							<input type="text" class="form-control" required name="commission" value="" placeholder=""  />
						</div>
					</div>
                    
                    <div class="form-group">
						<label class="col-md-4 control-label">List of Landowners</label>
						<div class="col-md-6">
                        	<select name="users" class="form-control" id="users_checkboxes" multiple="multiple">
                                @foreach( $vars["landowner_users"] as $user )
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
							<!--<input type="text" class="form-control" required name="lot_address" value="" placeholder="" >-->
						</div>
					</div>
					
					<?php /* ?>
					<div class="form-group">
						<label class="col-md-4 control-label">City</label>
						<div class="col-md-6">
							<input type="text" class="form-control" required name="lot_city" value="{{ $user['city'] }}" placeholder="City" >
						</div>
					</div>
					
					
					<div class="form-group">
						<label class="col-md-4 control-label">Price ( Per Hour ) </label>
						<div class="col-md-6">
							<div class="col-md-9 row">
							<select class="form-control" name="price" required>
								<option value="">Price</option>
								<!--<option value="1">$1</option>
								<option value="1.50">$1.50</option>-->
								<option value="2.00">$2.00</option>
								<option value="2.50">$2.50</option>
								<option value="3.00">$3.00</option>
								<option value="3.50">$3.50</option>
								<option value="4.00">$4.00</option>
								<option value="4.50">$4.50</option>
								<option value="5.00">$5.00</option>
								<option value="5.50">$5.50</option>
								<option value="6.00">$6.00</option>
                                <option value="6.50">$6.50</option>
								<option value="7.00">$7.00</option>
								<option value="7.50">$7.50</option>
								<option value="8.00">$8.00</option>
								<option value="8.50">$8.50</option>
								<option value="9.00">$9.00</option>
								<option value="9.50">$9.50</option>
                            </select>
							</div>
						</div>
					</div>
					<?php */ ?>
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

<?php /********************************************************* New Meter Modal *****************************************************/ ?>
<div id="newMeterModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Add New Meter</h4>
			</div>
			<form class="form-horizontal text-center newMeter-form" role="form" method="POST" action="{{ url('admin/client') }}" id="admin_meter_section">
				<div class="modal-body">
					
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<!--<input type="hidden" name="lot_id"  value="{{-- $vars['lot_id'] --}}">-->
					<input type="hidden" name="client_id" value="{{ $client['id'] }}" />
                    
					<div class="form-group">
						<label class="col-md-4 control-label">No. of Meters </label>
						<div class="col-md-6">
							<input type="text" class="form-control" id="meter_count" name="meter_count" value="1" placeholder="No. of Meters (Default:1)" min="1" max="999"> <!-- pattern="[0-9]{3}" -->
						</div>
					</div>
                    
                    <div class="form-group">
						<label class="col-md-4 control-label">Hourly Rate </label>
						<div class="col-md-6">
							<select class="form-control" name="price" required="">
                            
                                <option value="">Hourly Rate</option>

                                <option value="2.00" selected="selected">$2.00</option> 

                                <option value="2.50">$2.50</option>

                                <option value="3.00">$3.00</option>

                                <option value="3.50">$3.50</option>

                                <option value="4.00">$4.00</option>

                                <option value="4.50">$4.50</option>

                                <option value="5.00">$5.00</option>

                                <option value="5.50">$5.50</option>

                                <option value="6.00">$6.00</option>

                                <option value="6.50">$6.50</option>

                                <option value="7.00">$7.00</option>

                                <option value="7.50">$7.50</option>

                                <option value="8.00">$8.00</option>

                                <option value="8.50">$8.50</option>

                                <option value="9.00">$9.00</option>

                                <option value="9.50">$9.50</option>

                                <!--<option value="10.00">$10.00</option>-->

                            </select>
						</div>
					</div>
					
                    <div class="form-group">

                        <label class="col-md-4 control-label">Country</label>

                        <div class="col-md-6">

                            <select name="country" id="country_list" class="form-control">

                                @foreach( $countries as $country )

                                    <option value="{{ $country->id }}" @if( $client['country'] == $country->id ) selected="selected" @endif >{{ $country->nicename }}</option>

                                @endforeach

                            </select>

                        </div>

                    </div>



                    <div class="form-group">

                        <label class="col-md-4 control-label">State/Province</label>

                        <div class="col-md-6">

                            <select name="state" id="state_list" class="form-control">

                                <option value="">Select State/Province</option>

                                @if( count($states) )

                                    @foreach( $states as $state )

                                        <option value="{{ $state->state_code }}" @if( $client['state'] == $state->state_code ) selected="selected" @endif >{{ $state->state_code }}</option>

                                    @endforeach

                                @endif

                            </select>

                        </div>

                    </div>



                    <div class="form-group">

                        <label class="col-md-4 control-label">City</label>

                        <div class="col-md-6">

                            <select name="city" id="city_list" class="form-control">

                                <option value="">Select City</option>

                                @if( count($cities) )

                                    @foreach( $cities as $city )

                                        <option value="{{ $city->id }}" @if( $client['city'] == $city->id ) selected="selected" @endif >{{ $city->city_name }}</option>

                                    @endforeach

                                @endif

                            </select>

                        </div>

                    </div>

                     <div class="form-group">
                     	<label class="col-md-4 control-label">Towing Company</label>
                        <div class="input-field col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <span id="loading_img_landownwer" style="display:none;">
                                <img src="{{ asset('images/load.gif') }}" width="30px" height="30px">
                            </span>
                            <div class="towing_companies">
                                    
                                <select name='towing_companies' class="form-control @if( !count($towing_detail) ) hide_it @endif">
                                	<option>Select Towing Service</option>
                                    @foreach( $towing_detail as $company )

                                        <option value="{{ $company->id }}">{{ $company->company }}</option>

                                    @endforeach
                                </select>
                             
                                <div class="company_contact_number @if( count($towing_detail) ) hide_it @endif">
                                <input type="text" name='towing_contact' class="form-control disableAutoComplete" placeholder="Towing Service Phone Number"  autocomplete="off" value="" />           
                                </div>
                                    
                                
                                <!--<select name='towing_companies' class="form-control" required> </select>
                                <input type="text" name='towing_companies' class="form-control" placeholder="Towing Company" required />-->
                            </div>
                        </div>
                       
                        <!--<div class="company_contact_number col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Contact Number</label>
                            
                        </div>-->
                    
                    </div>           
                                
                    <div class="form-group">
						<div class="col-md-4 to-text text-right">Select Group</div>
						<div class="col-md-6 text-left to-text lot_name_html">
							{{-- $vars["lot_name"] --}}
                            <input type="radio" name="grouping_method" value="0" /> Existing Group
                            &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="grouping_method" value="1" /> Create New Group
						</div>
					</div>
                    
                    <div class="form-group hide_it" id="existing_group">
                        <div class="col-md-4 to-text text-right"></div>
                        <div class="col-md-6 text-left to-text lot_name_html">
							<select class="form-control" name="group_id"> 
                            	<option>Groups</option>
                                @if(count($Groups))
                                	@foreach($Groups as $group)
                                    	<option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                    @endforeach
                                @endif
                            </select>                    
                        </div>
                    </div>
                    
                    <div id="new_group" class="hide_it">
                    	
                        <div class="form-group">
                            <div class="col-md-4 to-text text-right"></div>
                            <div class="col-md-6 text-left to-text lot_name_html">
                                <input type="text" class="form-control" name="group_name" value="" placeholder="Group Name" >  
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-4 to-text text-right"></div>
                            <div class="col-md-6 text-left to-text lot_name_html">
                                <input type="text" class="form-control" name="commission" value="" placeholder="Commission"  />
                            </div>
                        </div>
                        
                    </div>
                    
					<br />
					
					<div class="clearfix"></div>
				
				</div>
				<div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="add_meter">
						Save
					</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php /***************************************************** Meter Added Successfully ***********************************************/ ?>

<div id="meter_Added" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
        	<div class="modal-body"> 
	        	<h3> Thanks for adding Meter. Downloading process is in progess! </h3>
            </div>
            <div class="modal-footer">
	            <button id="meter_Added_close" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php /***************************************************** Share Live Feed ********************************************************/ ?>

<div class="modal fade" id="share_incpt" role="dialog">
    <div class="modal-dialog modal-md" style="max-width:400px; margin-left: auto;  margin-right: auto;"> 
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Share Live Feed</h4>
        </div>
        <div class="modal-body">
        	<form name="share" class="form-horizontal" role="form" method="POST" action="{{ url('/admin/sharefeed') }}">
                <input type="hidden" name="inspections_url" value="{{ $vars['inspectionsURL'] }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <div class="col-xs-9 col-md-9">
						<input type="email" class="form-control" name="email" value="" placeholder="E-Mail Address">
					</div>
                    <div class="col-xs-3 col-md-3">
						<button type="submit" class="btn btn-primary pull-left">Send</button>
					</div>
                </div>
                <input type="hidden" name="client_id" value="{{ $client['id'] }}" />
            </form>
        </div>
      </div>
    </div>
</div>
  
<?php /***************************************************** Edit Group Modal ********************************************************/ ?>

<div id="changeMeterGroup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Change Group</h4>
			</div>
			<form class="form-horizontal text-center updateMeterGroup-form" role="form" method="POST" action="{{ url('/admin/updatemetergroup') }}">
				<div class="modal-error-body"></div>
				<div class="modal-body">
					Selected Meter(s): <div class="meter-name text-center"></div>

					<br>

					<input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="meter_id" value="">
					<input type="hidden" name="client_id" value="{{ $client['id'] }}">
					<div class="form-group">
						<label class="col-md-4 control-label">Select Group</label>
						<div class="col-md-6">
							<select class="form-control" name="group_id" placeholder="Group">
                            	@if(count($Groups))
                                	@foreach($Groups as $group)
                                    	<option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                    @endforeach
                                @endif
								
							</select>
						</div>
					</div>
					
					<div class="clearfix"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="changeGroupButton" >Submit</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php /***************************************************** Edit Group Modal ********************************************************/ ?>
<!-- Delete Lot Modal -->
<div id="deleteMeterGroup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Delete Meter(s)</h4>
			</div>
			<div class="modal-error-body text-center"></div>
			<div class="modal-body text-center">
				Selected Meter(s) <div class="meter-name text-center"></div>
				<br>
				Are you sure you want to delete?
			</div>
			<div class="modal-footer">
	            <input type="hidden" name="client_id" value="{{ $client['id'] }}"> 
				<button type="button" class="btn btn-danger" id="deleteMeter" >Delete</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<style>
	.hide_it{
		display:none;
	}
</style>
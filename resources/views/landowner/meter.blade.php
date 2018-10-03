<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					@if ( $vars['tab'] === '' && count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					@if ( $vars['tab'] === '' && Session::has('success'))
						<div class="alert alert-success">
							<strong>Success!</strong> {{ Session::get('success') }}
						</div>
					@endif

					<div>
						<?php /*?><div class="text-right">
							@if( $vars['inspectionsURL'] != '' )
								<strong>Inspections URL:</strong> <a href="{{ $vars['inspectionsURL'] }}" target="_blank">{{ $vars['inspectionsURL'] }}</a>
							@endif
						</div><?php */?>
						<br /><br />
						<div class="">
							<div class="<?php /*?>row lots-list<?php */?>">
								
								@if( $mylots->count() )
									<div class="col-md-3">
										<select name="lot_id" class="form-control btn-default" onchange="selectLot(this);" >
											@foreach( $mylots as $lot )
												<option value="{{ $lot->id }}" data-price="{{ $lot->price }}" >{{ $lot->lot_name }}</option>
												
												<?php /*?><div class="col-md-3 report-bordered groupbox lot_{{ $lot->id }} @if( isset($lot_count) && $lot_count === count($mylots)-1 ) last @else <?php $lot_count=isset($lot_count)?$lot_count+1:1; ?> @endif lot-box @if( $vars['lot_id'] == $lot->id ) selected btn-success @else btn-danger @endif" onclick="selectLot(this,{{ $lot->id }});" data-price="{{ $lot->price }}" >
													<span class="lot_name-html">{{ $lot->lot_name }}</span>
													
													<div class="dropdown pull-right groupbutton">
														<button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Action <span class="caret"></span></button>
														<ul class="dropdown-menu">
															<li><a href="#" data-toggle="modal" data-target="#editLotModal" >Edit</a></li>
															<li><a href="#" data-toggle="modal" data-target="#deleteLotModal" >Delete</a></li>
														</ul>
													</div>
												</div><?php */?>
											@endforeach
										</select>
									</div>
									<div class="dropdown pull-left groupbutton">
										<button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Action <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a href="#" data-toggle="modal" data-target="#editLotModal" >Edit</a></li>
											<li><a href="#" data-toggle="modal" data-target="#deleteLotModal" >Delete</a></li>
										</ul>
									</div>
									<div class="clearfix"></div>
									<hr />
								@else
									<div class="col-md-2"></div>
									<div class="alert alert-danger col-md-8">No Groups added! Start by clicking on new Group button.</div>
									<div class="col-md-2"></div>
									<div class="clearfix"></div>
								@endif
								<!--<div class="col-md-3 report-bordered btn-info new-groupbox" data-toggle="modal" data-target="#newLotModal" >
									<span class="lot_name-html">New Group +</span>
								</div>-->
								<div class="clearfix"></div>
							</div>
						</div>
						
						<div class="clearfix"></div>
					</div>
					
					<div id="meters_html_section">
						@if( $mylots->count() )
							@include("landowner.meter-sub")
						@endif
					</div>
					
					
					<br />
					
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>

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
					
					<!--<div class="form-group">
						<label class="col-md-4 control-label">City</label>
						<div class="col-md-6">
							<input type="text" class="form-control" required name="lot_city" value="{{ $user['city'] }}" placeholder="City" >
						</div>
					</div>-->
					
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

<!-- Edit Lot Modal -->
<div id="editLotModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Edit Group: {{ $vars['lot_name'] }}</h4>
			</div>
			<form class="form-horizontal text-center" role="form" method="POST" action="{{ url('/home/updateLot') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">
						Update
					</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Delete Lot Modal -->
<div id="deleteLotModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Delete Group: <span class="name">{{ $vars['lot_name'] }}</span></h4>
			</div>
			<div class="modal-body">
				All the data/meters related to this group will be deleted.<br />
				Are you sure you want to delete this group?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" id="delteGroup">Delete</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Delete Lot Modal -->
<div id="deleteMeterGroup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Delete Meter: </h4>
			</div>
			<div class="modal-body">
				Are you sure you want to delete this meter?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" id="deleteMeter" >Delete</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- New Lot Modal -->
<div id="newMeterModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Add New Meter</h4>
			</div>
			<form class="form-horizontal text-center newMeter-form" role="form" method="POST" action="{{ url('/home/newmeter') }}">
				<div class="modal-body">
					<strong>*NOTE:</strong> There is a one-time <strong>${{ number_format(config("meter_base_price"),2) }}</strong> fee for each new meter you create.
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="lot_id"  value="{{ $vars['lot_id'] }}">
					
					<div class="form-group">
						<div class="col-md-4 to-text text-right">Group</div>
						<div class="col-md-6 text-left to-text lot_name_html">
							{{ $vars["lot_name"] }}
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-4 control-label">No. of Meters </label>
						<div class="col-md-3">
							<input type="text" class="form-control" id="meter_count" name="meter_count" value="1" placeholder="No. of Meters (Default:1)" min="1" max="999" pattern="[0-9]{3}" >
						</div>
						<div class="col-md-3 text-center to-text ">
							* ${{ number_format(config("meter_base_price"),2) }}
						</div>
					</div>

					<br />
					<div class="form-group tota_amount" id="total_amount_paid">
						 Total Amount : <span class="price">${{ number_format(config("meter_base_price"),2) }}</span>
					</div>
					
					<div id="paypal_form" @if (count($errors) == 0) style="display:none;" @endif>
								<div class="form-group">
									<h3 class="text-center">Enter Credit Card Details</h3>
								</div>

								<div class="form-group" >
									<label class="col-md-4 control-label">Card Number</label>
									<div class="col-md-6">
										<input type="text" class="form-control" name="cc_number" value="{{ old('cc_number') }}" placeholder="Account Number" required="required" >
									</div>
								</div>
								
								<div class="form-group" >
									<label class="col-md-4 control-label">Expiry Date</label>
									<div class="col-md-6">
										<div class="col-md-6 row">
											<select name="expiry_month" class="form-control" >
												@for ($i=1; $i<=12;$i++)
												<option @if (old('expiry_month') == $i) selected="selected" @endif>{{ $i}}</option>
												@endfor
											</select>
										</div>
										<div class="col-md-2 to-text text-centered">/</div>
										<div class="col-md-6 row padd_right0">
											<select name="expiry_year" class="form-control" >
												@for ($i=2016; $i<=2030;$i++)
												<option @if (old('expiry_year') == $i) selected="selected" @endif>{{ $i}}</option>
												@endfor
											</select>
											
										</div>
									</div>
								</div>
							</div>
					
					
					
					<div class="clearfix"></div>
				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="proceed_to_checkout">
						Proceed to Checkout
					</button>

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Lot Modal -->
<div id="changeMeterGroup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Change Group <span class="meter-name">( Meter :  )</span></h4>
			</div>
			<form class="form-horizontal text-center updateMeterGroup-form" role="form" method="POST" action="{{ url('/home/updateMeterGroup') }}">
				<div class="modal-body">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="meter_id" value="">
					<div class="form-group">
						<label class="col-md-4 control-label">Select Group</label>
						<div class="col-md-6">
							<select class="form-control" name="lot_id" placeholder="Group">
								@foreach( $mylots as $lot )
									<option value="{{ $lot->id }}" @if( $lot->id == $vars['lot_id']) selected="selected" @endif  >{{ $lot->lot_name }}</option>
								@endforeach
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

<style>
	.displayclass{
		display:block !important;
	}
</style>
<script>var current_lot_id = {{ $vars['lot_id'] }};  var meter_base_price = {{ config("meter_base_price") }};</script>
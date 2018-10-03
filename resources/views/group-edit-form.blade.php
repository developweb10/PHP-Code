<input type="hidden" name="lot_id" value="{{ $vars['lot_id'] }}" >
<div class="form-group">
	<label class="col-md-4 control-label">Group Name</label>
	<div class="col-md-6">
		<input type="text" class="form-control" name="lot_name" value="{{ $vars['lot_name'] }}" placeholder="Group Name" >
	</div>
</div>

<div class="form-group">
	<label class="col-md-4 control-label">Street Address</label>
	<div class="col-md-6">
		<input type="text" class="form-control" name="lot_address" value="{{ $vars['lot_address'] }}" placeholder="Street Address" >
	</div>
</div>

<?php /*?><div class="form-group">
	<label class="col-md-4 control-label">City</label>
	<div class="col-md-6">
		<input type="text" class="form-control" name="lot_city" value="{{ $vars['lot_city'] }}" placeholder="City" >
	</div>
</div><?php */?>

<div class="form-group">
	<label class="col-md-4 control-label">Rate ( Per Hour )</label>
	<div class="col-md-6">
		<div class="col-md-9 row">
			<select class="form-control" name="price" required>
				<option value="">Price</option>
				<?php /*?><option value="1" @if( $vars['lot_price'] == "1" ) selected="selected" @endif >$1</option>
				<option value="1.5" @if( $vars['lot_price'] == "1.5" ) selected="selected" @endif >$1.50</option><?php */?>
				<option value="2" @if( $vars['lot_price'] == "2" ) selected="selected" @endif >$2.00</option>
				<option value="2.5" @if( $vars['lot_price'] == "2.5" ) selected="selected" @endif >$2.50</option>
				<option value="3" @if( $vars['lot_price'] == "3" ) selected="selected" @endif >$3.00</option>
				<option value="3.5" @if( $vars['lot_price'] == "3.5" ) selected="selected" @endif >$3.50</option>
				<option value="4" @if( $vars['lot_price'] == "4" ) selected="selected" @endif >$4.00</option>
				<option value="4.5" @if( $vars['lot_price'] == "4.5" ) selected="selected" @endif >$4.50</option>
				<option value="5" @if( $vars['lot_price'] == "5" ) selected="selected" @endif >$5.00</option>
				<option value="5.5" @if( $vars['lot_price'] == "5.5" ) selected="selected" @endif >$5.50</option>
				<option value="6" @if( $vars['lot_price'] == "6" ) selected="selected" @endif >$6.00</option>
                <option value="6.50" @if( $vars['lot_price'] == "6.5" ) selected="selected" @endif  >$6.50</option>
                <option value="7.00" @if( $vars['lot_price'] == "7" ) selected="selected" @endif >$7.00</option>
                <option value="7.50" @if( $vars['lot_price'] == "7.5" ) selected="selected" @endif >$7.50</option>
                <option value="8.00" @if( $vars['lot_price'] == "8" ) selected="selected" @endif >$8.00</option>
                <option value="8.50" @if( $vars['lot_price'] == "8.5" ) selected="selected" @endif >$8.50</option>
                <option value="9.00" @if( $vars['lot_price'] == "9" ) selected="selected" @endif >$9.00</option>
                <option value="9.50" @if( $vars['lot_price'] == "9.5" ) selected="selected" @endif >$9.50</option>
                <option value="10.00" @if( $vars['lot_price'] == "10" ) selected="selected" @endif >$10.00</option>
			</select>
			<!--<input type="text" class="form-control" name="price" value="{{ $vars['lot_price'] }}" placeholder="Price" ></div><div class="col-md-3 to-text"> $ / hr-->
		</div>
	</div>
</div>

<?php /*?><div class="form-group">
	<label class="col-md-4 control-label">Start Time </label>
	<div class="col-md-6">
		<div class="input-group">
			<input type="text" name="start_time" class="form-control input-small timepicker1" placeholder="Start Time" value="{{ $vars['lot_start_time'] }}">
			<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="col-md-4 control-label">End Time </label>
	<div class="col-md-6">
		<div class="input-group">
			<input type="text" name="end_time" class="form-control input-small timepicker1" placeholder="End Time" value="{{ $vars['lot_end_time'] }}" >
			<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
		</div>
	</div>
</div><?php */?>
	
<div class="clearfix"></div>

<div class="row">
	<div class="col-md-2 text-center">
	</div>
	<div class="col-md-4 text-center">
		<h1>{{ $vars["lot_address"] }}</h1>
	</div>
	<div class="col-md-4 text-center">
		<h1>$ {{ $vars["lot_price"] }} / hr</h1>
	</div>
	<div class="col-md-2 text-center">
		<button class="btn btn-default new-groupbox" data-toggle="modal" data-target="#newLotModal" >New Group +</button>
	</div>
</div>
<br />
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10 html_section">
		<div class="row meters-list" id="lot_meters">
			<div class="col-md-12 heading-row">
				<div class="col-md-3 to-text text-center">
					<div class="dropdown">
						<button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Action <span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="#" data-toggle="modal" data-target="#changeMeterGroup" onclick="changeGroup();">Change Group</a></li>
							<li><a href="#" data-toggle="modal" data-target="#deleteMeterGroup" onclick="deleteMeter();">Delete</a></li>
						</ul>
						<button class="btn-addmeter btn btn-success btn-sm" data-toggle="modal" data-target="#newMeterModal" onclick="changeGroup();">+ New Meter</button>
					</div>
				</div>
				<div class="col-md-3 to-text text-center"><input type="search" name="meter_search" id="meter_search" class="form-control" value="" placeholder="Meter #" /></div>
				<div class="col-md-3 to-text text-center">Recent</div>
				<div class="col-md-3 to-text text-center">Expiry</div>
				<div class="clearfix"></div>
			</div>
			<div>
				<?php $i=0; ?>
				@foreach( $mymeters as $meter )
					<?php $i++ ?>
					<div class="col-md-12 data-row @if($i % 2 == 0) alternate @endif">
						<div class="col-md-3 text-center" >
							<input type="checkbox" name="selectedMeters" value="{{ $meter->id }}"  />
						</div>
						<div class="col-md-3 meter_id text-center" >{{ $meter->meter_id }}</div>
						<?php 
							$minutes = $meter->hours * 60;
							$h = floor($minutes / 60);
							$m = ($minutes % 60);
							$formated_hours =  sprintf("%02d:%02d", $h, $m);

						?>
						<div class="col-md-3 text-center" >{{ $meter->hour_price }} @ {{ $formated_hours }} Hrs</div>
						<?php 
							$expiry = app('App\Http\Controllers\UtilsController')->date_difference(date('Y-m-d H:i:s'),$meter->expiry);	
							$expiry_text = ( $expiry["hours"] == 0 && $expiry["mins"] == 0 ) ? "Expired" : ((($expiry["hours"] > 0)?$expiry["hours"]." Hrs ":"" ).$expiry["mins"]." Min");
						?>
						<div class="col-md-3 text-center" >{{ $expiry_text }}</div>
						<div class="clearfix"></div>
					</div>
				@endforeach
				<div class="col-md-12 data-row no-records-row" @if( count($mymeters) ) style="display:none;"  @endif >No meters found.</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<div class="col-md-1"></div>
</div>
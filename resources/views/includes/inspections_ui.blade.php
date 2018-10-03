<div class="container-fluid">
	<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
                    	<div class="col-xs-12 col-sm-1 col-md-2 col-lg-2"></div>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3" style="position: relative;background-image:url('/images/iphone_img.png');z-index: 99;min-height: 480px;background-size: 100%;background-repeat: no-repeat;">
                        <table id="datatable_alone" class="display inspectons_table" cellspacing="0" width="100%">
						<thead>
							<tr class="heading-row">
								<th class="text-center" width="50%">
									<input type="search" name="meter_search" id="meter_search_inspections" value="" placeholder="Meter #" class="form-control" style="width:100px; margin: 0 auto; text-align:center;" />
								</th>
								<?php /*?><th class="text-center">Group</th>
								<th class="text-center">Hours</th>
								<th class="text-center">Price</th><?php */?>
								<th class="text-center">Expiry</th>
							</tr>
						</thead>
						<tbody>
							@if( count($mymeters) )
								@foreach( $mymeters as $meter )
									<tr>
										<td class="text-center">{{ $meter['meter_id'] }}</td>
										<?php /*?><td class="text-center">{{ $meter['lot_name'] }}</td>
										<?php 
											$minutes = $meter['hours'] * 60;
											$h = floor($minutes / 60);
											$m = ($minutes % 60);
											$formated_hours =  sprintf("%02d:%02d", $h, $m);
										?>
										<td class="text-center">{{ $formated_hours }}</td>
										<td class="text-center">${{ $meter['hour_price'] }} /hr</td><?php */?>
										<?php 
											$expiry = app('App\Http\Controllers\UtilsController')->date_difference(date('Y-m-d H:i:s'),$meter['expiry']);
											$expiry_text = ( $expiry["hours"] == 0 && $expiry["mins"] == 0 ) ? "Expired" : ((($expiry["hours"] > 0)?$expiry["hours"]." Hrs ":"" ).$expiry["mins"]." Min");
										?>
										<td class="text-center">{{ $expiry_text }}</td>
									</tr>
								@endforeach
							@endif
						</tbody>
						<tfoot style="display:none;">
							<tr>
								<td colspan="2" class="text-center">No matching results!</td>
							</tr>
						</tfoot>
					</table>
                        </div>
                    	<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-5">
                        <br/><br/>
                        <p>Your Inspections URL is a live feed of meter bookings showing the remaining time for each of your parking meters.The URL is mobile responsive and can be shared with others as it doesn't require login access.For quick and easy access, we recommend adding this webpage to the home screen of your mobile device.</p>
               			<p></p><p></p><br/>
                        <p>
                             @if( $vars['inspectionsURL'] != '' )
                             <a href="{{ $vars['inspectionsURL'] }}" target="_blank">{{ $vars['inspectionsURL'] }}</a>
                             @endif
                        </p>
                        <br/><br/>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
                        </div>
                    	<div class="col-xs-12 col-sm-1 col-md-2 col-lg-2"></div>
                    </div>
					<div class="clearfix"></div>
				</div>
			</div>
		<div class="clearfix"></div>
	</div>
</div>
<style>
.inspectons_table{
    position: absolute;
    width: 86% !important;
    z-index: 9;
    height: 100%;
    top: 102px;
    left: 17px;	
}
#datatable_alone th.text-center {
    padding: 2px !important;
}
</style>
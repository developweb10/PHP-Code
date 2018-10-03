@extends('app')

@section('content')

	<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
            		@if( Session::has('success'))
						<div class="alert alert-success">
							<strong>Success!</strong> {{ Session::get('success') }}
						</div>
					@endif
				<?php /*?><div class="panel-heading displayclass" style="padding-bottom: 20px;height:56px;" >
					<div class="col-md-8 col-lg-8 col-sm-8 col-xs-12 to-text">Statistics ( Showing active meter details for {{ $user->name }} )</div>
					<div class="col-md-4 col-lg-4 col-sm-4 col-xs-12 text-right">
						<select name="group_id" class="form-control" onchange="window.location='?group_id='+this.value;">
							<option value="">Select Group</option>
							@foreach( $groups as $group )
								<option value="{{ $group->id }}" @if( $vars['group_id'] == $group->id ) selected="selected" @endif >{{ $group->lot_name }}</option>
				 			@endforeach
						</select>
					</div><?php */?>
					<div class="clearfix"></div>
					<?php /*?>@if( Auth::check() )
						<a href="{{ URL::to('notify') }}" class="btn btn-success pull-right btn-sm">Send Notification</a>
					@endif
				</div><?php */?>
				<div class="panel-body">
                
                
                	<?php /*?><div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">Check Meter Status</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-12 text-center">
                                        <input type="text" class="form-control" id="check_meter_id" placeholder="Stall #" style="width:200px;display:inline; " >
                                        <button type="button" class="btn btn-primary" id="check_meter_button">Search</button>
                                    </div>
                                </div>
                                <Br /><Br /><Br />
                                <div id="check-meter-result">
                                    
                                </div>
                            </div>
                        </div>
                    </div><?php */?>
					<table id="datatable_alone" class="display" cellspacing="0" width="100%">
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
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<style>
		.banner-img{
			   display:none;
					  }
		.navbar{
			display:none;
				  }
		footer{
			display:none;
				}
	.btn-sm{
		display:none;
		}			
.dataTables_length{
	display:none;
	}
	.dataTables_info{
		display:none;
		}
	.displayclass{
		display:block !important;
		}
		
	@media screen and (max-width:768px)
	{
		.panel-default > .panel-heading{
		    height: 130px !important;
			text-align:center;
		}
	}
</style>


@endsection
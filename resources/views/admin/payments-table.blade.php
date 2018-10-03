<h2>{{ $vars["pay"] === 'lo' ? 'Payable Landowners' : ( ( $vars["pay"] === 'sa' ) ? 'Payable Sales Associates' : 'All Payable Users' ) }}
	<?php /*?><a href="{{ URL::to('/admin/payouts') }}?pay={{ $vars["pay"] }}" class="btn btn-success btn-lg pull-right">Pay Now</a>
	<div class="clearfix"></div><?php */ //echo "<pre>"; print_r($vars); echo "</pre>"; ?>
</h2>

<table id="datatable" class="display" cellspacing="0" width="100%" @if( $vars['export'] !== '' ) border="1" @endif >
	<thead>
		<tr>
			<th class="text-center">Name</th>
			<th class="text-center">Account</th>
			<th class="text-center">Email</th>
			<th class="text-center">$ Earned</th>
			<th class="text-center">$ Paid</th>
			<th class="text-center">$ Payable</th>
		</tr>
	</thead>
	<tbody>
		@if( count($vars["payout_details"]) )
			@foreach( $vars["payout_details"] as $user )
				<tr>
					<td align="center"><a href="Javascript:get_payouts({{$user['id']}});">{{ $user['name'] }}</a></td>
					<td align="center">{{ ( $user['role_id'] == 2 ) ? 'Sales Associate' : 'Landowner' }}</td> 
					<td align="center">{{ $user['email'] }}</td>
					<td align="center">{{ $user['total_paid'] }}</td>
					<td align="center">{{ $user['total_received'] }}</td>
					<td align="center">{{ $user['balance'] }}</td>
				</tr>
			@endforeach
		@endif
	</tbody>
    <tfoot>
    	<tr>
        	<td></td>
            <td></td>
            <td></td>
            <td align="center"><?php if(isset( $vars["sum"][0] )) { echo "$".$vars["sum"][0]; } ?></td>
            <td align="center"><?php if(isset( $vars["sum"][0] )) { echo "$".$vars["sum"][1]; } ?></td>
            <td align="center"><?php if(isset( $vars["sum"][0] )) { echo "$".$vars["sum"][2]; } ?></td>
        </tr>
    </tfoot>
</table>

<div id="payout_history" class="modal fade">
	<div class="modal-dialog">
    	<div class="modal-content">
        	<div class="modal-header">
             <button data-dismiss="modal" class="close">x</button>
            	<h4 class="modal-title">Payout History</h4>
            </div>
            <div class="modal-body">
            	<div class="load_img" style="text-align:center;"><img src="{{url('images/loading%20(3).gif')}}" height="50px" width="50px" /></div>
                <div class="payout_table">
                
                </div>
            </div>
        </div>
    </div>

</div>

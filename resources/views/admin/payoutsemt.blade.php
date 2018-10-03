<?php
	if( isset( $payouts["confirm"] ) && $payouts["confirm"] == 1 ){ echo "<div class='alert alert-danger'>You have already exported the list of payables. Please update status for these users if you have paid successfuly. </div>";  }
?>
<table id="datatable" class="display" cellspacing="0" @if( isset( $payouts["exported"] ) && $payouts["exported"] == 1 ) width="100%" @endif >	<thead>
    	<tr>
    		<th>Dr/Cr</th>
            <th>Amount</th>
            <th>Currency</th>
            <th>Type</th>
            <th>PayeeEmail</th>
            <th>Priority</th>
            <!--<th>SecurityQuestion</th>
            <th>SecurityAnswer</th>-->
            <th>PayeeName</th>
            <th>Security Question</th>
            <th>Security Answer</th>
            @if( isset( $payouts["exported"] ) && $payouts["exported"] == 1 )
	            <th>Status</th>
            @endif
    	</tr>
    </thead>
    
    <tbody>
    	
        	@foreach($payouts as $payout)
            	@if( isset($payout['email']) )
                    <tr>
                        <td> C </td>
                        <td> @if($payout['balance']) {{$payout['balance']}} @endif </td>
                        <td>CAD</td>
                        <td>EMT</td>
                        <td> @if($payout['email'])  {{$payout['email']}} @endif </td>
                        <td>0</td>
                        <td> @if($payout['name'])  {{$payout['name']}} @endif </td>
                        <td>What is your Payout Code?</td>
                        <td>@if(isset($payout['security_answer']))  {{$payout['security_answer']}} @endif</td>
                        @if( isset( $payouts["exported"] ) && $payouts["exported"] == 1 )
                            <td> 
                            	<select id="status_{{$payout['id']}}" onchange="updatepayoutstatus({{$payout['id']}})"> 
                                	<option> In progress </option> 
                                    <option> Paid </option> 
                                </select> 
                                <img src="../images/load.gif" width="20px" height="20px" id="payout_{{$payout['id']}}" style="display: none;">
                            </td>
                            
                        @endif
                        
                    </tr>
                 @endif
            @endforeach
        
    </tbody>
	
</table>
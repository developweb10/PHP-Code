@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		
		<!-- Delete Lot Modal -->
		<div id="termsDialog" class="modal fade" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					@if( !empty($promo_error) )
						<div class="alert alert-danger text-center" style="margin-bottom: 0px;">
							<b>Error!</b> {{ $promo_error }}
						</div>
					@endif
					<form method="post" action="{{ URL::to('/home/validate_promo_code') }}" >
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="modal-header">
							<h4 class="modal-title text-center">Enter Promo Code</h4>
						</div>
						<div class="modal-body text-center">
                        	<br />
							<?php /*?><h3 class="text-center">Do you have referral promo code?</h3><?php */?>
                            <input type="text" required name="promo_code" class="form-control" value="" placeholder="4-digit Promo Code" style="width:210px; margin:0 auto;text-align:center;" >
                            <div class="clearfix"></div>
							
						</div>
						<div class="modal-header text-center">
							<input type="submit" value="Submit" class="btn btn-success" /> 
							<a class="btn btn-danger" href="" >Cancel & Continue</a> 
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<div class="clearfix"></div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function(){
		
		$("#termsDialog").modal({backdrop: 'static', keyboard: false});
		
	}, false);
</script>

@endsection  
<div class="">

<form class="form-horizontal" role="form" method="POST" action="" id="payment_details_form">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<h4 class="text-center">Personal Details</h4>
	<hr>

	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="control-label">First Name</label>
		<div class="">
			<input name="FirstName" value="{{ $user->FirstName }}" placeholder="First Name" class="form-control required" />
		</div>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="control-label">Last Name</label>
		<div class="">
			<input name="LastName" value="{{ $user->LastName }}" placeholder="Last Name" class="form-control required" />
		</div>
	</div>

	<?php /*
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<label class="control-label">Complete Address</label>
		<div class="">
			<input type="text" name="CompleteAddress" placeholder="Complete Address" class="form-control required" value="{{ $user->CompleteAddress }}" />
		</div>
	</div>
	*/ ?>
	

	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="control-label">Country</label>
		<div class="">
			<select name="CountryIsoCode" id="CountryIsoCode" class="form-control required" >
				<option value="">Select Country</option>
				@foreach( $countries as $country )
					@if( isset($country["IsoCode"]) and isset($country["Name"]) and isset($country["HasTown"]) and in_array($country["IsoCode"],array("CA","US")) )

						<option value='{{ $country["IsoCode"] }}'  @if( $country["IsoCode"] === $user->CountryIsoCode  ) selected="selected" @endif >{{ $country["Name"] }}</option>
					@endif
				@endforeach
			</select>
		</div>
	</div>

	
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="control-label">State/Province</label>
		<div class="">
			<select name="StateId" id="StateId" class="form-control required" >
				<option value="">Select State/Province</option>
			</select>
		</div>
	</div>



	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="control-label">City</label>
		<div class="">
			<select name="CityId" id="CityId" class="form-control required" >
				<option value="">Select City</option>
			</select>
		</div>
	</div>
	<?php /*
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 TownId_div">
		<label class="control-label">Town</label>
		<div class="">
			<select name="TownId" id="TownId" class="form-control required" >
				<option value="">Select Town</option>
			</select>
		</div>
	</div>
	*/ ?>
	<div class="clearfix"></div>

	<hr>

	<h4 class="text-center">Bank Details</h4>
	<hr>

	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="control-label">Payment Mode</label>
		<div class="">
			<select name="PaymentModeId" id="PaymentModeId" class="form-control required" >
				<option value="">Select Payment Mode</option>
			</select>
		</div>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="control-label">Receiver Currency</label>
		<div class="">
			<select name="ReceiveCurrencyIsoCode" id="ReceiveCurrencyIsoCode" class="form-control required" >
				<option value="">Select Receiver Currency</option>
			</select>
		</div>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="control-label">Bank</label>
		<div class="">
			<select name="BankId" id="BankId" class="form-control required" >
				<option value="">Select Bank</option>
			</select>
		</div>
	</div>
	
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="control-label">Account #</label>
		<div class="">
			<input name="Account" value="{{ $user->Account }}" placeholder="Account" class="form-control required" />
		</div>
	</div>

	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<label class="control-label">Transit # / Routing Number</label>
		<div class="">
			<input name="BankBranchId" value="{{ $user->BankBranchId }}" placeholder="BankBranchId" class="form-control required" />
		</div>
	</div>

	<div class="clearfix"></div>
	<br clear="all"><br>

	<div id="message_div_html"></div>
	<div class="clearfix"></div>

	<div class="modal-footer">
		<button type="button" name="enter_payment_details" id="enter_payment_details" class="btn btn-default">Save</button>
	</div>	

</form>
</div>
<div class="clearfix"></div>

<script type="text/javascript">

	selected_state 		= "{{ $user->StateId }}";
	selected_city 		= "{{ $user->CityId }}";
	selected_town 		= "{{ $user->TownId }}";
	selected_Currency 	= "{{ $user->ReceiveCurrencyIsoCode }}";
	selected_bank 		= "{{ $user->BankId }}";
	selected_mode 		= "{{ $user->PaymentModeId }}";

	setTimeout(function(){
		@if( !empty($user->CountryIsoCode) )
			$("#CountryIsoCode").change();	
		@endif
	}, 1000);
</script>

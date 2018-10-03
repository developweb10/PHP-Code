<?php
echo "TESTING";

	$price_array = array(

		"0.5"		=> 		"30 min",

		"1"			=> 		"1 Hr",

		"1.5"		=> 		"1.5 Hr",

		"2"			=> 		"2 Hr",

		"2.5"		=> 		"2.5 Hr",

		"3"			=> 		"3 Hr",

		"4"			=> 		"4 Hr",

		"5"			=> 		"5 Hr",

		"6"			=> 		"6 Hr",

		"7"			=> 		"7 Hr",

		"8"			=> 		"8 Hr",

		"9"			=> 		"9 Hr",

		"10"		=> 		"10 Hr",

	);

?>

<div style="position:relative">

<form class="form-horizontal" role="form" method="POST" action="{{ url('/home/pay') }}" id="customer_pay_form">

	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<div class='row'>



        <div class="col-md-4 col-lg-4">

        </div>



		<div class='col-md-4 col-lg-4 text-center'>

			<img src="{{ asset('images/metercar.png') }}" style="max-height:100px;" >

            <div class="clearfix"></div>

            <br clear="all" />



			<div>

		

				<div>

					<div class="meter_message to-text padinglr alert alert-success " style="display:none; ">

						@if( isset($meter) && $meter['meter_id'] > 0 )

							<div class="meter_price_message to-text text-center" style="display:none;"></div>

						@else

							<span></span>

						@endif

					</div>

				</div>

		

				<div>

					@if( isset($meter) && $meter['meter_id'] > 0 )
						IF METER is SET
                    	<br />

						<div class="add-more-time-div">

							<div class="to-text text-center" style="font-size:20pt;">Meter #: {{ $meter['meter_id'] }}</div>

							

							<div class="clearfix"></div>

		

							<div class="meter_message to-text text-center" style="border:1px solid green;">Price: ${{ $meter['hour_price'] }} /hr</div>

		

							<div class="clearfix"></div>

							

							<input type="hidden" name="meter_id" value="{{ $meter['meter_id'] }}" >

							<input type="hidden" name="add_more_time" value="true" />

						</div>

						<div class="clearfix"></div>

						<br />

						<div class="mobile-item"><br /></div>

						<div class="text-center" style="position: relative;">

                       		<button type="button" class="sleectText expiry_time" >

                       			@if( isset($price_array[old('expiry_time')]) ) {{ old('expiry_time') }} @else Time @endif

                       			<i class="fa fa-tachometer" aria-hidden="true" style="font-size: 22px;padding-left: 10px;"></i>

                       		</button>

                       		<div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i></div>

							<select name="expiry_time" class="form-control_ centered-select select_center_text" required onchange="calculate_price()" >

								<option value="">Time</option>

                                @foreach( $price_array as $price=>$time )

                                	<option value="{{ $price }}" @if( old('expiry_time') === '0.5' ) selected="selected" @endif  >{{ $time }}</option>	

                                @endforeach

                            </select>

						</div>

		

						<div class="clearfix"></div>

		

					@else

						ELSE

						<div class="text-center"> 
								TESt
							<input type="text" class="form-control" name="meter_id" pattern="\d{6}" value="{{ old('meter_id') }}" required placeholder="Meter #" >

						</div>

						<div class="clearfix"></div>

						<br />

						<div class="text-center"  style="position: relative;">

							<button type="button" class="sleectText expiry_time" >

                       			@if( isset($price_array[old('expiry_time')]) ) {{ old('expiry_time') }} @else Time @endif

                       			<i class="fa fa-tachometer" aria-hidden="true" style="font-size: 22px;padding-left: 10px;"></i>

                       		</button>



                        	<!-- <input type="button" class="sleectText expiry_time" value="@if( isset($price_array[old('expiry_time')]) ) {{ $price_array[old('expiry_time')] }} @else Time @endif"> -->

                        	<div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i>

                        </div>









							<select name="expiry_time" class="form-control centered-select select_center_text useselect" required onchange="calculate_price()" >

								<option value="">Time</option>

                                @foreach( $price_array as $price=>$time )

                                	<option value="{{ $price }}" @if( old('expiry_time') == $price ) selected="selected" @endif  >{{ $time }}</option>	

                                @endforeach

								

								<?php /*?><option value="1" @if( old('expiry_time') === '1' ) selected="selected" @endif >1 Hr</option>

								<option value="1.5" @if( old('expiry_time') === '1.5' ) selected="selected" @endif >1.5 Hr</option>

								<option value="2" @if( old('expiry_time') === '2' ) selected="selected" @endif >2 Hr</option>

								<option value="2.5" @if( old('expiry_time') === '2.5' ) selected="selected" @endif >2.5 Hr</option>

								<option value="3" @if( old('expiry_time') === '3' ) selected="selected" @endif >3 Hr</option>

								<option value="4" @if( old('expiry_time') === '4' ) selected="selected" @endif >4 Hr</option>

								<option value="5" @if( old('expiry_time') === '5' ) selected="selected" @endif >5 Hr</option>

								<option value="6" @if( old('expiry_time') === '6' ) selected="selected" @endif >6 Hr</option>

								<option value="7" @if( old('expiry_time') === '7' ) selected="selected" @endif >7 Hr</option>

								<option value="8" @if( old('expiry_time') === '8' ) selected="selected" @endif >8 Hr</option>

								<option value="9" @if( old('expiry_time') === '9' ) selected="selected" @endif >9 Hr</option>

								<option value="10" @if( old('expiry_time') === '10' ) selected="selected" @endif >10 Hr</option><?php */?>

							</select>

						</div>

                        

                        @if( !empty(old('meter_id')) and !empty(old('expiry_time')) )

                        	<script type="text/javascript">

                            	document.addEventListener("DOMContentLoaded",function(){ 

                            		setTimeout(function(){ validate_meter() }, 1000);

                            	},false);

                            </script>

                        @endif

                        

					@endif

					

				</div>



			</div>



			<div class="clearfix"></div>



            <div class="meter_price_message price-msg to-text" style="display:none;margin-top:20px;"></div>





            <div class="paddinlrsmall" style="position: relative; float:left; width:100%;">

                <div id="paypal_form" style="margin-top:20px;@if (count($errors) == 0) display:none; @endif">

                    <div class="text-center" >

                        <img src="{{ asset('/images/card_types.png') }}" border="0" class="cart_types_img"  />

                    </div>

                    <div>

                        <input type="text" class="form-control text-center" name="cc_number" value="{{ old('cc_number') }}" placeholder="Card Number" required="required" pattern="\d*" style="font-size:18px;" >

                    </div>

                    <br />

                    <div>

                    <input type="button" class="sleectText" value="@if( !empty(old('expiry_month')) ) {{ old('expiry_month') }} @else Expiry Month @endif" style=" width:100%;"><div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i></div>

                        <select name="expiry_month" class="form-control select_center_text" style="text-align-last:center;" required="required" >

                            <option value="">Expiry Month</option>

                            @for ($i=1; $i<=12;$i++)

                            <option @if (old('expiry_month') == $i) selected="selected" @endif>{{ $i}}</option>

                            @endfor

                        </select>

                    </div>

                    <br />  

                    <div>

                    <input type="button" class="sleectText" value="@if( !empty(old('expiry_year')) ) {{ old('expiry_year') }} @else Expiry Year @endif" style=" width:100%;"><div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i></div>

                        <select name="expiry_year" class="form-control select_center_text" style="text-align-last:center;" required="required" >

                            <option value="">Expiry Year</option>

                            @for ($i=2016; $i<=2040;$i++)

                            <option @if (old('expiry_year') == $i) selected="selected" @endif>{{ $i}}</option>

                            @endfor

                        </select>

                        

                    </div>

        

                </div>	

            </div>



            <div class="clearfix"></div>



            <div style="position:relative;float:left; width:100%;">

            	<br />

                <button type="button" class="btn btn-primary btn-block green-button" id="pay_button">Pay <i class="fa fa-credit-card" aria-hidden="true"></i></button>

            </div>





		</div>





        <div class="col-md-4 col-lg-4">

        </div>

		

        <div class="clearfix"></div>

		<br /><br />

	</div>

	

	

</form>

</div>

<style>

	.meter_price_message.price-msg .alert{ margin-bottom:0px; }
	#terms-link{
		border: none; 
	}
	body #customer_pay_form .form-control, button.expiry_time{
		box-shadow: none;
		outline: none;
		border: 1px solid #e8e4e4;
		 -webkit-appearance: none;
	}

</style>

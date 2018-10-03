
<div class="container-fluid">
	<div class="row">
		<div class="">
			<div class="panel panel-default">
				<!-- <div class="panel-heading">My Account</div> -->
				<div class="panel-body">
					<div class="col-md-10 col-md-offset-1"> 						
						@if ( $vars['tab'] === 'account' && count($errors) > 0)
							<div class="alert alert-danger">
								<strong>Whoops!</strong> There were some problems with your input.<br><br>
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif
						
						@if( $vars['tab'] === 'account' && Session::has('success'))
							<div class="alert alert-success">
								<strong>Success!</strong> {{ Session::get('success') }}
							</div>
						@endif
						
                        @if ((!config('is_Ipad')) && config('is_mobile'))  
                              <span class="to-text page_title"><b>Account</b></span>
                        @endif

						<!--<small>* Please fill in accurate details as they will be used while processing your payments</small>-->
						<form class="form-horizontal" role="form" method="POST" action="{{ url('/home/account') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div>
								<div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="control-label">First Name</label>
									<div class="">
										<input type="text" class="form-control" name="name" value="{{ $user->name }}" placeholder="First Name" >
									</div>
								</div>

								<div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="control-label">Last Name</label>
									<div class="">
										<input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" placeholder="Last Name" >
									</div>
								</div>
							
								<!--<div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="control-label">E-Mail Address</label>
									<div class="to-text">
										{{ $user->email }}
										<input type="hidden" name="email" value="{{ $user->email }}" >
									</div>
								</div>-->
							
								<!--<div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<label class="control-label">Mobile Phone</label>
									<div class="">
										<input type="text" class="form-control" name="phone" placeholder="Mobile Phone" value="{{ $user->phone }}" >
									</div>
								</div>-->
							</div>

                            

							<hr class="form_hr" />
							<div>
								<div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="control-label">Address</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="street" placeholder="Street" value="{{ $user->street }}" >
                                    </div>
                                </div>
     
                                <div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="control-label">Postal Code</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="zip" value="@if(!empty($user->zip)) {{$user->zip}}@endif" placeholder="Postal Code">
                                    </div>
                                </div>
    
                                <div class="input-field input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="control-label">Province</label>
                                    <div class="">
                                        <select name="state" id="state_list" class="form-control">
                                            <option value="">Select State/Province</option>
                                            @if( count($states) )
            
                                            @foreach( $states as $state ) 
                                                    <option value="{{ $state->state_code }}" @if( $user->state == $state->state_code ) selected="selected" @endif >{{ $state->state_code }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>	
    
                                <div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="control-label">City</label>
                                    <div class="">
                                        <select name="city" id="city_list" class="form-control">
                                            <option value="">Select City</option>
                                            @if( count($cities) )
                                                @foreach( $cities as $city )
                                                    <option value="{{ $city->id }}" @if( $user->city == $city->id ) selected="selected" @endif >{{ $city->city_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
							</div>

                            <!--

                            <hr class="form_hr" />

                            

                            <div>

                                <div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="control-label">Account Number</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="account_no" placeholder="Account Number" value="{{ $user->account_no }}">
                                    </div>
                                </div>
								
								<div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="control-label">Transit Number</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="transit_no" placeholder="Transit Number" value="{{ $user->transit_no }}">
                                    </div>
                                </div>

                            </div>
							
							<div>

                                <div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="control-label">Bank:</label>
                                    <div class="">
										<select name="bank_code" id="bank_list" class="form-control">
											<option value="">Select Bank</option>
											@if( isset($banks) && count($banks) )
												@foreach( $banks as $bank )
													<option value="{{ $bank->Id }}" @if( $user->bank_code == $bank->Id ) selected="selected" @endif >{{ $bank->Name }}</option>	
												@endforeach	
											@endif	
										</select>
                                    </div>
                                </div>

                            </div>

                            --> 
							<hr class="form_hr" />

                            

                            <div>
							<div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label class="control-label">Current Password</label>
								<div class="">
									<input type="password" class="form-control" name="old_password" placeholder="****" >
								</div>
							</div>
							
                            <div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label class="control-label"></label><p></p>
								<div class="">
									<small><strong>NOTE:</strong> If you'd like to change your password, please enter your old password first.</small>
								</div>
							</div>
                            
                            <div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label class="control-label">New Password</label>
								<div class="">
									<input type="password" class="form-control" name="password" placeholder="****" >
								</div>
							</div>
                            
                            <div class="input-field col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<label class="control-label">Confirm New Password</label>
								<div class="">
									<input type="password" class="form-control" name="password_confirmation" placeholder="****" >
								</div>
							</div>
                            
                           	</div>
							
							<!--<div class="form-group">
								<label class="control-label"></label>
								<div class="">
									<small><strong>NOTE:</strong> If you'd like to change your password, please enter your old password first.</small>
								</div>
							</div>-->
                            
                            
                            <!--***** toggle button email,sms ,variable rates start *****-->
                            
							<?php  $sms_feature   = ( (isset($settings_select['sms_feature']) && !empty($settings_select['sms_feature'])?$settings_select['sms_feature']:0 ));
								  $email_feature = ( (isset($settings_select['email_feature']) && !empty($settings_select['email_feature'])?$settings_select['email_feature']:0 ));
								  $variable_rates = ( (isset($settings_select['variable_rates']) && !empty($settings_select['variable_rates'])?$settings_select['variable_rates']:0 ));
							
							$recipient_email = $settings_select['recipient_email'];
								if(isset($recipient_email) && empty($recipient_email) ||!isset($recipient_email)){
									$recipient_email = $user->email;
							}
							$recipient_mobile = $settings_select['recipient_mobile'];
								if(isset($recipient_mobile) && empty($recipient_mobile) ||!isset($recipient_mobile)){
									//$recipient_mobile = $user->mobile;
							}
							
							?>
                           <hr class="form_hr" />
                            
                            <div class="input-field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label class="Email-label">Email Feature :</label>
                                <div class="">
                                    <input type="checkbox" data-toggle="toggle" data-style="ios" id="email_feature" name="email_feature" <?php if ($email_feature == 1){ ?> checked="checked" <?php } else { ?><?php } ?>>
                                    
                                    <input id='email_feature_hidden' type='hidden' value='0' name='email_feature'>
                                    
                                     <input type="email" class="input-form" name="recipient_email" id="recipient_email" placeholder="Email" value="<?php echo $recipient_email; ?>" <?php if ($email_feature != 1){?> style="display:none" <?php } else { ?><?php } ?> />
                                    
                                </div>  
							</div>
                            
                            <div class="input-field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="Email-label">Sms Feature :</label>
                                <div class="">
                                    <input type="checkbox" data-toggle="toggle" data-style="ios" name="sms_feature" id="sms_feature" <?php if ($sms_feature == 1){?> checked="checked" <?php } else { ?><?php } ?>>
                                   <input id='sms_feature_hidden' type='hidden' value='0' name='sms_feature'>
                                   
                                   <input type="text" class="input-form" <?php if ($sms_feature != 1){?> style="display:none" <?php } else { ?><?php } ?> name="recipient_mobile" id="recipient_mobile" value="<?php echo $recipient_mobile; ?>" placeholder="Mobile Number" />
                                   
                                </div>
							</div>
                            
                            <br />
                            
							<!--***** toggle button email,sms end *****-->
                            <div class="clearfix"></div>
							<div class="form-group">
								<div class="text-right">
									<button type="submit" class="btn btn-primary">
										Submit
									</button>
								</div>
							</div>
							
							<div class="clearfix"></div>
						</form>

					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div id="payment_details_dialog" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
        	<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">Payment Details</h4>
                <p class="text-center">* Below details are mandatory and should be filled carefully in order to receive payment.</p>
        	</div>
        	<div class="modal-body">
        		
        	</div>
        	
        </div>
    </div>
</div>

<style type="text/css">
	.input-field{  height: 75px;  }

	.form_hr{	width:100%; float:left; }
	
	
</style>

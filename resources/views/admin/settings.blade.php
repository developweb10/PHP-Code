@extends('app')

@section('content')

<div class="container-fluid">
	<div class="panel panel-default">
		<div class="panel-heading">Settings</div>
		<div class="panel-body">
			@if ( count($errors) > 0)
				<div class="alert alert-danger">
					<strong>Whoops!</strong> There were some problems with your input.<br><br>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			
			@if( Session::has('success'))
				<div class="alert alert-success">
					<strong>Success!</strong> {{ Session::get('success') }}
				</div>
			@endif
			
			<form action="" method="post" class="form-inline">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="settings" class="btn btn-default">Save</button>
				</div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-3 to-text"><strong>Sales Manager Commission</strong></div>
					<div class="col-md-4">
						<input type="text" name="sm_commission" class="form-control" value="{{ $settings->sm_commission or "" }}" > %
					</div>
					<div class="clearfix"></div>
				</div>
				<br clear="all" />
				<hr />
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-3 to-text"><strong>Sales Associates Commission</strong></div>
					<div class="col-md-4">
						<input type="text" name="sa_commission" class="form-control" value="{{ $settings->sa_commission or "" }}" > %
					</div>
					<div class="clearfix"></div>
				</div>
				<br clear="all" />
				<hr />
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-3 to-text"><strong>Promo Code Discount for Landowners</strong></div>
					<div class="col-md-4">
						<input type="text" name="promo_code_discount_lo" class="form-control" value="{{ $settings->promo_code_discount_lo or "" }}" > %
					</div>
					<div class="clearfix"></div>
				</div>
				<br clear="all" />
				<hr />
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-3 to-text"><strong>Per Parking Sign Price for Landowners</strong></div>
					<div class="col-md-4">
						<input type="text" name="meter_price_lo" class="form-control" value="{{ $settings->meter_price_lo or "" }}" > $
					</div>
					<div class="clearfix"></div>
				</div>
                
                <br clear="all" />
				<hr />
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-3 to-text"><strong>Shipping Price</strong></div>
					<div class="col-md-4">
						<input type="text" name="ship_price_lo" class="form-control" value="{{ $settings->ship_price_lo or "" }}" > $
					</div>
					<div class="clearfix"></div>
				</div>
                
                <!-- landowner email, sms, feature setings  -->
                
                <?php  $sms_feature   = ( (isset($settings['sms_feature']) && !empty($settings['sms_feature'])?$settings['sms_feature']:0 ));
								  $email_feature = ( (isset($settings['email_feature']) && !empty($settings['email_feature'])?$settings['email_feature']:0 ));
								  $variable_rates = ( (isset($settings['variable_rates']) && !empty($settings['variable_rates'])?$settings['variable_rates']:0 ));
								 
							?>
                
                 <!--<br clear="all" />
				<hr />
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-3 to-text"><strong>Variable Rate</strong></div>
					<div class="col-md-4">
						<input type="checkbox" data-toggle="toggle" data-style="ios" id="variable_rates" name="variable_rates" <?php if ($variable_rates == 1){?> checked="checked" <?php } else { ?><?php } ?>>
                                    
                                    <input id='variable_rates_hidden' type='hidden' value='off' name='variable_rates'>
					</div>
					<div class="clearfix"></div>
				</div>-->
                
                 <br clear="all" />
				<hr />
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-3 to-text"><strong>Email Feature</strong></div>
					<div class="col-md-4">
						<input type="checkbox" data-toggle="toggle" data-style="ios" id="email_feature" name="email_feature" <?php if ($email_feature == 1){ ?> checked="checked" <?php } else { ?><?php } ?>>
                                    
                                    <input id='email_feature_hidden' type='hidden' value='off' name='email_feature'>
					</div>
					<div class="clearfix"></div>
				</div>
                
                 <br clear="all" />
				<hr />
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="col-md-3 to-text"><strong>Sms Feature</strong></div>
					<div class="col-md-4">
						<input type="checkbox" data-toggle="toggle" data-style="ios" id="sms_feature" name="sms_feature" <?php if ($sms_feature == 1){?> checked="checked" <?php } else { ?><?php } ?>>
                                    
                                    <input id='sms_feature_hidden' type='hidden' value='off' name='sms_feature'>
					</div>
					<div class="clearfix"></div>
				</div>
                
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="settings" class="btn btn-default">Save</button>
				</div>

			</form>
			
			
		</div>
	</div>
</div>

@endsection
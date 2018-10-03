<div class="container-fluid">

	<div class="row">

		<div class="">

			<div class="panel panel-default">

				<div class=""><!-- Generate Signage -->

	                <div class="text-right col-md-12">

	                	<br>

                    	<form role="form" class="form-inline towing-number-form" action="{{ URL::to('/home/save_towing_number') }}" method="post" >

                        	<span class="to-text">Local Towing Company Number:</span>

                        	<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<input type="text" name="towing_company_number" value="{{ $user->towing_company_number }}" class="form-control input-sm" >

                            <input type="submit" value="Save" class="btn btn-default btn-sm">

                        </form>

                        <hr>

                    </div>

                </div>

				<div class="panel-body">

                	

                    <div class="clearfix"></div>

                    <br />

					@if ( $vars['tab'] === 'signage' && count($errors) > 0)

						<div class="alert alert-danger">

							<strong>Whoops!</strong> There were some problems with your input.<br><br>

							<ul>

								@foreach ($errors->all() as $error)

									<li>{{ $error }}</li>

								@endforeach

							</ul>

						</div>

					@endif

					

					@if( $vars['tab'] === 'signage' && Session::has('success'))

						<div class="alert alert-success">

							<strong>Success!</strong> {{ Session::get('success') }}

						</div>

					@endif

					

					 @if( !empty($user->towing_company_number) )<form method="post" action="{{ URL::to('/home/generateSignage') }}" >@endif

						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div id="signage_form" class="form-horizontal col-md-5">

							

                            <h4 style="margin-top:0px;">Select Meter #</h4>

							<div class="form-group">

								<div class="col-md-12">

									<select name="lot_id" class="form-control" onchange="getsignagemeters(this)" required="required" >

										@if( $mylots->count() )

											@foreach( $mylots as $lot )

												<option data-price="{{ $lot->price }}" value="{{ $lot->id }}" @if( $vars['lot_id'] == $lot->id ) selected="selected" @endif >{{ $lot->lot_name }}</option>

											@endforeach

										@endif

									</select>

								</div>

							</div>

							

							<div class="form-group">

								<div class="col-md-12">

									<?php $selected_meter_id = ( $mymeters->count() ? $mymeters[0]->meter_id : 0 ); ?>

									<br />

									<div class="checkboxes-section">

										@if( $mymeters->count() )

											<table class="table table-stripe table-responsive">

												<tr>

													<td colspan="4">

														<label>

															<input type="checkbox" name="meter_id[]" value="">

															All

														</label>

													</td>

												</tr>

												@for( $i=0; $i<count($mymeters); $i++ )

												

													<tr>

														<?php $meter = $mymeters[$i]; ?>

														<td>

															<label>

																<input type="checkbox" name="meter_id[]" value="{{ $meter->meter_id }}">

																{{ $meter->meter_id }}

															</label>

														</td>

														<?php $i++; ?>

														@if( isset($mymeters[$i]) )

															<?php $meter = $mymeters[$i]; ?>

															<td>

																<label>

																	<input type="checkbox" name="meter_id[]" value="{{ $meter->meter_id }}">

																	{{ $meter->meter_id }}

																</label>															

															</td>

														@endif

														<?php $i++; ?>

														@if( isset($mymeters[$i]) )

															<?php $meter = $mymeters[$i]; ?>

															<td>

																<label>

																	<input type="checkbox" name="meter_id[]" value="{{ $meter->meter_id }}">

																	{{ $meter->meter_id }}

																</label>															

															</td>

														@endif

														<?php $i++; ?>

														@if( isset($mymeters[$i]) )

															<?php $meter = $mymeters[$i]; ?>

															<td>

																<label>

																	<input type="checkbox" name="meter_id[]" value="{{ $meter->meter_id }}">

																	{{ $meter->meter_id }}

																</label>															

															</td>

														@endif

													</tr>

												

												@endfor

											</table>

										@else

											<div class="alert alert-warning margin-bottom-0">No meter found for selected group!</div>

										@endif

									</div>

									<br />

									<!-- <select name="meter_id[]" class="form-control" id="generate_signage_html" multiple="multiple" size="5" >

										<option value="">All</option>

										@foreach( $mymeters as $meter )

											<option value="{{ $meter->meter_id }}" >{{ $meter->meter_id }}</option>

										@endforeach

									</select> 

									<br />

									<small>Use Ctrl + Left click to select multiple.</small>-->

								</div>

							</div>

                            

							<div class="clearfix"></div>

                            

                            @if( !empty($user->towing_company_number) )

                            

                                <div class="form-group">

                                    <div class="col-md-12 text-left"><button type="submit" class="btn btn-success btn-lg btn-block">Download</button></div>

                                </div>

                                <div class="clearfix"></div>

							@else

                                <div class="form-group">

                                    <div class="col-md-12 text-left"><button type="button" data-toggle="modal" data-target="#showMessageModal" class="btn btn-success btn-lg btn-block">Download</button></div>

                                </div>

                                <div class="clearfix"></div>



                                <div id="showMessageModal" class="modal fade" role="dialog">

                                    <div class="modal-dialog">

                                        <div class="modal-content">

                                            <div class="modal-header">

                                                <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                <h4 class="modal-title text-center">Towing Company Phone Number</h4>

                                            </div>

                                            <div class="modal-body">

                                            	<form role="form" class="form-horizontal" action="{{ URL::to('/home/save_towing_number') }}" method="post" >

								                    <div class="modal-body text-center">

								                        <div class="col-md-8 col-md-offset-2">

								                            Please enter the phone number of the towing company that you will engage for your parking spaces.

								                            <hr />

								                        

								                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

								                            <div class="col-md-8 col-md-offset-2">

								                                <input type="text" name="towing_company_number" value="{{ $user->towing_company_number }}" class="form-control input-sm text-center" >

								                            </div>

								                            

								                        </div>

								                        <div class="clearfix"></div>

								                    </div>

								                    <div class="modal-footer text-right">

								                        <input type="submit" value="Save" class="btn btn-default">

								                       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

								                    </div>

								                </form>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                

                            	

                            @endif

							<br />

                             <a href="https://www.google.co.in/webhp#q=Parking+sign+printshop+in+{{ $user->city_name }}" target="_blank" class="btn btn-default btn-block"><i class="fa fa-search"></i> Search nearby Printing Locations</a>                            

							<a href="{{ URL::to('/admin/signage') }}" target="_blank" class="btn btn-default btn-block">Click Here to purchase Signs </a> 

						</div>



						<div class="col-md-2">

						</div>

                        

                        <div class="col-md-5 text-center signage-right-section">

                        	@if( $vars["owner_content"]->signage_image != '' )

                                <img src="{{ $vars["owner_content"]->signage_image }}" border="0" width="60%" />

                                <br />

                                <br />

                                The hourly rate, meter #, and tow truck phone number will be added once you've selected the meter(s) and hit the Download button.

                            @endif

                        </div>

                        <div class="clearfix"></div>

					@if( !empty($user->towing_company_number) )</form>@endif

                    <hr />

							<div class="signage_image">

								<?php /*?><div class="col-md-5 img_html" id="pdf_container1">

									<div class="signage_img_html" style="border: 3px solid gray;text-align:center;">

										<div style="background-color: #A6E35D;margin:2px;">&nbsp;

											<h2>$<span class="lot_print_price">{{ $vars["lot_price"] }}</span>/hr</h2>

											my-meter.com

											<h3># <span class="meter_print_id">{{ $selected_meter_id }}</span></h3>

											&nbsp;

										</div>

									</div>

								</div><?php */?>

								<div class="col-md-12">

                                	@if( $vars["owner_content"]->signage_content ) {{ app('App\Http\Controllers\UtilsController')->html_decode($vars["owner_content"]->signage_content) }} @endif

									<br />

								</div>

							</div>

				</div>

			</div>

		</div>

		<div class="clearfix"></div>

	</div>

</div>



@if( empty($user->towing_company_number) )

	<script type="text/javascript">

		document.addEventListener( "DOMContentLoaded", function(){



			/*$("#forceMessageModal").modal({

				backdrop: "static",

				keyboard: false,

				show: true

			});
*/


			$('a[href="#signage"]').click(function(){

				$("#forceMessageModal").modal({

					backdrop: "static",

					keyboard: false,

					show: true

				});

			});	

		}, false );

	</script>

    <div id="forceMessageModal" class="modal fade" role="dialog">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title text-center">Towing Company Phone Number</h4>

                </div>

                <form role="form" class="form-horizontal" action="{{ URL::to('/home/save_towing_number') }}" method="post" >

                    <div class="modal-body text-center">

                        <div class="col-md-8 col-md-offset-2">

                            Please enter the phone number of the towing company that you will engage for your parking spaces.

                            <hr />

                        

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="col-md-8 col-md-offset-2">

                                <input type="text" name="towing_company_number" value="{{ $user->towing_company_number }}" class="form-control input-sm text-center" >

                            </div>

                            

                        </div>

                        <div class="clearfix"></div>

                    </div>

                    <div class="modal-footer text-right">

                        <input type="submit" value="Save" class="btn btn-default">

                        <input type="button" value="Add Later" class="btn btn-default" data-dismiss="modal">

                    </div>

                </form>

            </div>

        </div>

    </div>

	@endif


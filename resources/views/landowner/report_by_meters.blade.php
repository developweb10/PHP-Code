<?php // echo "<pre>";  print_r($vars); echo "</pre>"; ?>	 
@if ( Session::has('register_order_success'))
    <div id="register_order_successModal" class="modal fade" role="dialog" >

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title text-center">Thank you for your order!</h4>

                </div>

                <div class="modal-body">

                    <div class='text-center'>

                        <img src="{{ asset('images/metercar.png') }}" style="max-height:100px;" >

                    </div>                        

                    <Br clear="all" />

                    <div class="alert alert-success">
                    	
                        @if(Session::get('register_order_success') == "purchase success")
							
                            Your order has been confirmed and will be shipped to your door in approximately 10 business days. Your new meter numbers have been added to your account.
                        	
                        @else
                        	
                            Your order has been confirmed and will be shipped to your door in approximately 10 business days.
                        
                        @endif

                        <div class="clearfix"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <script type="application/javascript">

        document.addEventListener( "DOMContentLoaded", function(){

            $("#register_order_successModal").modal("show");

        }, false );

    </script>
@endif
@if( $vars['export'] === '' ) 

<div class="">

	<div class="row">

		<div class="col-md-12">

			<div class="com"></div>

			<div class="panel panel-default">

				<!--<div class="col-md-12 meter-buttons text-left">

					<br>

					<button class="btn btn-default btn-sm" data-toggle="modal" data-target="#newLotModal" >New Group +</button>

                    @if( count($mylots) )

                        <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#newMeterModal" >+ New Meter</button>

                    @endif

				</div>-->

				<div class="clearfix"></div>

				<div class="panel-body">

					@if ( $vars['tab'] === '' && count($errors) > 0)

						<div class="alert alert-danger">

							<strong>Whoops!</strong> There were some problems with your input.<br><br>

							<ul>

								@foreach ($errors->all() as $error)

									<li>{{ $error }}</li>

								@endforeach

							</ul>

						</div>

					@endif

					

					@if( $vars['tab'] === '' && Session::has('success'))

						<div class="alert alert-success test_it">

							<strong>Success!</strong> {{ Session::get('success') }}

						</div>

					@endif

						

					<div class="text-center">

						<form role="form" class="form-inline filter-form" id="filter-form" action="{{ URL::to('/home') }}">

							<input type="hidden" name="export" id="export" value="">

							<fieldset>
                            
								@if ((!config('is_Ipad')) && config('is_mobile')) 
                                	<span class="to-text page_title"><b>Reports</b></span>
                                @endif
                                
								<span class="to-text"><b>Filter Results</b></span>

								<!--<select class="form-control" name="group_id">

									<option value="">Select Group (Default:All)</option>

									@foreach( $mylots as $group )

										<option value="{{ $group['id'] }}"  @if( $group['id'] == $vars['group_id'] ) selected="selected" @endif     >{{ $group['lot_name'] }}</option>

									@endforeach

								</select>-->

								&nbsp;&nbsp;&nbsp;&nbsp;

								<input type="text" class="form-control datepicker1" name="start_date" value="{{ $vars['start_date'] }}" placeholder="Start Date" @if ((config('is_Ipad')) || config('is_mobile')) readonly  @endif />

								<input type="text" class="form-control datepicker1" name="end_date" value="{{ $vars['end_date'] }}" placeholder="End Date" @if ((config('is_Ipad')) || config('is_mobile')) readonly  @endif />
 								<!--<select class="form-control" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>-->
                                
								<input type="submit" class="btn btn-default"  value="Search" />
								@if( (isset($vars["start_date"]) && !empty($vars["start_date"])) or (isset($vars["end_date"]) && !empty($vars["end_date"])) ) 
									<input type="reset" class="btn btn-default" value="Clear">
								@endif
							</fieldset>

						</form>

					</div>

					<br />

@else



	@if( $vars['export'] === 'PDF' )

		<html style="padding:0px; margin:10px;">

		<head>

		</head>

		<body style="padding:0px; margin:0px;">

        	<style type="text/css">

				body .lot_meters{

					font-family:arial !important;

				}

				.page-break {

					page-break-after: always;

				}

				.text-center{

					text-align:center;

				}
				.text-left{

					text-align:left;

				}
				.text-right{

					text-align:right;

				}
				
				table.lot_meters tbody tr {

				    background-color: #ffffff;

				}

				table.lot_meters tbody tr.odd, table.lot_meters tbody tr.odd {

				    background-color: #f9f9f9;

				}

			</style>

	@endif

    
	
    
    <table class="ouser_cont" style="max-width:650px; width:650px; margin:0 auto;"> 
    	<tr>
        	<td>
            	<span><img src="{{ asset('/images/gray-logo.png') }}" style='max-height:50px;'></span>
            </td>
            <td>
            	<div></div>
            </td>
            <td>
            	<span><?php /*?>{{ $vars['user']->name }}<?php */?>Published on {{ date('Y-m-d') }} </span>
            </td>
        </tr>
        <tr> 
        	<td align="center" colspan="3">
            	<h3>Report ( @if( $vars['start_date'] !== '' ) From: {{ $vars['start_date'] }} @endif  @if( $vars['end_date'] !== '' ) To: {{ $vars['end_date'] }} @endif  ) </h3>
            </td>
        </tr>
     </table>
@endif		


	<div class="table-responsive" <?php if ($vars['export'] != 'PDF' && (config('is_Ipad') != 1) && (config('is_Ipad')==0) && (config('is_mobile') == 1)) { ?> style="display:none;" <?php } ?>>

		<table id="datatable" class="table display lot_meters" cellspacing="0" width="100%" @if( $vars['export'] !== '' )  style="max-width:650px; width:650px; margin:0 auto; text-align:center"   @endif >

			<thead>

				<tr style="background-color: gray;color: #fff;">
					<!--
					@if( $vars['export'] === '' ) 

						<th class="text-center" valign="middle">

							<div class="dropdown" id="dropdown-button-container">

								<button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Action <span class="caret"></span></button>

								<ul class="dropdown-menu">

									<li><a href="#" data-toggle="modal" data-target="#changeMeterGroup" onClick="changeGroup();">Change Group</a></li>

									<li><a href="#" data-toggle="modal" data-target="#deleteMeterGroup" onClick="deleteMeter();">Delete</a></li>

								</ul>

							</div>

						</th> 

					@endif
					-->
					<th class="text-center" valign="middle">Meter #

						<?php /*?>@if( $vars['export'] === '' )

							<input type="search" name="meter_search" id="meter_search" value="" placeholder="Meter #" style="width:70%; margin: 0 auto; text-align:center;" />

						@else

							Meter #

						@endif<?php */?>

					</th>
                    @if( $vars['export'] != 'PDF' )
						<th class="text-center" valign="middle"><a href="#" data-toggle="modal" data-target="#share_incpt" style="color:#fff;"><img src="{{ asset('/images/live_feed.png') }}" border="0" class="live_feed" /></a></th>
                    @endif
                    <!--<th class="text-center" valign="middle">$/hr.</th>
					<th class="text-center" valign="middle">Group Name</th>-->
					<th class="text-center" valign="middle">Transactions</th>
                    <th class="text-center" valign="middle">Total Hours</th>
                    <th class="text-center" valign="middle">Revenue</th>
				</tr>

			</thead>

			<tbody>

				<?php $totals = array("count"=>count($vars['meter_details']),"revenue"=>0.00,"transactions"=>0.00,"hours"=>0,"net_revenue"=>0.00); $ii = 0; 
				
				
				$meter_id ='';
				$live_feed='';
				$price_h='';
				$trasactions='';
				$totals_hrs='';
				$revenue='';
				
				?>

				@foreach( $vars['meter_details'] as $meter )

					<tr 

						@if( $vars['export'] !== '' )

							@if($ii % 2 == 0) 	

								style="background-color: #f9f9f9;" 

							@else 

								style="background-color: #ffffff;" 

							@endif  

						@endif 

					>

					
                    
						<!-- @if( $vars['export'] === '' ) 

							<td class="text-center" align="center"><input type="checkbox" name="selectedMeters" value="{{ $meter->id }}"  /></td>

						@endif -->

						<td class="text-center meter_id" align="center">
							<a href="javascript:open_report_modal({{ $meter->meter_id }},'day');void(0);" >{{ $meter->meter_id }}</a>
						</td>
						


						<!--<td class="text-center" align="center">

							@if( $vars['export'] !== '' )

								{{ $meter->lot_name }}

							@else

								<a href="#" data-toggle="modal" data-target="#editLotModal" onClick="selectGroup({{ $meter->lot_id }});" >{{ $meter->lot_name }}</a>

							@endif

						</td>

						-->

						<?php 
                            $expiry = app('App\Http\Controllers\UtilsController')->date_difference(date('Y-m-d H:i:s'),$meter['expiry']);
                            $expiry_text = ( $expiry["hours"] == 0 && $expiry["mins"] == 0 ) ? "Expired" : ((($expiry["hours"] > 0)?$expiry["hours"]." Hrs ":"" ).$expiry["mins"]." Min");
                        ?>
                        
                        @if( $vars['export'] != 'PDF' )
	                        <td class="text-center">{{ $expiry_text }}</td>
                        @endif
                        <?php /*
                        <td class="text-center" align="center">
                        	@if(number_format($meter->hour_price,2) == 0.00)
                            	${{ number_format($meter->price,2) }}
                            @else
	                        	${{ number_format($meter->hour_price,2) }}
                            @endif
                        </td>
						*/ ?>
						<td class="text-center" align="center">{{ $meter->transactions }}</td>
                        
                        <?php //$net_revenue =  ( $meter->trans_amount * 80 )/100;  ?>

						<td class="text-center" align="center">{{ number_format(($meter->total_hours > 0) ? $meter->total_hours : 0,2) }}</td>
                        
                        <td class="text-center" align="center">$ {{ number_format($meter->landowner_revenue,2) }}</td>
                        
                        <?php /*?><td class="text-center">{{ number_format($meter->trans_amount,2) }}</td><?php */?>

						
                 <?php 
					
					$meter_id .= "<span class='meter_ids user_items'><a href='".'javascript:open_report_modal( '.$meter->meter_id.' ,"day");void(0);'."' > $meter->meter_id</a></span>"; 
					$live_feed .="<span class='expiry_text user_items'> $expiry_text </span>";
					$price_h .="<span class='price_h user_items'> $ ".number_format($meter->price,2)." </span>";
					$trasactions .="<span class='trasactions user_items'> $meter->transactions </span>";
					$totals_hrs .="<span class='totals_hrs user_items'>  ".number_format(($meter->total_hours > 0) ? $meter->total_hours : 0,2)."</span>";
					$revenue .="<span class='revenue user_items'> $  ".number_format($meter->landowner_revenue,2)." </span>";
					
					
				 ?>  
                        

					</tr>

					<?php 

						$totals["revenue"] += $meter->landowner_revenue;  $totals["transactions"] += $meter->transactions; $totals["hours"] += $meter->total_hours; $totals["net_revenue"] += $meter->landowner_revenue; 

						$ii++;

					?>

				@endforeach

			</tbody>
			<tfoot>

					
             @if( $vars['export'] !== '' )
	             <tr style="background-color: #eee; font-weight: bold;">
                	<td class="text-center" align="center"></td>
                    @if( $vars['export'] != 'PDF' )<td class="text-center" align="center"></td>@endif
                    <td class="text-center" align="center"></td>
                    <td class="text-center" align="center"></td>
                    <td class="text-center" align="center"></td>
                    <td class="text-center" align="center">$ {{ number_format($totals["net_revenue"],2) }}</td>
					<?php /*?><h2 class="text-center">
    
                        {{ $totals["count"] }} @if( $totals["count"] > 1 ) METERS @else METER @endif
    
                        <br>
    
                        {{ $totals["transactions"] }} Transactions
    
                        <br>
    
                        $ {{ number_format($totals["net_revenue"],2) }} Revenue
    
                    </h2><?php */?>
              	</tr>

			@else

				<tr>

						@if( $vars['export'] === '' ) 

							<!--<td class="text-center" align="center"></td>-->

						@endif

						<td class="text-center" align="center">{{ $totals["count"] }} @if( $totals["count"] > 1 ) METERS @else METER @endif</td>
                        
                        <td class="text-center" align="center"></td>
                        
                        <!--<td class="text-center" align="center"></td>-->

						<td class="text-center" align="center">{{ $totals["transactions"] }}</td>

						<td class="text-center" align="center">{{ ($totals["hours"] > 0) ? $totals["hours"] : 0 }}</td>
				                        
                        <td class="text-center" align="center">$ {{ number_format($totals["net_revenue"],2) }}</td>

						<?php /*?><td class="text-center">{{ number_format($totals["revenue"],2) }}</td><?php */?>

				</tr>	

			@endif 
			

		</tfoot>
		</table>
        	
	</div>
<!--< Table view For Mobile Devices Start>-->  
@if( $vars['export'] != 'PDF' )  
<?php
//if ((config('is_Ipad') == 1) && (config('is_Ipad')!=1) && (config('is_mobile') == 1)) {

//echo $Ipad.'--'.$mobile;

if ((config('is_Ipad') != 1) && (config('is_Ipad')==0) && (config('is_mobile') == 1)) {
	
    $html_meter_ids = htmlentities($meter_id);
    $html_live_feeds = htmlentities($live_feed);
    $html_price_hs = htmlentities($price_h);
    $html_trasactions = htmlentities($trasactions);
    $html_totals_hrs = htmlentities($totals_hrs);
    $html_revenue = htmlentities($revenue);
    
    $meter_ids = html_entity_decode($html_meter_ids); 
    $live_feeds = html_entity_decode($html_live_feeds);
    $price_hs = html_entity_decode($html_price_hs);
    $trasactions = html_entity_decode($html_trasactions);
    $totals_hrs = html_entity_decode($html_totals_hrs);
    $revenue = html_entity_decode($html_revenue);
     ?>  
       
    <div class="col-xs-12" style="padding:0;">
      <ul class="nav nav-pills nav-stacked">
        <li><a href="#" style="background-color: gray;color: #fff; border-radius: 0;"> Meter # </a></li>
        <li class="dropdown" style="background-color: darkgray;color: #fff;">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="selected_detail"> Revenue </span> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="active"><a data-toggle="tab" href="#revenus_cont">Revenue</a></li>
            <li><a data-toggle="tab" href="#live_feeds_cont">Live Feed</a></li>
            <li><a data-toggle="tab" href="#price_hs_cont">$/hr</a></li>
            <li><a data-toggle="tab" href="#trasactions_cont">Transactions</a></li>
            <li><a data-toggle="tab" href="#totals_hrs_cont">Total Hours</a></li>   
          </ul>
        </li>
      </ul>
      <div class="col-xs-6 text-center" style="padding:0;">
          <div class="meter_ids_cont" id="meter_ids_con">
           <?php echo $meter_ids;?>
            @if( $vars['export'] !== '' )
            @else	           
              <span class='user_items footer_span'>{{ $totals["count"] }} @if( $totals["count"] > 1 ) METERS @else METER @endif</span>
            @endif 
          </div>
      </div>
      <div class="col-xs-6 text-center" style="padding:0;">
        <div class="tab-content right_cont">
              <div id="revenus_cont" class="tab-pane fade in active">
              <?php echo $revenue;?>
               @if( $vars['export'] !== '' )
               	  <span class='user_items footer_span'>$ {{ number_format($totals["net_revenue"],2) }}</span>
                @else	           
                  <span class='user_items footer_span'>$ {{ number_format($totals["net_revenue"],2) }}</span>
                @endif 
              </div>
              <div id="live_feeds_cont" class="tab-pane fade">
              <?php echo $live_feeds;?>
              @if( $vars['export'] !== '' )
                @else	           
                  <span class='user_items footer_span'>
                  	<a href="#" data-toggle="modal" data-target="#share_incpt">Share</a>
                  </span>
                @endif 
              </div>
              <div id="price_hs_cont" class="tab-pane fade">
              <?php echo $price_hs;?>
              @if( $vars['export'] !== '' )
                @else	           
                  <span class='user_items footer_span'></span>
                @endif 
              </div>
              <div id="trasactions_cont" class="tab-pane fade">
              <?php echo $trasactions;?>
               @if( $vars['export'] !== '' )
                @else	           
                  <span class='user_items footer_span'>{{ $totals["transactions"] }}</span>
                @endif 
              </div>
              <div id="totals_hrs_cont" class="tab-pane fade">
              <?php echo $totals_hrs;?>
              @if( $vars['export'] !== '' )
                @else	           
                  <span class='user_items footer_span'>{{ ($totals["hours"] > 0) ? $totals["hours"] : 0 }}</span>
                @endif 
              </div>
          </div>
      </div>
      <div class="clearfix"></div>
    </div>    
    <!--<div class="col-xs-6 text-center"><a href="#" data-toggle="modal" data-target="#share_incpt" style="color:#fff;"><img src="{{ asset('/images/sharebtn.png') }}" border="0" class="live_feed" /></a></div>-->
    
<?php 
}
?>
@endif
<!--< Table view For Mobile Devices End>-->

        
	<!--<div class="text-right download_img">
        <br clear="all">
        <img src="{{ asset('/images/download.png') }}">
    </div>-->
   <!--<a href="#" data-toggle="modal" data-target="#share_incpt"><img src="/images/md_share.png" alt="Share" /></a> -->    
	
    
    @if( $vars['export'] === '' )	

					@include('includes.export-buttons')
                    

				</div>

				

			</div>

		</div>

		<div class="clearfix"></div>

	</div>

</div>



@else

		@if( $vars['export'] === 'PDF' )

			</body>

			</html>

		@endif

@endif







@if( $vars['export'] === '' )



<!-- New Lot Modal -->
<?php /* ?>
@include("landowner.new-lot-html")
<?php */ ?>


<!-- Edit Lot Modal -->

<div id="editLotModal" class="modal fade" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title text-center">Edit Group: {{ $vars['lot_name'] }}</h4>

			</div>

			<form class="form-horizontal text-center" role="form" method="POST" action="{{ url('/home/updateLot') }}">

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<div class="modal-body">

					

				</div>

				<div class="modal-footer">

					<button type="submit" class="btn btn-primary">

						Update

					</button>

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

				</div>

			</form>

		</div>

	</div>

</div>



<!-- Show meter report -->

<div id="get_meters_by_day" class="modal fade" role="dialog">

	<div class="modal-dialog">

		

		<div class="modal-content">



			<div class="modal-header">
				<div class="row">
                	
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"> <img src="{{asset('images/transaction_icon.png')}}" height="40px" /> </div>
					<div class="col-xs-8 col-sm-9 col-md-9 col-lg-9"><h4 class="text-centered"></h4> </div>
                    <div class="col-sm-1 col-md-1 col-lg-1"> <button type="button" class="close" data-dismiss="modal">&times;</button> </div>
				</div>
			</div>



			 <div class="modal-body" style="max-height:none;">



			 </div>

			

		</div>

	</div>

</div>



<!-- Delete Lot Modal -->

<div id="deleteLotModal" class="modal fade" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title text-center">Delete Group: <span class="name">{{ $vars['lot_name'] }}</span></h4>

			</div>

			<div class="modal-body">

				All the data/meters related to this group will be deleted.<br />

				Are you sure you want to delete this group?

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-danger" id="delteGroup">Delete</button>

				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

			</div>

		</div>

	</div>

</div>



<!-- Delete Lot Modal -->

<div id="deleteMeterGroup" class="modal fade" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title text-center">Delete Meter(s)</h4>

			</div>

			<div class="modal-error-body text-center"></div>

			<div class="modal-body text-center">

				Selected Meter(s) <div class="meter-name text-center"></div>

				<br>

				Are you sure you want to delete?

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-danger" id="deleteMeter" >Delete</button>

				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

			</div>

		</div>

	</div>

</div>



<!-- New Meter -->

@if( count($mylots) )

    <div id="newMeterModal" class="modal fade" role="dialog">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title text-center">How many Meters would you like to add?</h4>

                    <center><h6><strong>*NOTE:</strong> There is a one-time <strong>${{ number_format(config("meter_base_price"),2) }}</strong> fee for each new meter you create.</h6></center>

                </div>

                <div class="row">

                <div class="col-md-6 center_col">

                <form class="form-horizontal text-center newMeter-form" role="form" method="POST" action="{{ url('/home/newmeter') }}">

                    <div class="modal-body">

                        

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        

                        <div class="form-group">

                            

                            <div class="col-md-12 text-left to-text">

                                @if( $mylots->count() == 1 )

                                	<input type="hidden" name="lot_id" value="{{ $mylots[0]->id }}">

                                @else

                                    <select name="lot_id" class="form-control centered-select" required="required" >

                                        <option value="">Select Group</option>

                                        @foreach( $mylots as $lot )

                                            <option value="{{ $lot->id }}">{{ $lot->lot_name }}</option>

                                        @endforeach

                                    </select>

                                @endif

                            </div>

                        </div>

                        

                        <?php /*?><div class="form-group">

                            

                            <div class="col-md-12 text-left to-text">

                            </div>

                        </div><?php */?>

                        

                        

                        <div class="form-group">

                            

                            <div class="col-md-12">

                                <input type="text" class="form-control text-center" id="meter_count" name="meter_count" value="" required placeholder="No. of Meters (Default:1)" min="1" placeholder="No. of Meters" max="999" pattern="[0-9]{3}" >

                            </div>

                            <?php /*?><div class="col-md-3 text-center to-text ">

                                * ${{ number_format(config("meter_base_price"),2) }}

                            </div><?php */?>

                            <div class="clearfix"></div>

                            <br />

                            <div class="col-md-12 tota_amount to-text" id="total_amount_paid">

                                 Total : <span class="price">${{ number_format($vars["base_price"],2) }}</span>

                                 @if( $vars["promo_code_discount"] > 0 )

                                 	&nbsp;

                                 	<span style="color:red;">

                                 		(-{{ number_format($vars["promo_code_discount"],0) }}% )

                                 	</span>

                                 @endif

                            </div>			

                        </div>

    

                        

                        

                        <div id="paypal_form" @if (count($errors) == 0) style="display:none;" @endif>

                                    <?php /*?><div class="form-group">

                                        <h3 class="text-center">Payment Information</h3>

                                    </div><?php */?>

                                    <div class="form-group" >

                                        <label class="col-md-12 control-lable"></label>

                                        <div class="col-md-12 text-left"><img src="{{ asset('/images/card_types.png') }}" class="cart_types_img margin_none" border="0" /></div>

                                    </div>

                                    <div class="form-group" >

                                        

                                        <div class="col-md-12">

                                            <input type="text" class="form-control text-center" name="cc_number" value="{{ old('cc_number') }}" placeholder="Card Number" required >

                                        </div>

                                    </div>

                                    

                                    <div class="form-group" >

                                        

                                    

                                            <div class="col-md-12">

                                                <select name="expiry_month" class="form-control centered-select" required="required" >

                                                <option value="">Expiry Month</option>

                                                    @for ($i=1; $i<=12;$i++)

                                                    <option @if (old('expiry_month') == $i) selected="selected" @endif>{{ $i}}</option>

                                                    @endfor

                                                </select>

                                            </div>

                                          <div class="clearfix"></div>

                            <br />

                                            <div class="col-md-12  padd_right0">

                                                <select name="expiry_year" class="form-control centered-select" required="required" >

                                                 <option value="">Expiry Year</option>

                                                    @for ($i=2016; $i<=2030;$i++)

                                                    <option @if (old('expiry_year') == $i) selected="selected" @endif>{{ $i}}</option>

                                                    @endfor

                                                </select>

                                                

                                            </div>

                                        

                                    </div>

                                </div>

                        

                        

                        

                        <div class="clearfix"></div>

                    

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-primary btn-block" id="proceed_to_checkout">

                            Pay Now  <i class="fa fa-credit-card" aria-hidden="true"></i>

                        </button>

                    </div>

                </form>

    </div>            </div>

            </div>

        </div>

    </div>

@endif



<!-- Edit Lot Modal -->

<div id="changeMeterGroup" class="modal fade" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title text-center">Change Group</h4>

			</div>

			<form class="form-horizontal text-center updateMeterGroup-form" role="form" method="POST" action="{{ url('/home/updateMeterGroup') }}">

				<div class="modal-error-body"></div>

				<div class="modal-body">

					Selected Meter(s): <div class="meter-name text-center"></div>



					<br>



					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<input type="hidden" name="meter_id" value="">

					<div class="form-group">

						<label class="col-md-4 control-label">Select Group</label>

						<div class="col-md-6">

							<select class="form-control" name="lot_id" placeholder="Group">

								@foreach( $mylots as $lot )

									<option value="{{ $lot->id }}" @if( $lot->id == $vars['lot_id']) selected="selected" @endif  >{{ $lot->lot_name }}</option>

								@endforeach

							</select>

						</div>

					</div>

					

					<div class="clearfix"></div>

				</div>

				<div class="modal-footer">

					<button type="submit" class="btn btn-primary" id="changeGroupButton" >Submit</button>

					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

				</div>

			</form>

		</div>

	</div>

</div>





<style>

	
	.displayclass{

		display:block !important;

	}

	body #paypal_form{

		padding: 0px 0px;

	}

	body .newMeter-form .form-group{

		padding-top: 5px;

	}

</style>



<script>var current_lot_id = {{ $vars['lot_id'] }};  var meter_base_price = {{ $vars["base_price"] }}; var config_meter_base_price = {{ config("meter_base_price") }}; var promo_code_discount = {{ config("promo_code_discount") }}; </script>



<script type="text/javascript">

	function open_report_modal(meter_id,report_type){

		var start_date = $("input[name='start_date']").val();
		/*if(($("#filter-transactions input[name='start_date']").length == 1 && $("#filter-transactions input[name='start_date']").val() != "") ){
			
				start_date = $("#filter-transactions input[name='start_date']").val();
			
		}
*/
		var end_date = $("input[name='end_date']").val();
		/*if(($("#filter-transactions input[name='end_date']").length == 1 && $("#filter-transactions input[name='end_date']").val() != "")){
			if(($("#filter-transactions input[name='end_date']").length == 1) && ($("#filter-transactions input[name='end_date']").val() != "")){
				end_date = $("#filter-transactions input[name='end_date']").val();
			}
		}*/

		$("#get_meters_by_day").modal("show");
		$("#get_meters_by_day .modal-header h4").html("Daily transactions for Meter# "+meter_id); //Showing details for

		showLoader();

		$.post("{{ URL::to('/home/get_meters_by_day') }}", { meter_id : meter_id, start_date: start_date, end_date: end_date, report_type: report_type }, function(data){

			$("#get_meters_by_day .modal-body").html(data);

			hideLoader();

		});

	}



</script>



@endif

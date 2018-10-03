<?php //echo "<pre>"; print_r($variable_rates); echo "</pre>"; 

$rates_info = array();

$color_codes = array('#87d236','#92eb32','#5cff4e','#32eb5b','#32eb87','#32ebbf','#32d5eb','#32b3eb','#3292eb','#3255eb','#6432eb','#a032eb');
//'#96e63f','#56e63f','#4ce63f',
//,'#327ceb','#325beb','#c96c89','#c96c9a'
$count= 0;
foreach( $variable_rates as $variable_rate ){
	
	/* Skip variable rates that come and gone only with offer type special event and date range */
								
	$skip = 0;
	 if($variable_rate["offer_type"] == 6 || $variable_rate["offer_type"] == 7){
		 if($variable_rate["start_date"]<date('Y-m-d h:i:s')){
			 $skip = 1;
		 }
	 }
	if( $skip == 0  ){
		$rates_info[$count]['id'] 			= $variable_rate["id"];
		$rates_info[$count]['title'] 		= "$". $variable_rate["price"];
		$rates_info[$count]['price'] 		= $variable_rate["price"];
		
		if( $count>11 ){
			$rates_info[$count]['backgroundColor'] 	= $color_codes[$count%11];//$variable_rate["bg_color"];
		}else{
			$rates_info[$count]['backgroundColor'] 	= $color_codes[$count];
		}
		
		
		if($variable_rate["offer_type"] == 4){
			
			// Weekends
			//repeating events are only displayed if they are within one of the following ranges.
			
			$rates_info[$count]['dow'] 		= '[0,6]'; 
			
			$start = app("App\Http\Controllers\UtilsController")->formatTime($variable_rate["start_date"],'Y-m-d');
			
			$end = date('Y-m-d', strtotime('+20 years'));
			
			$rates_info[$count]['ranges'] 	= array(
												'0' =>
													array(
														'start'=>$start,
														'end'=>$end
													)
												);
			$rates_info[$count]['start'] 	= '12:00';
			$rates_info[$count]['end'] 	= '24:00';
			//$rates_info[$count]['allDay'] 	= true;
		
		}elseif($variable_rate["offer_type"] == 3){
			
			$rates_info[$count]['dow'] 		= '[1,2,3,4,5]'; 
			
			$start = app("App\Http\Controllers\UtilsController")->formatTime($variable_rate["start_date"],'Y-m-d');
			
			$end = date('Y-m-d', strtotime('+20 years'));
			
			$rates_info[$count]['ranges'] 	= array(
												'0' =>
													array(
														'start'=>$start,
														'end'=>$end
													)
												);
			$rates_info[$count]['start'] 	= app("App\Http\Controllers\UtilsController")->formatTime($variable_rate["start_date"],'H:i');
			$rates_info[$count]['end'] 		= app("App\Http\Controllers\UtilsController")->formatTime(date('Y-m-d', strtotime('+20 years'))." ".$variable_rate["end_time"],'H:i');
			
		}
		else{
			
			
			$start_date = $variable_rate["start_date"];
			
			if($variable_rate["end_date"] != "0000-00-00 00:00:00"){
				$end_date = $variable_rate["end_date"];
			}else{
				$end_date = date('Y-m-d', strtotime('+20 years'))." ".$variable_rate["end_time"];
			}
			
			$rates_info[$count]['ranges'] 	= array(
												'0' =>
													array(
														'start'=>$start_date,
														'end'=>$end_date
													)
												);
			
			$rates_info[$count]['start'] 	= $start_date;
			$rates_info[$count]['end'] 		= $end_date ;
		
		}
		$count++;
	}
}

//echo "<pre>"; print_r($variable_rates); echo "</pre>";
//echo "<pre>"; print_r($rates_info); echo "</pre>"; exit;
//echo json_encode($rates_info);
$data_ = json_encode($rates_info); 

?>
<div class="container-fluid">
  <div class="row">
    <div class="panel panel-default">
    	<form id="Rates_section" method="POST" action="{{ url('/home/qrCode') }}">
         
           
           <div class="form-group panel_groups">
              <div class="row">
                <div class="col-md-4 col-lg-4">
                  <select name="lot_id" class="form-control groups_name" >
                    <?php 
						$price 			= ( (isset($mylots[0]->price) ? $mylots[0]->price : '' ) ) ;
						$hr_daily   	= ( (isset($mylots[0]->hr_daily) ? $mylots[0]->hr_daily : '' ) ) ;
						$lot_selected 	= ( (isset($mylots[0]->id) ? $mylots[0]->id : '' ) ) ;
					?>
                    
                    @foreach( $mylots as $key => $lot  )
                        
                        
                        <option value="{{ $lot ->id }}">{{ $lot ->lot_name }}</option>

                         
                    @endforeach          
                                              
                  </select>
                </div>
              </div>
            </div>
      		<div class="panel-body">
            @if( $vars['tab'] === 'qrCode' && Session::has('success'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ Session::get('success') }}
                </div>
            @endif
        
           
          		<div class="col-md-4 col-lg-4 rates_panel">
            
            <h2>Rates:</h2>
             <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="form-group">
              <?php 
			  //echo'<pre>'; print_r($price);echo'</pre>'; ?>
              <label>Default Rate:</label>
              <br />
              <div class="row">
                <div class="col-lg-6">
                  <?php 
									
						$values = array('2.00', '2.50', '3.00', '3.50', '4.00', '4.50', '5.00', '5.50', '6.00', '6.50', '7.00', '7.50', '8.00', '8.50', '9.00', '9.50');
						$selected = "";
					
					//echo '<pre>'.$price; print_r($values);echo'</pre>';
					
					?>
                  <select name="default_rate" class="form-control change">
                    
                                   
                    @foreach( $values as $values_all  )
                        
                        @if( $price === $values_all )
                                        	
                    		<?php $selected = " selected='selected' "; ?>
                    
                        @else
                                        	
                    		<?php $selected = ""; ?>
                    
                       @endif
                                        
                                        
                    	<option  value="{{ $values_all }}" <?php echo $selected; ?>>${{ $values_all}}</option>
                    
                    @endforeach
                                      
                                     
                  </select>
                </div>
                <div class="col-lg-6">
                  <select name="hr_daily" class="form-control">
                    <option value="0" <?php echo ( !empty($hr_daily)&& ($hr_daily == 0)?'selected':'' ) ?>>Hourly</option>
                    <option value="1" <?php echo ( !empty($hr_daily)&& ($hr_daily == 1)?'selected':'' ) ?>>Daily</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="variable_rates"> 
              	
                <div align="center" class="fee_loader" style="display:none;">
                	<img src="{{ asset('/images/green_loader.gif') }}" height="40px" width="40px" />
                </div>
                
                <div class="rates_not_found" style="display:none;">
                	Data Not Found.
                </div>
                
                <div class="variable_rates_groups panel-group" id="accordion">
                	
					<?php //print_r($variable_rates); 
                        $variable_rate_count = 1;
                        $data_type = array("","","timepicker","","timepicker","date_time_picker","date_time_picker");
                        if( isset($variable_rates) and count($variable_rates)>0 ){
                            foreach( $variable_rates as $rates )
                            {
								/* Skip variable rates that come and gone only with offer type special event and date range */
								
								$skip = 0;
								 if($rates["offer_type"] == 6 || $rates["offer_type"] == 7){
									 if($rates["start_date"]<date('Y-m-d h:i:s')){
										 $skip = 1;
									 }
								 }
								 
								 if( $skip == 0 ){
									 
								 
								
									$start_date = ( isset($rates["start_date"])&& $rates["start_date"]!= "0000-00-00 00:00:00" ?  $rates["start_date"] : '');
									$end_date   = ( isset($rates["end_date"])&& $rates["end_date"]!= "0000-00-00 00:00:00" ?  $rates["end_date"] : '');
									
									$bg_color	= ( (isset($rates["bg_color"]) && !empty($rates["bg_color"]))?$rates["bg_color"]:'87D236' );
									/**************************** Re-Format Start and End date ******************************/
									
									if($start_date != ""){
										$start_date = app("App\Http\Controllers\UtilsController")->formatDate($rates["start_date"],'m/d/Y h:i A');
									}
									if($end_date != ""){
										$end_date = app("App\Http\Controllers\UtilsController")->formatDate($rates["end_date"],'m/d/Y h:i A');
									}
						
									
									if( $rates["lot_id"] == $lot_selected ){
										$hide_date_time = "";	
										$hide_time = "";	
										
										if($rates["offer_type"] == 3 || $rates["offer_type"] == 5){
											$hide_date_time = " style=display:none;";		
										}elseif($rates["offer_type"] == 6 || $rates["offer_type"] == 7){
											$hide_time = " style=display:none;";	
										}elseif($rates["offer_type"] == 4){
											$hide_date_time = " style=display:none;";	
											$hide_time = " style=display:none;";	
										}
										
										if( $rates["lot_id"] == $lot_selected ){
											$prices = array('2.00', '2.50', '3.00', '3.50', '4.00', '4.50', '5.00', '5.50', '6.00', '6.50', '7.00', '7.50', '8.00', '8.50', '9.00', '9.50');	
										}
										if($variable_rate_count>12){
											$background_color = $color_codes[$variable_rate_count%12];
										}else{										
											$background_color = $color_codes[$variable_rate_count-1];
										}
									?>
									
										<div class="panel panel-default variable_rate_panel" id="variable_rate_<?php echo $variable_rate_count; ?>">
											
											<div class="panel-heading" style="background-color:<?php echo $background_color; ?>">
											  <h4 class="panel-title">
												<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $variable_rate_count; ?>">
												  <i class="fa fa-caret-right"></i> Variable Rate <?php echo $variable_rate_count; ?>: 
												</a>
												<img src="https://my-meter.com/dev/public/images/panel_close1.png" class="variable_rate_close" data-attr="<?php echo $rates["id"]; ?>">
											  </h4>
											</div>
											<div id="collapse<?php echo $variable_rate_count; ?>" class="panel-collapse collapse">
													<div class="panel-body">
														<input type="hidden" name="variable_rates[rate_id][]" value="<?php echo $rates["id"]; ?>" /> 
												
														<div class="form-group">
														  
														  <div class="row">
															<div class="col-lg-6">
															  <select name="variable_rates[price][]" class="form-control">
																<?php
																foreach( $prices as $price ):
																	$selected = "";
																	if( $price ==  $rates["price"]){
																		$selected = " selected='selected'";	
																	}
																	echo $html = '<option value="'.$price.'"'. $selected.'>$'.$price.'</option>';
																   
																endforeach;
																?>
															   
															  </select>
															</div>
															<div class="col-lg-6">
															  <select name="variable_rates[hr_flat][]" class="form-control">
																 <?php
																  for($i=0;$i<=1;$i++){
																	$selected = "";
																	if( $variable_rates_meta[$i]->id ==  $rates["hr_flat"] ){
																		$selected = " selected='selected'";	
																	}
																	echo $html = '<option value="'.$variable_rates_meta[$i]->id.'" '.$selected.'>'.$variable_rates_meta[$i]->meta_key.'</option>';
																  }
																  ?>
															  </select>
															</div>
														  </div>
														</div>
														
														<div class="form-group">
														  <div class="row">
															<div class="col-lg-12">
															  <select name="variable_rates[offer_type][]" class="form-control offer_type">
															  <?php
																  
																  for($i=2;$i<=6;$i++){
																	  $selected = "";
																	  if( $variable_rates_meta[$i]->id == $rates["offer_type"] ){
																		   $selected = ' selected="selected" ';
																	  }
																		  
																	  echo $html = '<option data-type="'.$data_type[$i].'" value="'.$variable_rates_meta[$i]->id.'"'.$selected.'>'.$variable_rates_meta[$i]->meta_key.'</option>';
																	  
																  }
																?>
																
															  </select>
															</div>
														  </div>
														</div>
														
														<div class="form-group time_fields" <?php echo $hide_time; ?>>
														  <div class="row">
															<div class="col-lg-6">
															  <input type="text" class="form-control timepicker defaultstart_time" name="variable_rates[start_time][]" placeholder="Start Time" value="<?php echo ( isset( $rates['start_time'] )?$rates['start_time']:'' ); ?>" />
															</div>
															<div class="col-lg-6">
															  <input type="text" class="form-control timepicker defaultend_time" name="variable_rates[end_time][]" placeholder="End Time" value="<?php echo ( isset( $rates["end_time"] )?$rates["end_time"]:'' ); ?>" />
															</div>
														  </div>
														</div>  
														
														<div class="form-group date_fields" <?php echo $hide_date_time; ?>>
														  <div class="row">
															<div class="col-lg-12">
															  <input type="text" class="form-control date_time_picker start_time" name="variable_rates[start_date][]" placeholder="Start Time" value="<?php echo $start_date; ?>" />
															</div>
														  </div>
														</div>
														
														<div class="form-group date_fields" <?php echo $hide_date_time; ?>>
														  <div class="row">
															<div class="col-lg-12">
															  <input type="text" class="form-control date_time_picker end_time" name="variable_rates[end_date][]" placeholder="End Time" value="<?php echo $end_date; ?>" />
															</div>
														  </div>
														</div>
														<!--
														<div class="form-group">
															<div class="row">
																<div class="col-lg-12">
																	
																	<input type="text" value="<?php //echo $bg_color; ?>" name="variable_rates[bg_color][]" class="colorpicker form-control" style="position:inherit;">
																	
																</div>	
															</div>
														</div>
													   -->
													</div>
												</div>    
											 
										</div>
									<?php
									}
									$variable_rate_count++;
									
								 }
                            }
                        
                        }
                    ?>
                    
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <div class="row">
                <div class="col-lg-offset-6 col-lg-6">
                  <button type="submit" class="panel_save_btn btn btn-primary form-control">Save</button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="margin_top_10" style="text-align:center;font-size: 15px;"> <a href="#" class="add_variable_rate"> <i class="fa fa-plus" aria-hidden="true"></i> Add Variable Rate </a> </div>
              </div>
            </div>
          </div>
       
          	<div class="col-md-8 col-lg-8">
              <div id='calendar'></div>
            </div>
            
           	</div>
        </form>
    </div>
  </div>
</div>

<div class="clon" style="display: none;">
  <div class="panel panel-default variable_rate_panel">
  
                                                
        <div class="panel-heading" style="background-color:#87d236;">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href=""> <?php /* #collapse1 */ ?>
                  <i class="fa fa-caret-right"></i> Variable Rate : <?php // echo $variable_rate_count; ?>
                </a>
                <img src="https://my-meter.com/dev/public/images/panel_close1.png" class="variable_rate_close" data-attr=""> <?php // echo $rates["id"]; ?>
            </h4>
        </div>
        
        <div id="collapse" class="panel-collapse collapse">
        	<div class="panel-body">
                <input type="hidden" name="rate_id" value="" />
                
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-6">
                      <select name="variable_rates[price][]" class="form-control">
                        <option value="2.00" selected="selected">$2.00</option>
                        <option value="2.50">$2.50</option>
                        <option value="3.00">$3.00</option>
                        <option value="3.50">$3.50</option>
                        <option value="4.00">$4.00</option>
                        <option value="4.50">$4.50</option>
                        <option value="5.00">$5.00</option>
                        <option value="5.50">$5.50</option>
                        <option value="6.00">$6.00</option>
                        <option value="6.50">$6.50</option>
                        <option value="7.00">$7.00</option>
                        <option value="7.50">$7.50</option>
                        <option value="8.00">$8.00</option>
                        <option value="8.50">$8.50</option>
                        <option value="9.00">$9.00</option>
                        <option value="9.50">$9.50</option>
                      </select>
                    </div>
                    <div class="col-lg-6">
                      <select name="variable_rates[hr_flat][]" class="form-control">
                        <option value="1">Hourly</option>
                        <option value="2">Flat</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-12">
                      <select name="variable_rates[offer_type][]" class="form-control offer_type">
                        <option data-type="timepicker" value="3" selected="selected">Evenings</option>
                        <option data-type="" value="4">Weekends</option>
                        <option data-type="timepicker" value="5">Every Day</option>
                        <option data-type="date_time_picker" value="6">Special Event</option>
                        <option data-type="date_time_picker" value="7">Date Range</option>
                      </select>
                    </div> 
                  </div>
                </div>
                <div class="form-group time_fields">
                  <div class="row">
                    <div class="col-lg-6">
                      <input type="text" class="form-control timepicker defaultstart_time" name="variable_rates[start_time][]" placeholder="Start Time" />
                    </div>
                    <div class="col-lg-6">
                      <input type="text" class="form-control timepicker defaultend_time" name="variable_rates[end_time][]" placeholder="End Time" />
                    </div>
                  </div>
                </div>
                <div class="form-group date_fields" style="display:none;">
                  <div class="row">
                    <div class="col-lg-12">
                      <input type="text" class="form-control date_time_picker start_time" name="variable_rates[start_date][]" placeholder="Start Time" />
                    </div>
                  </div>
                </div>
                <div class="form-group date_fields" style="display:none;">
                  <div class="row">
                    <div class="col-lg-12">
                      <input type="text" class="form-control date_time_picker end_time" name="variable_rates[end_date][]" placeholder="End Time" />
                    </div>
                  </div>
                </div>
                <!--<div class="form-group">
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="text" value="#87d236" name="variable_rates[bg_color][]" class="colorpicker form-control" style="position:inherit;">
                        </div>	
                    </div>
                </div>
                -->
            </div>
        </div>
     
  </div>                                   
</div>

@section('additionalJS')

	<script src="{{ asset('/js/color-picker/bootstrap-colorpicker.js') }}"></script>
    
	<script>
		
		
		
		var renderedPrice = "<?php if(isset($mylots[0]->price)){ echo number_format($mylots[0]->price,2,'.',''); } if(isset($mylots[0]->hr_daily) && $mylots[0]->hr_daily == 0){ echo " / Hr";}else{ echo " / Day"; } ?>";
		 $(document).ready(function(){
			$('.date_time_picker').datetimepicker({
				format: "mm/dd/yyyy HH:ii P",
				showMeridian: true,
				autoclose: true,
				todayBtn: true,
				minuteStep: 10,
				
			});
				 
			$('.start_time').datetimepicker().on('changeDate', function(ev){
 
				//alert(ev.date);
				$(this).parents('.variable_rate_panel').find('.end_time').datetimepicker('setStartDate', ev.date);
			
			});
			$('.end_time').datetimepicker().on('changeDate', function(ev){
 
			   //alert(ev.date);
			   $(this).parents('.variable_rate_panel').find('.start_time').datetimepicker('setEndDate', ev.date);
			
			}); 
			 /*
			 var tooltip = $('<div/>').qtip({
				id: 'calendar',
				prerender: true,
				content: {
					text: ' ',
					title: {
						button: true
					}
				},
				position: {
					my: 'bottom center',
					at: 'top center',
					target: 'mouse',
					viewport: $('#calendar'),
					adjust: {
						mouse: false,
						scroll: false
					}
				},
				show: false,
				hide: false,
				style: 'qtip-light'
			}).qtip('api');*/
	
			 /********* close ********/
			  $('.fragment i').on('click', function(e) { $('.variable_rate_panel').closest('.variable_rate_panel').remove(); });
		
			if( $('#calendar').length > 0 ){
				
				$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
					
					$('#calendar').fullCalendar('render');
				});
				
				$('#calendar').fullCalendar({
					header: 
					{
						left: 'title',
						center: 'prev,next',
						right: ''
					},
					displayEventEnd : true,
					editable: true,
					eventLimit: true, 
					defaultView: 'month',
					timeFormat: 'hh:mm a',
					render: function(){
						console.log('rendering');
					},
					eventMouseover: function(data, event, view) {
						
						var event_start = moment(data.start, 'DD.MM.YYYY').format('YYYY-MM-DD  hh:mm a');
						var event_end = moment(data.end, 'DD.MM.YYYY').format('YYYY-MM-DD  hh:mm a');
						
						/*var content = '<h3>'+data.title+'</h3>' + 
							'<p><b>Start:</b> '+event_start+'<br />' + 
							(data.end && '<p><b>End:</b> '+event_end+'</p>' || '');
			
						tooltip.set({
							'content.text': content
						})
						.reposition(event).show(event);*/
						
						tooltip = '<div class="tooltiptopicevent" style="width:auto;height:auto;background:#eee;border:1px solid #ddd;position:absolute;z-index:10001;padding:10px 10px 10px 10px ;  line-height: 200%;">' + '<b>' + '' + data.title + '</b>' + '<p style="margin:0;"><b>Start: </b>' + '' + event_start + '</p><p style="margin:0;"><b>End: </b>'+event_end+'</p></div>';
						
						$("body").append(tooltip);
						$(this).mouseover(function (e) {
							$(this).css('z-index', 10000);
							$('.tooltiptopicevent').fadeIn('500');
							$('.tooltiptopicevent').fadeTo('10', 1.9);
						}).mousemove(function (e) {
							$('.tooltiptopicevent').css('top', e.pageY + 10);
							$('.tooltiptopicevent').css('left', e.pageX + 20);
						});
               
					},
					 eventMouseout: function (data, event, view) {
						$(this).css('z-index', 8);
			
						$('.tooltiptopicevent').remove();
			
					},
					eventRender: function(event, element, view){
						//console.log('welcome');
						//console.log(element);
						var eventStart = moment(event.start);
						var eventEnd = event._end === null ? eventStart : moment(event.end);
						var diffInDays = eventEnd.diff(eventStart, 'days');
						
						//console.log(eventStart);
						//console.log(eventEnd);
						//console.log(diffInDays);
						
						/* Restrict default rate to appear before the scheduled date */
						return (event.ranges.filter(function(range){
							return (event.start.isBefore(range.end) &&
									event.end.isAfter(range.start));
						}).length)>0;
					},
					events: 
					
					<?php echo $data_ ; ?>
					,
				   	dayRender: function(date, cell) {
						cell.append('<div class="default_price">$ ' + renderedPrice + '</div>'); 
					},eventColor: '#87D236'
				})
			}
			
			
	
		 
		/*fetch group price */
		$('select[name="lot_id"]').change(function(){
            var lot_id = $(this).val();
			var price = ['2.00', '2.50', '3.00', '3.50', '4.00', '4.50', '5.00', '5.50', '6.00', '6.50', '7.00', '7.50', '8.00', '8.50', '9.00', '9.50'];
			
			$('.variable_rates .variable_rates_groups').html('');
			$('.variable_rates .rates_not_found').hide();
			$('.variable_rates .fee_loader').show();
			
			setTimeout( function() {
        			$.ajax({
						type: 'POST',
						url: "{{ asset('home/groups_name') }}",
						data: {id: lot_id},
						success: function (data) {
							
							$('.variable_rates .fee_loader').hide();
							$('.variable_rates .rates_not_found').hide();
							
							console.log(data);
							
							/** Fetch price and variable rates set for the selected group  **/
							$('select[name=default_rate]').val(data["price"]);
							$('select[name=hr_daily]').val(data["hr_daily"]);
							var hr_daily = "";
							if(data["hr_daily"] == 0){
								 hr_daily = "/ Hr";
							}else{
								 hr_daily = "/ Day";
							}
							renderedPrice = data["price"];
							$('.default_price').text("$"+data["price"]+hr_daily);
							if(data["variable_rates"] == ''){	
								$('.variable_rates .variable_rates_groups').html('');
								$('.variable_rates .rates_not_found').show();
							}else{
								$('.variable_rates .variable_rates_groups').html(data["variable_rates"]);
																
								$('.variable_rate_panel .defaultstart_time').timepicker();
								$('.variable_rate_panel .defaultend_time').timepicker();
								var date=$('.variable_rate_panel .date_time_picker').datetimepicker({format: "mm/dd/yyyy HH:ii P",showMeridian: true,autoclose: true,todayBtn: true,minuteStep: 10,});
								$('.start_time').datetimepicker().on('changeDate', function(ev){
								
									//alert(ev.date);
									$(this).parents('.variable_rate_panel').find('.end_time').datetimepicker('setStartDate', ev.date);
								
								});
								$('.end_time').datetimepicker().on('changeDate', function(ev){
								
								   //alert(ev.date);
								   $(this).parents('.variable_rate_panel').find('.start_time').datetimepicker('setEndDate', ev.date);
								
								}); 

		
							}
						} 
				 });
    		}, 2000 );
            
        });
			
				 
			
	});
				
	</script>
    
@endsection
    
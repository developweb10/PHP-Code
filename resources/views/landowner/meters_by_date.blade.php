<?php

	if(!isset($mobile) || !isset($Ipad)){
		$mobile = config('is_mobile');
		$Ipad = config('is_Ipad');
	}

	$start_date = "";
	$end_date = "";
	
?>
@if(isset($inputs["start_date"]) && !empty($inputs["start_date"]))
	<?php  $start_date = $inputs["start_date"]; ?>
@endif

@if(isset($inputs["end_date"]) && !empty($inputs["end_date"]))
    <?php  $end_date = $inputs["end_date"]; ?>
@endif
                
@if((isset($export) && $export === '') || (!isset($export)))
    <div class="text-center">
    
        <form role="form" class="form-inline filter-form" id="filter-transactions" action="{{ URL::to('/home') }}" style="padding: 0px 10px 10px;">
            
            <input type="hidden" name="export" id="export_transactions" value="">
            
            <input type="hidden" name="meter_id" id="meter_id" value="@if(isset($inputs['meter_id'])) {{ $inputs['meter_id'] }} @endif">
    
            <fieldset>
                <div class="row">
    
                <span class="to-text"><b>Filter Results</b></span>
    
                &nbsp;&nbsp;&nbsp;&nbsp;
                <!--
                <select class="form-control" name="last_days">
                    <option value="0"> By Last days </option>
                    <option value="today - 7 days" @if( isset($inputs['last_days']) && $inputs['last_days'] === "today - 7 days" ) selected = 'selected' @endif> 7 days </option>
                    <option value="today - 14 days" @if( isset($inputs['last_days']) && $inputs['last_days'] === "today - 14 days" ) selected = 'selected' @endif> 14 days</option>
                    <option value="today - 30 days" @if( isset($inputs['last_days']) && $inputs['last_days'] === "today - 30 days" ) selected = 'selected' @endif> 30 days</option>
                </select>
            -->
                <!-- 
                <input type="text" class="form-control from width_20" placeholder="By Month" >
            -->
                
                <input type="text" class="form-control datepicker width_20" name="start_date" value='{{ $start_date }}' placeholder="Start Date" @if (($Ipad) || $mobile) readonly  @endif >
                <input type="text" class="form-control datepicker width_20" name="end_date" value='{{ $end_date }}' placeholder="End Date" @if (($Ipad) || $mobile) readonly  @endif >
                <input type="button" class="btn btn-default" value="Search">
                @if( (isset($inputs["start_date"]) && !empty($inputs["start_date"])) or (isset($inputs["end_date"]) && !empty($inputs["end_date"])) ) 
                	<input type="reset" class="btn btn-default" value="Clear">
                @endif
    
                </div>
             </fieldset>
    
        </form>
    
    </div>

@endif
@if((isset($export) && $export === 'transaction_pdf') )
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
    <table class="ouser_cont" style="max-width:650px; width:650px; margin:0 auto;" > 
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
        @if( $start_date !== '' || $end_date !== '' )
            <tr> 
                <td align="center" colspan="3">
                    <h3>Report ( @if( $start_date !== '' ) From: {{ $start_date }} @endif  @if( $end_date !== '' ) To: {{ $end_date }} @endif  ) </h3>
                </td>
            </tr>
        @endif
     </table>
     
@endif

<table id="meter_transactions" class="display" cellspacing="0" width="100%" @if(isset($export) && $export === 'transaction_pdf')  style="max-width:650px; width:650px; margin:0 auto; text-align:center"   @endif > 

	<thead>

		<tr style="background-color: gray;color: #fff;">

			<th class="text-center">

				@if( isset($inputs['report_type']) && $inputs['report_type'] === "month" ) Month @else Day @endif

			</th>

			<th class="text-center"># of Transactions</th>

			<th class="text-center">Total Hours</th>

			<th class="text-center">Revenue</th>

		</tr>

	</thead>

	<tbody>
    	
		
		@if(!isset($inputs["failure"]))

			<?php $totals = array("count"=>count($meter_details),"revenue"=>0.00,"transactions"=>0.00,"hours"=>0,"net_revenue"=>0.00); $ii = 0; ?>

			@if( (isset($inputs["start_date"]) && !empty($inputs["start_date"])) or (isset($inputs["end_date"]) && !empty($inputs["end_date"])) ) 
				
				@foreach( $meter_details as $meter )
					
                    
					<?php
                    	for($i=0; $i<count($meter); $i++){
					?>
                    		
                    
                            <tr 
        
                                @if($ii % 2 == 0) 	
        
                                    style="background-color: #f9f9f9; page-break-after: always;" 
        
                                @else 
        
                                    style="background-color: #ffffff; page-break-after: always;" 
        
                                @endif  
        
                            >
        
                                <td>
        
                                    @if( isset($inputs["report_type"]) && $inputs['report_type'] === "month" ) 
        
                                        {{ date('F',strtotime($meter[$i]["trans_day"])) }} ( {{ date('m/Y',strtotime($meter[$i]["trans_day"])) }} )
        
                                    @else
        
                                        {{ date('l',strtotime($meter[$i]["trans_day"])) }}, {{ date('M d',strtotime($meter[$i]["trans_day"])) }} 
        
                                    @endif
        
                                </td>
        
                                <td class="text-center">{{ $meter[$i]["transactions"] }}</td>
        
                                <td class="text-center">{{ number_format(($meter[$i]["hours"] > 0) ? $meter[$i]["hours"] : 0,2) }}</td>
        
                                <?php //$net_revenue =  ( $meter[$i]["trans_amount"] * 80 )/100;  ?>
        
                                <td class="text-center">$ {{ number_format($meter[$i]["trans_amount"],2) }}</td>
        
                            </tr>
        
                            <?php 
        
                                $totals["revenue"] += $meter[$i]["trans_amount"];  $totals["transactions"] += $meter[$i]["transactions"]; $totals["hours"] += $meter[$i]["hours"]; $totals["net_revenue"] += $meter[$i]["trans_amount"]; 
        
                                $ii++;
        
                            ?>
                    <?php
						}
					?>

				@endforeach

			@else
				<?php $i = 0;  ?>
              
				@foreach( $meter_details as $meter ) 
                	
                   
                    
                	<tr 
    
                        @if($ii % 2 == 0) 	

                            style="background-color: #f9f9f9; page-break-after: always;" 

                        @else 

                            style="background-color: #ffffff; page-break-after: always;" 

                        @endif  

                    >
                        
                	<?php $i++; 
						
					?>
					
                    @if(count($meter))
                    	
							<?php //echo date('Y-m')."-".$i." >= ".date('Y-m-d',strtotime($meter->trans_day)); ?>
                            <td>
    
                                @if( isset($inputs['report_type']) && $inputs['report_type'] === "month" ) 
    
                                    {{ date('F',strtotime($meter[0]["trans_day"])) }} ( {{ date('m/Y',strtotime($meter[0]["trans_day"])) }} )
    
                                @else
    
                                    {{ date('l',strtotime(date('Y-m')."-".$i)) }}, {{ date('M d',strtotime(date('Y-m')."-".$i)) }} 
    
                                @endif
    
                            </td>
    
                            <td class="text-center">{{ $meter[0]["transactions"] }}</td> <?php /*  */ ?>
    
                            <td class="text-center">{{ number_format(($meter[0]["hours"] > 0) ? $meter[0]["hours"] : 0,2) }}</td>
    
                            <?php // $net_revenue =  ( $meter[0]["trans_amount"] * 80 )/100;  ?>
    
                            <td class="text-center">$ {{ number_format($meter[0]["trans_amount"],2) }}</td>
    
                            <?php 
    
                                $totals["revenue"] += $meter[0]["trans_amount"];  $totals["transactions"] += $meter[0]["transactions"]; $totals["hours"] += $meter[0]["hours"]; $totals["net_revenue"] += $meter[0]["trans_amount"]; 
    
    
                            ?>

					   
                 	@else
                    	
                 	   <td>

                            @if( isset($inputs['report_type']) && $inputs['report_type'] === "month" ) 

                                {{ date('F',strtotime($meter[0]["trans_day"])) }} ( {{ date('m/Y',strtotime($meter[0]["trans_day"])) }} )

                            @else

                                {{ date('l',strtotime(date('Y-m')."-".$i)) }}, {{ date('M d',strtotime(date('Y-m')."-".$i)) }} 

                            @endif

                        </td>

                        <td class="text-center">0</td>

                        <td class="text-center">0</td>

                        <td class="text-center">0</td>	
                    @endif  
                  </tr>
			    @endforeach
				 	
				
			@endif

			


		@else

			<tr> <td align="center"> No result Found </td> </tr>
		@endif
	</tbody>
    @if(!isset($inputs["failure"]))
	    <tfoot>
			
	        <tr style="page-break-after: always;background-color: #eee; font-weight: bold; ">

	            <td class="text-center" align="center">Total</td>

	            <td class="text-center" align="center">{{$totals["transactions"]}}</td>
	            
	            <td class="text-center" align="center">{{ number_format($totals["hours"],2) }}</td>

	            <td class="text-center" align="center">${{ number_format($totals["net_revenue"],2) }}</td>

	        </tr>

	    </tfoot>
                
    @endif
</table>
@if((isset($export) && $export === 'transaction_pdf') )

    </body>

    </html>

@endif
@if((isset($export) && $export === '') || (!isset($export)))
    <div class="text-right">
        <br />
        <div class="col-xs-6 col-md-3 col-lg-3 pull-right col-xs-offset-3"> 
            <input type="image" class="" src="{{asset('images/download_blue.png')}}"  value="PDF" onClick="document.getElementById('export_transactions').value='transaction_pdf';document.getElementById('filter-transactions').submit();document.getElementById('export_transactions').value='';" style="width:100%;" />
        </div> 
    </div>

@endif

	<script>
	
		if( $("#meter_transactions").length )  $("#meter_transactions").DataTable({	
			"searching": false,
			"scrollY": 250	,
			"paging":   false,
	        "ordering": false,	
			"oLanguage": {
				"sEmptyTable":"There are no transactions for these dates"
			},
		});
		
		
		$('input[type="reset"]').click(function(){
		
			form = $(this).parents("form");
	
			form.find("input[type='text'],select,hidden").val('');
			
			$("#filter-transactions input[type=button]").click();
	
		});
	
		$('.datepicker').datepicker({
	
			format: "yyyy-mm-dd",
	
			setDate: current_date,
	
			autoclose: true
	
	
	
		}).on('changeDate', function(e){
	
			$(this).datepicker('hide');
	
		});


	
		$('.from').datepicker({
			autoclose: true,
			minViewMode: 1,
			format: 'mm/yyyy'
		}).on('changeDate', function(selected){
				
			}); 

		$("#filter-transactions input[type=button]").click(function(){
			var meter_id  = $("#filter-transactions input[name=meter_id]").val();
			var start_date = $("#filter-transactions input[name=start_date]").val();
			var end_date = $("#filter-transactions input[name=end_date]").val();
			//var last_days = $("#filter-transactions select[name=last_days]").val();
			var report_type = "day";
			showLoader();
			// , last_days : last_days
			$.post(home_url+"/filter_transactions", { meter_id : meter_id , start_date : start_date , end_date : end_date , report_type: report_type }, function(response){
				//alert(response);
				$("#get_meters_by_day .modal-body").html(response);
				hideLoader();
				//$("#activation_steps #state_list").html(response);
				//$( "#cities" ).autocomplete({
				//  source: response
				//});
				//$("#get_meters_by_day .modal-body table.display").html(data);
			})
		})

		/*$("#filter-transactions input[type=button]").click(function(){
			var meter_id  = $("#filter-transactions input[name=meter_id]").val();
			var start_date = $("#filter-transactions input[name=start_date]").val();
			var end_date = $("#filter-transactions input[name=end_date]").val();
			var last_days = $("#filter-transactions select[name=last_days]").val();
			var report_type = "day";
			$.post("{{ URL::to('/home/get_meters_by_day') }}", { meter_id : meter_id, start_date: start_date, end_date: end_date, report_type: report_type }, function(data){
	
				$("#get_meters_by_day .modal-body").html(data);
	
				hideLoader();
	
			});
	
		});*/
		
		$('.dataTables_scrollBody').scroll(function () {
			//alert("test");
			
			$('.dataTables_scrollHead').scrollLeft($(this).scrollLeft());
			$('.dataTables_scrollFoot').scrollLeft($(this).scrollLeft());
		});

	</script>

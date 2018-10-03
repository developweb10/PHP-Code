	
    @if( $vars['export'] === '' ) 
    
        
        <!--<button class="btn btn-default btn-sm" data-toggle="modal" data-target="#newLotModal" >New Group +</button>-->
    
        <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#newMeterModal" >+ New Meter</button>
                        
        <!-- Filter Form -->
        <div class="text-center">
    
            <form role="form" class="form-inline filter-form" id="filter-form" action="{{ URL::to('/admin/client?id=') }}{{ $client['id'] }}">
    
                <input type="hidden" name="id" value="{{ $client['id'] }}">
                <input type="hidden" name="export" id="export" value="">
                
                <fieldset>
                     
                    <span class="to-text"><b>Filter Results</b></span>
    
                    <select class="form-control" name="group_id">
    
                        <option value="">Select Group (Default:All)</option>
                        
                        <!--- groups created by admin--->
                      
                        @if(isset($landowners_groups) && count($landowners_groups))
                            @foreach( $landowners_groups as $landowners_group )
                                {{$landowners_group}}
                               <option value="{{ $landowners_group['id'] }}" >{{ $landowners_group['group_name'] }}</option> 
    
                            @endforeach
                        @endif
                    </select>
    
                    &nbsp;&nbsp;&nbsp;&nbsp;
    
                    <input type="text" class="form-control datepicker1" name="start_date" value="" placeholder="Start Date" @if ((config('is_Ipad')) || config('is_mobile')) readonly  @endif /> <?php /* {{ $vars['start_date'] }} */ ?>
    
                    <input type="text" class="form-control datepicker1" name="end_date" value="" placeholder="End Date" @if ((config('is_Ipad')) || config('is_mobile')) readonly  @endif /> <?php /*{{ $vars['end_date'] }} */ ?>
                    <!--<select class="form-control" name="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>-->
                    
                    <input type="submit" class="btn btn-default"  value="Search" />
                    <?php /* @if( (isset($vars["start_date"]) && !empty($vars["start_date"])) or (isset($vars["end_date"]) && !empty($vars["end_date"])) ) 
                        <input type="reset" class="btn btn-default" value="Clear">
                    @endif */ ?>
                </fieldset>
    
            </form>
    
        </div>
    
        <br />
        
        <!-- Client Name -->
        <h5 style="text-transform:capitalize;  font-weight: 700; ">{{ $client['name'] }} Dashboard</h5>
                        
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
            
    @else
    
        @if( $vars['export'] === 'PDF' )
        
            <?php //  echo "<pre>";  print_r($vars); echo "</pre>"; // exit();?>	
    
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
        
        <div class="table-responsive">
        
                <table id="datatable" class="table display lot_meters" @if( $vars['export'] !== '' ) style="max-width:650px; width:650px; margin:0 auto; text-align:center"  @endif >
        
                <thead>
        
                    <tr style="background-color: gray;color: #fff;">
                       
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
                        
                        <th class="text-center" valign="middle">Meter #</th>
                        @if( $vars['export'] === '' )
                            <th class="text-center" valign="middle"><a href="#" data-toggle="modal" data-target="#share_incpt" style="color:#fff;"><img src="{{ asset('/images/live_feed.png') }}" border="0" class="live_feed" /></a></th>
                       @endif
                        <th class="text-center" valign="middle">$/hr.</th>
                        <th class="text-center" valign="middle">Transactions</th>
                        <th class="text-center" valign="middle">Total Hours</th>
                        <th class="text-center" valign="middle">Total Revenue</th>
                        <th class="text-center" valign="middle">Revenue</th>
                    </tr>
        
                </thead>
        
                <tbody>
                
                    @foreach( $vars['meter_details'] as $meter )
                        
                        <tr>
                            @if( $vars['export'] === '' ) 
                                <td class="text-center" align="center"><input type="checkbox" name="selectedMeters" value="{{ $meter->id }}" group_id = "{{ $meter->group_id }}" /></td>
                            @endif
                            <td class="text-center meter_id" align="center">
                                <a href="javascript:open_report_modal({{ $meter->meter_id }},'day');void(0);" >{{ $meter->meter_id }}</a>
                            </td>
                            
                            <?php 
                                $expiry = app('App\Http\Controllers\UtilsController')->date_difference(date('Y-m-d H:i:s'),$meter['expiry']);
                                $expiry_text = ( $expiry["hours"] == 0 && $expiry["mins"] == 0 ) ? "Expired" : ((($expiry["hours"] > 0)?$expiry["hours"]." Hrs ":"" ).$expiry["mins"]." Min");
                            ?>
                            
                            @if( $vars['export'] === '' )
                                <td class="text-center">{{ $expiry_text }}</td>
                            @endif
                            
                            <td class="text-center" align="center">
                                @if(number_format($meter->hour_price,2) == 0.00)
                                    ${{ number_format($meter->price,2) }}
                                @else
                                    ${{ number_format($meter->hour_price,2) }}
                                @endif
                            </td>
        
                            <td class="text-center" align="center">{{ $meter->transactions }}</td>
        
                            <td class="text-center" align="center">{{ number_format(($meter->total_hours > 0) ? $meter->total_hours : 0,2) }}</td>
                            
                            <td class="text-center" align="center">$ {{ number_format($meter->landowner_revenue,2) }}</td> <?php //$net_revenue ?>
                            
                            <td class="text-center" align="center">$ {{ number_format($meter->my_meter_revenue,2) }}</td>
                        </tr>
                    
                    @endforeach
        
                </tbody>
                
            </table>
        
            </div>	
        @if( $vars['export'] === '' )	

                @include('includes.export-buttons')
        @endif   
        
        
        @if( $vars['export'] === 'PDF' )

            </body>

            </html>
            
        @endif  


        @if( $vars['export'] === '' )
        
            @include('admin.lots')
        
        
            <div id="createNewUserModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
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
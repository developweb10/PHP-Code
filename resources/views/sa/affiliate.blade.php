<div class="container-fluid">

	<div class="row">

    	<div class="">

            <div class="panel panel-default">

                <!-- <div class="panel-heading">Affiliate Account Info</div> -->

                <div class="panel-body">
					@if ((!config('is_Ipad')) && config('is_mobile'))  
                        <span class="to-text page_title"><b>Overview</b></span>
                    @endif
                    @if ($vars['tab'] === '' || $vars['tab'] === 'overview')

                        @if (  count($errors) > 0)
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

                    @endif

                    <div class="col-md-12">

                        <div class="top_section">

                            <h2>{{ $user->name }}</h2>

                            <h4>{{ $vars["role"] }}</h4>

                            <h4><strong>Tracking URL:</strong> <span id="tracking_url_box">{{ $vars['affilliateURL'] }}</span></h4>

                            <h4><strong>Promo Code:</strong> <span style="margin-left:17px;">{{ $user->promo_code }}</span></h4>

                        </div>

                    </div>



                    <div>

                        <div class="col-md-6">

                            <div class="text_section">

                                {{ app('App\Http\Controllers\UtilsController')->html_decode($vars["overview_data"]->dashboard_text) }}

                            </div>

                        </div>

        

                        <div class="col-md-6 text-center">

                            <img src="{{ $vars['overview_data']->dashboard_image }}" border="0" style="max-width:90%;" />

                        </div>

                        <div class="clearfix"></div>

                    </div>

                    <br />

                    <?php  /*

                    <hr />

                    <div class="row text-center">

                        <form action="" method="get" class="form-inline">

                            <strong>Filters:</strong>

                            <input type="text" class="form-control datepicker1" name="start_date" value="{{ $vars['start_date'] }}" placeholder="Start Date" />

                            <input type="text" class="form-control datepicker1" name="end_date" value="{{ $vars['end_date'] }}" placeholder="End Date" />

                            <input type="submit" class="btn btn-info"  value="Submit" />

                            <input type="reset" class="btn btn-danger" value="Clear">

                        </form>

                    </div>

                    <hr />

                    */ ?>

                    <br />

                    <div class="report_statistics1 row">

                        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2 text-center">

                            <h3>Clients</h3> <!-- Signups -->

                            <h1>{{ $vars["signups"] }}</h1>

                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2 text-center">

                            <h3>Total Meters</h3>

                            <h1>{{ $vars["meters"] }}</h1>

                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2 text-center">

                            <h3>Active Meters</h3>

                            <h1>{{ $vars["active_meters"] }}</h1>

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 text-center">

                            <h3>Commisions Earned</h3>

                            <h1>${{ number_format($vars["net_comm"],2) }}</h1>

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 text-center">

                            <h3>Next Payment</h3>

                            <h1><span>$</span>{{ number_format($vars["next_pay"],2) }}</h1>

                        </div>

                        <div class="clearfix"></div>

                    </div>

                                    

                    <br />

                    <?php  /*

                    <div class="report_chart text-center">

                        <br /><br />

                        @if( count( $vars["chart_data"]["label"] ) <= 0 and ( !empty($vars['start_date']) or !empty($vars['end_date']) ) )

                            <div class="alert alert-danger">There were no transactions for the selected period.</div>

                        @endif

                        <div id="canvas_container">

                            <canvas id="canvas" height="300" width="600"></canvas>

                        </div>

                    </div>

                    */ ?>

    

                </div>

            </div>

        </div>

	</div>

</div>

<?php  /*

    <script>

	var chart_data = [];

	chart_data["label"] = [];

	chart_data["dataset"] = [];

	@foreach( $vars["chart_data"]["label"] as $label )

		chart_data["label"].push('{{$label}}');

	@endforeach

	@foreach( $vars["chart_data"]["dataset"] as $dataset )

		chart_data["dataset"].push({{ $dataset }});

	@endforeach

	

	var initializeAffChart = true;

	

	@if ( $vars['start_date'] != '' or $vars['end_date'] != '' )

		document.addEventListener( "DOMContentLoaded", function(){

			 $('html,body').animate({scrollTop: $(".report_statistics1").offset().top},'slow');

			

		}, false );

	@endif

</script>

*/ ?>
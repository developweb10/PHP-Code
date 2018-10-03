<div class="container-fluid">
  <div class="row">
    <div class="">
      <div class="panel panel-default"> 
        <!-- <div class="panel-heading">Affiliate Account Info</div> -->
        <div class="panel-body">
        	@if ((!config('is_Ipad')) && config('is_mobile'))  
                <span class="to-text page_title"><b>Overview</b></span>
            @endif
            <div class="table-responsive">
          	<table id="datatable" width="100%" class="display compact test" cellspacing="0" >
            <thead>
              <tr>
              	<th class="col-md-2 text-center">City</th>
                <th class="col-md-2 text-center">Name</th>
                <th class="col-md-2 text-center">Email</th>
                <th class="col-md-2 text-center">Signups</th>
                <th class="col-md-2 text-center">Revenue</th>
                <th class="col-md-2 text-center">Commission</th>
                <th class="col-md-1 text-center">Status</th>
              </tr>
            </thead>

            @if( count($sa_users) )
              <tbody>
              
              @foreach( $sa_users as $user )
              <tr>
  			    <td class="col-md-2 text-center">{{ $user->city_name }}</td>
                <td class="col-md-2 text-center">{{ $user->name }}</td>
                <td class="col-md-2 text-center">{{ $user->email }}</td>
                <td class="col-md-2 text-center">{{ $vars['sa_sinups'][$user->id] }}</td>
                <td class="col-md-2 text-center">{{ $vars['sa_revenue'][$user->id] }}</td>
                <td class="col-md-2 text-center">{{ $vars['sa_commissions'][$user->id] }}</td>
                <td class="col-md-1 text-center">{{ $user->deleted == 1 ? "Inactive" : "Active"  }}</td>
               
              </tr>
              @endforeach
              </tbody>
            @endif
            
          </table>
             </div>
                    <br />

                    <div class="report_statistics1 row">

                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-center">

                            <h3>Signups</h3>

                            <h1>{{ $vars["signups"] }}</h1>

                        </div>


                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-center">

                            <h3>Commisions Earned</h3>

                            <h1>${{ number_format($vars["net_commissions"],2) }}</h1>

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-center">

                            <h3>Next Payment</h3>

                            <h1><span>$</span>{{ number_format($vars["next_pay"],2) }}</h1>

                        </div>

                        <div class="clearfix"></div>

                    </div>

          <br />
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

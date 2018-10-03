<div class="container-fluid">
  <div class="row">
    <div class="">
      <div class="panel panel-default"> 
        <!-- <div class="panel-heading">Affiliate Account Info</div> -->
        <div class="panel-body">
        	@if ((!config('is_Ipad')) && config('is_mobile'))  
                <span class="to-text page_title"><b>Clients</b></span>
            @endif
          <div class="table-responsive">
          	<table id="datatable" width="100%" class="display compact" cellspacing="0" >
            <thead>
              <tr>
              	<th class="col-md-2 text-center">City</th>
                <th class="col-md-2 text-center">Name</th>
                <th class="col-md-2 text-center">Email</th>
                <th class="col-md-2 text-center">Meters</th> <!-- Revenue -->
                <th class="col-md-2 text-center">Commissions</th>
                <th class="col-md-2 text-center">Next Payout</th>
              </tr>
            </thead>
            <tbody>
            <?php //echo "<pre>"; print_r($vars); echo "</pre>"; ?>
            <?php //echo "<pre>"; print_r($data); echo "</pre>"; ?>
            <?php //echo "<pre>"; print_r($landowner_users); echo "</pre>"; ?>
            <?php //echo "<pre>"; print_r($banks); echo "</pre>"; ?>
            @foreach( $landowner_users as $user )
            <tr>
			  <td class="col-md-2 text-center">{{ $user->city_name }}</td>
              <td class="col-md-2 text-center">{{ $user->name }}</td>
              <td class="col-md-2 text-center">{{ $user->email }}</td>
              <td class="col-md-2 text-center">{{ $user->meter_count }}</td> <!--$vars["revenue"][$user->id]-->
              <td class="col-md-2 text-center">{{ $vars["commission"][$user->id] }}</td>
              <td class="col-md-2 text-center">{{ $user->deleted == 1 ? "Inactive" : "Active"  }}</td>
             
            </tr>
            @endforeach
            </tbody>
            
          </table>
          </div>
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

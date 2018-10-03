
<div class="container-fluid">

	<div class="row">

		<div class="">

			<div class="panel panel-default">

				<!-- <div class="panel-heading">

					Payments

				</div> -->

				<div class="panel-body" style="position:relative;">
                
                	@if ((!config('is_Ipad')) && config('is_mobile'))  
                              <span class="to-text page_title"><b>Payments</b></span>
                    @endif

					@if ( $vars['tab'] === 'payments' && count($errors) > 0)

						<div class="alert alert-danger">

							<strong>Whoops!</strong> There were some problems with your input.<br><br>

							<ul>

								@foreach ($errors->all() as $error)

									<li>{{ $error }}</li>

								@endforeach

							</ul>

						</div>

					@endif

					

					@if( $vars['tab'] === 'payments' && Session::has('success'))

						<div class="alert alert-success">

							<strong>Success!</strong> {{ Session::get('success') }}

						</div>

					@endif
					<p>
                    	<style>
							.security_ques{
								position: absolute;
								right: 10px;
								border: 2px solid #949191;
								padding: 1%;
								font-size: 14px;
							}
						</style>
                        <section class="text-center security_ques">Your Payout Code is : <br /> <?php if(isset( $vars['user']->security_answer )){ echo $vars['user']->security_answer; } ?>  </section>

                    	<h3 class="text-center" >Your next payment date is {{ $vars['next_month'] }} 1st</h3>

                        <p class="text-center">Please note that an account balance must be at least $50.00 before we send out payments</p>

                    </p>



					<table id="datatable_alone" class="display payments-table" cellspacing="0" width="100%">

						<thead>

							<th class="text-center">Payment Date</th>

							<th class="text-center">Amount Paid ($)</th>

						</thead>

						<tbody>

							<?php $totals = array("payment"=>0.00); ?>

							@foreach( $vars['payments'] as $pay )

								<tr>

									<td class="text-center">{{ date("Y-m-d",strtotime($pay['created_at'])) }}</td>

									<td class="text-center">{{ number_format($pay['amount'],2) }}</td>

								</tr>

								<?php $totals["payment"] += $pay['amount']; ?>

							@endforeach

						</tbody>

						<tfoot>

							<td class="text-center">TOTALS</td>

							<td class="text-center">{{ number_format($totals["payment"],2) }}</td>

						</tfoot>

					</table>

					

				</div>

			</div>

		</div>

		<div class="clearfix"></div>

	</div>

</div>

<style>.dataTables_info{ display:none; }</style>

@if ( $vars['tab'] === 'payments' )

	<script>var chartInitialized = true;  </script>

    

@endif

<script>

	document.addEventListener( "DOMContentLoaded", function(){

		setTimeout(function(){

			$("#datatable_alone.payments-table .dataTables_empty").html("No payments have been sent");

		},2000);

	}, false );

</script>




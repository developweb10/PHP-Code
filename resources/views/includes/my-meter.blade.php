@extends('welcome-app')



@section('content')

<?php
	//session_start();
	//echo "session : <pre>"; print_r($_SESSION); echo "</pre>";
	if(isset($_SESSION['bonus']) && $_SESSION['bonus'] == 1){
		unset($_SESSION['bonus']);
	?>
		<script> alert("Congratulations! 15mins have been added in your time. "); opener.location.reload();  window.close();   </script>
    <?php
		
	}elseif(isset($_SESSION['bonus']) && $_SESSION['bonus'] == 0){
		unset($_SESSION['bonus']);
		?>
			<script> alert("15mins already been added in your time.");window.close();   </script>
        <?php
	}
?>

<style type="text/css">

	#terms-link{

		display:block !important;

		font-size: 18px;

	}
	.grey_text{
		color: #949494;;
	}

</style>

<div class="container-fluid">

	<div class="row">

		<div class="col-md-8 col-md-offset-2">

			<div class="panel panel-default" @if ((!config('is_Ipad')) && config('is_mobile')) style = "padding-top:8%;"  @endif >

				<?php /*?><div class="panel-heading">Hire Meter</div><?php */?>

				<div class="panel-body">

                	
					<?php
						if(isset($_SESSION["success"])){
							if(isset($_SESSION["pay_item_id"]) && !empty($_SESSION["pay_item_id"])){
					?>
								<div class='text-center'>

									<img src="{{ asset('images/metercar.png') }}" style="max-height:100px;" >
	
								</div>
         
                                <h3 class='text-center' style="font-weight: bold;">Booking Confirmed!</h3>
                                
                                <?php if(isset($_SESSION['meter_id']) && !empty($_SESSION['meter_id'])): ?>
                                	<h4 class="text-center grey_text">Meter #<?php echo $_SESSION['meter_id']; ?></h4>
                                <?php endif; ?>
                                
                                <?php if(isset($_SESSION['time_hours']) && !empty($_SESSION['time_hours'])): ?>
                                	<h4 class="text-center grey_text">Booked: <?php echo $_SESSION['time_hours']; ?></h4>
                                <?php endif; ?>
                                
                                <?php if(isset($_SESSION['amount']) && !empty($_SESSION['amount'])): ?>
                                	<h4 class="text-center" style="font-weight: bold;">Payment: $<?php echo $_SESSION['amount']; ?></h4>
                                <?php endif; ?>
                                
                                
                               
                    <?php
							}
						}
					
					?>
                    <!--
					@if( Session::has('success') || Session::has('opt_in_err'))

						@if( Session::has('pay_item_id') )
                        
                    		<div class='text-center'>

								<img src="{{ asset('images/metercar.png') }}" style="max-height:100px;" >

							</div>
                            
                            @if( !Session::has('opt_in_err') )
                            	<h3 class='text-center' style="font-weight: bold;">Booking Confirmed!</h3>
                                
                                @if( Session::has('meter_id') )
                            	<h4 class="text-center grey_text">Meter # {{ Session::get("meter_id") }}</h4>
                            	@endif
                                
                                @if( Session::has('time_hours') )
                            		<h4 class="text-center grey_text">Booked: {{ Session::get('time_hours') }}</h4>
                            	@endif
                                @if( Session::has('amount') )
    	                        	<h4 class="text-center" style="font-weight: bold;">Payment: ${{ Session::get('amount') }}</h4>
	                            @endif
                            
                            @endif
                        @endif
                     
					@endif
                    -->
                    <div class="clock"></div>
                  
	

					<?php /*?>@if (count($errors) > 0)

						<div class="alert alert-danger text-center col-md-8 col-md-offset-2">

							<strong>Whoops!</strong> There were some problems with your input.<br><br>

							<ul>

								@foreach ($errors->all() as $error)

									<li>{{ $error }}</li>

								@endforeach

							</ul>

						</div>

					@endif<?php */?>

					<div class="clearfix"></div>
                   
					<?php if(isset($_SESSION["success"])){ 
							if(isset($_SESSION["pay_item_id"]) && !empty($_SESSION["pay_item_id"])){
					?>
                    	<form class="form-horizontal">
	                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-md-12 text-center TEST">
                                    <button type="button" class="btn btngreen btn-width100" id="bttn">Set Reminder <i class="fa fa-bell-o" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </form>	
                        <br />
						<div id="show" style="display: none;">

								<!--<h1 class='thanksclass text-center'>Thanks!</h1>-->

								<div class="text-center">

									If you need to extend your booking we can send you a notification 5 minutes before your meter expires. Just enter your mobile number below beginning with your area code.

								</div>

							

								<form class="form-horizontal" role="form" method="POST" action="{{ url('/home/add_opt_in') }}" >

								<input type="hidden" name="_token" value="{{ csrf_token() }}">

								<input type="hidden" name="pay_item_id" value="{{ Session::get('pay_item_id') }}">

								<br />

								<div class="form-group margin-botomformgrp">

									<div class="col-md-4 col-md-offset-4">

										<div>

											<input type="text" class="form-control text-center" name="opted_phone_number" value="{{ old('opted_phone_number') }}" required="required" placeholder="Mobile #" pattern="\d*" >

										</div>

										<div class="col-md-7 "><div class="meter_message to-text col-md-10" style="display:none;border:1px solid green;"></div></div>

									</div>

								</div>

								<br />

								<div class="form-group">

									<div class="col-md-12 text-center">

										<button type="submit" class="btn btngreen btn-width100" >Save Reminder <i class="fa fa-bell-o" aria-hidden="true"></i></button>

									</div>

								</div>

							</form>
                            </div>
                    <?php }else{
								?>
                                	<div class='text-center'>

                                        <img src="{{ asset('images/metercar.png') }}" style="max-height:100px;">
        
                                    </div>
        
                                    <h1 class='thanksclass text-center'>Great!</h1>
        
                                    <div class="alert alert-success text-center">
        
                                        <?php echo $_SESSION["success"]; ?>
        
                                    </div>
                                <?php
							}
					
					} else{ ?>
                    	<div class="col-lg-2 col-md-1"></div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mobile-padding-0">

							@include('includes.my-meter-form')

						</div>

						<div class="col-lg-2 col-md-1"></div>
                    <?php } ?>
					

					

				</div>

			</div>

		</div>

	</div>

</div>

@if ((!config('is_Ipad')) && config('is_mobile'))
<?php
	if(isset($_SESSION["success"])){
		if(isset($_SESSION["pay_item_id"]) && !empty($_SESSION["pay_item_id"])){
?>
        <div style="background-image:url('{{ asset('images/Home Page Background.jpg') }}');height: 203px;background-size: 100%;background-repeat: no-repeat; text-align:center;">
    		<h4 style="color:#fff; margin-bottom: 24px; letter-spacing: 2px;">Share on facebook and receive 15 bonus minutes!</h4>   
            <a href="redirect" class="facebook_share"><img src="{{ asset('images/Facebook_transparent.png') }}"></a>
        </div>
<?php
		}
	}
?>
@endif
<!-- Delete Lot Modal -->

<div id="termsDialog" class="modal fade" role="dialog">

	<div class="modal-dialog modal-lg">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title text-center">{{ $data->page_title }}</h4>

			</div>

			<div class="modal-body" style="max-height:500px; overflow-y:scroll;">

				@if ( $data->page_content != '' )

					{{ app('App\Http\Controllers\UtilsController')->html_decode($data->page_content) }}

				@endif

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

			</div>

		</div>

	</div>

</div>





@endsection





@section('additionalJS')



<script>



$(document).ready(function(){
//$("#show").hide(); 
$("#bttn").click(function(){
	$( "#show" ).slideToggle();
  /* $if ($.trim($(this).text()) === 'Text Reminder') {
		
		$('html, body').animate({
			scrollTop: $("#show").offset().top + 20
		}, 2000);
	} else {
		$(this).html('Text Reminder <i class="fa fa-bell-o" aria-hidden="true"></i>');     
	}
   (this).toggleClass("active").next().slideToggle("fast");
    
    if ($.trim($(this).text()) === 'Text Reminder') {
        $(this).text('Hide Answer');
    } else {
        $(this).text('Text Reminder');        
    }
    
    return false; */
});
 $("a[href='" + window.location.hash + "']").parent(".reveal").click();
});


$(document).ready(function(){
    $(".btngreen").click(function(){
        $("#show").hide();
		$("#show").hide();
    });
	$(".btngreen").click(function(){
        $("#show").show();
    });
});
</script>

	<script>

		$(document).ready(function(){

			$(".navbar-brand").replaceWith('<a class="navbar-brand" href="#" data-toggle="modal" data-target="#termsDialog">Terms <i class="fa fa-file-text-o" aria-hidden="true"></i></a>');

		});

	</script>

	<?php if(isset($_SESSION['expiry_time']) && !empty($_SESSION['expiry_time'])): ?>
	
    	
		<link href="{{ asset('/vendors/counter/flipclock.css') }}" rel="stylesheet">

		<script src="{{ asset('/vendors/counter/flipclock.js')}}"></script>

		<script type="text/javascript">
			
			
			
			var clock;

			var meter_id = '<?php echo $_SESSION['meter_id']; ?>';

			

			$(document).ready(function() {
				

				setTimer(<?php echo $_SESSION['expiry_time']; ?>);

				

				$(window).focus(function() {

					$.post("/home/get_expiry_time",{meter_id:meter_id},function(data){

						remaining_time = $.trim(data)

						if( remaining_time <= 0  ){

							alert("Your meter time limit reached!");
							
							

							window.location = '/my-meter';

						}

						else{

							setTimer(remaining_time);

							console.log(data);

						}

					})

				});

			});

			

			function setTimer(tm){

				clock = $('.clock').FlipClock({

					clockFace: 'HourlyCounter',

					autoStart: false,

					callbacks: {

						stop: function() {

							alert('Your meter time limit reached!');
							

						}

					}

				});

				clock.setTime(tm*1);

				clock.setCountdown(true);

				clock.start();

			}

		</script>

		<style>

			.clock{

			    width: 460px;

				margin: 2em auto;

			}

			@media screen and (max-width:768px)

			{
				
				.clock{

					width: 400px;

				}

				.flip-clock-wrapper ul{

					width: 50px;

					height: 66px;

				}

				.flip-clock-wrapper ul li{

					line-height: 68px;

				}

				.flip-clock-wrapper ul li a div div.inn{

					font-size: 50px;

				}

				.flip-clock-divider{

					height: 90px;

				}

				.flip-clock-dot.top {

					top: 22px;

				}

			}

			@media screen and (max-width:480px)

			{

				.clock{

					width: 280px;

				}

				.flip-clock-wrapper ul{

					width: 30px;

					height: 45px;

					padding: 16px;

				}

				.flip-clock-wrapper ul li{

					line-height: 46px;

				}

				.flip-clock-wrapper ul li a div div.inn{

					    font-size: 35px;

				}

				.flip-clock-divider{

					height: 77px;

					width: 10px;

				}

				.flip-clock-dot{

					left: 0px;

				}

				.flip-clock-dot.top {

					top: 15px;

				}

				.flip-clock-label{

					display:none;

				}

			}

			@media screen and (max-width:360px)

			{

				.clock{

					width: 252px;

				}

				.flip-clock-wrapper ul{

					width: 28px;

					height: 45px;

					padding: 14px;

				}

			}

		</style>

	<?php endif; ?>

    
<script>
 
 $(document).ready(function(){
	 <?php
		if( isset($data->meter_id) && !empty($data->meter_id) )	{
			?>
				//alert(<?php //echo $data->meter_id; ?>);
				validate_meter();	
				
			<?php
		}
	?>
 });

	
</script>

    

@endsection
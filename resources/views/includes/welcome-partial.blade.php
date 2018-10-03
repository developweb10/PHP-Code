<div class="over_steps steps-container clearfix ">
	<?php /* <a href="#" class="small-link header_sign_up_button" data-toggle="modal" data-target="#openSignUpModal" data-static="true" data-keyboard="false" style="">Sign Up</a>
	<h1>{{ $data->section1->heading or "" }}</h1>
    <br/>
    
    <form action="{{ URL::to('/home/register') }}" method="post" class="register_form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="reg_type" value="quick"  />
        <div class="col-md-2 report-bordered steps col-centered">
            <input type="text" name="name" placeholder="Name" required="required" class=" @if( config('is_mobile') != 1 ) btn btn-success @else Mobile_fields @endif full-width">
        </div>
        <div class="col-md-2 report-bordered steps col-centered">
            <input type="email" name="email" placeholder="Email" required="required" class=" @if( config('is_mobile') != 1 ) btn btn-success @else Mobile_fields @endif full-width">
        </div>
        <div class="col-md-2 report-bordered steps last col-centered">
            <input type="submit" value="SignUp" id="sign-up-button" class="btn btn-green btn-success full-width"  style='color: white;background: #87D236 !important;'>
        </div>
        
       	 <input type="text" name="name" placeholder="Name" required="required" class="btn btn-success full-width">
         <input type="email" name="email" placeholder="Email" required="required" class="btn btn-success full-width">
         <input type="submit" value="SignUp" id="sign-up-button" class="btn btn-green btn-success full-width"  style='color: white;background: #87D236 !important;'>
   
    </form>
   
    <div class="clearfix">
        <p>@if( $data->section3->description ) {{ app('App\Http\Controllers\UtilsController')->html_decode($data->section1->description) }} @endif</p>
    </div> */ ?>
</div>

<div class="clearfix info-mechanics">
	<div class="container">
        <div class="info-mechanic clearfix vertical-center-table">
            <div class="col-md-6 text-centered"> <a class="popup-youtube"> 
                <img class="img-responsive" src="{{ $data->section2->image or "" }}" alt="image"/> </a> 
            </div>
            <div class="col-md-6 text-centered">
                <h1>{{ $data->section2->heading or "" }}</h1>
                <p>@if( $data->section2->description != "" ) {{ app('App\Http\Controllers\UtilsController')->html_decode($data->section2->description) }} @endif</p>
            </div>
        </div>
        <div class="info-mechanic clearfix vertical-center-table">
        
        	<div class="col-md-6 text-centered float_right"> <img class="img-responsive" src="{{ $data->section3->image or "" }}" alt="image"/> </div>
            <div class="col-md-6 text-centered float_left">
            <h1>{{ $data->section3->heading or "" }}</h1>
                    <p>@if( $data->section3->description ) {{ app('App\Http\Controllers\UtilsController')->html_decode($data->section3->description) }} @endif</p>
            @if( config('is_mobile') ) 
            	</div>
            @endif
            </div>
            
        </div>
        
        <div class="info-mechanic clearfix vertical-center-table">
            <div class="col-md-6 text-centered"> <a class="popup-youtube"> 
                <img class="img-responsive" src="{{ $data->section6->image or "" }}" alt="image"/> </a> 
            </div>
            <div class="col-md-6 text-centered">
                <h1>{{ $data->section6->heading or "" }}</h1>
                <p>@if( $data->section6->description != "" ) {{ app('App\Http\Controllers\UtilsController')->html_decode($data->section6->description) }} @endif</p>
            </div>
        </div>
        
    </div>
</div>



<script type="text/javascript">
	
</script>
<style type="text/css">
	.vertical-center-table{ display:table; }
	.vertical-center-table > div{
		display: table-cell;
		vertical-align: middle;
		float: none !important;	
	}
	.vertical-center-table > div > h1{
		margin-top:10px;
	}
	.register_form input[type="text"] , .register_form input[type="email"]{
		color:#333 !important;
	}
		
	@media screen and (max-width: 520px) 
	{
		.Mobile_fields{
			border: 2px solid #bbb;
			color: #333333;
			font-size: 1.5em;
		    padding: 6px 12px;
			text-align:center;
			border-radius:0;
		}
	}
	@media screen and (max-width: 1024px){
		body.welcome 

		{

			background-image:none;

		}
		body .content-container .over_steps{ 
				margin-top: 0px;
				border-bottom: none;
			}
	
	}
</style>
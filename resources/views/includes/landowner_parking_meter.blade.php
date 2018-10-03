<div class="container-fluid">

	<div class="row">

		<div class="">

			<div class="panel panel-default">
				
				<div class="panel-body">
                	@if ((!config('is_Ipad')) && config('is_mobile')) 
                              <span class="to-text page_title"><b>Parking Signs</b></span>    
                    @endif
                	<!--Landowner Park View Start-->
                	<div class="landowner_park_view col-lg-12">
                        <div class="col-xs-12 col-sm-1 col-md-2 col-lg-2"></div>
                        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 img_left">
                            <img src="{{ asset('/images/custom/qr sign graphic_1.jpg') }}" border="0" class="img-responsive" style="max-height: 355px; width:100%;" />
                        </div>
                        
                        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 left_cont">
                            <div class="outer_cont">
                                <div class="row">
                                 <div class="col-xs-8 col-sm-10 col-md-8 col-lg-8">
                                    <span class="parking_txt">Order new parking signs with new meter numbers...</span>
                                 </div>
                                 <div class="col-xs-4 col-sm-2 col-md-4 col-lg-4 text-right">
                                    <a href="#" data-type="new_parking" class="select_parking">
                                    	<i class="fa fa-angle-right icon cust_arw"></i>
                                    </a>
                                 </div>
                                </div>
                                <br/>
                                <hr/>
                                <br/>
                                <div class="row">
                                 <div class="col-xs-8 col-sm-10 col-md-8 col-lg-8">
                                    <span class="parking_txt">Replace existing parking signs with existing meter numbers...</span>
                                 </div>
                                 <div class="col-xs-4 col-sm-2 col-md-4 col-lg-4 text-right">
                                    <a href="#" data-type="replace_parking" class="@if(count($vars['meter_details']) != 0) select_parking @endif" style=" @if(count($vars['meter_details']) == 0) cursor: no-drop; @endif ">
                                    	<i class="fa fa-angle-right icon cust_arw @if(count($vars['meter_details']) == 0) grey_color_text @endif"></i>
                                    </a>
                                 </div>
                                </div>		
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1"></div>
                    </div>
                    <!--Landowner Park View End-->
                    
                    <!--Landowner New Parking Start-->
                    <div class="landowner_new_parking" style="display:none;">
                    	@include('includes.landowner_step_ui_parking_signs')
                    </div>
                    <!--Landowner New Parking End-->
                    
                   
				</div>

			</div>

		</div>

		<div class="clearfix"></div>

	</div>

</div>


@include('agree_to_terms_modal')

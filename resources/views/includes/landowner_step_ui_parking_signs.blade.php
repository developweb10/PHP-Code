<div class="tabbale"> <!-- Only required for left/right tabs -->
    <ul class="nav nav-tabs steps_list" style="display:none">
        <li class="step1 new_parking"><a href="#step1" data-toggle="tab">Step 1</a></li>
        <li class="step2 ui-state-disabled" ><a href="#step2" >Step 2</a></li><!-- data-toggle="tab" -->
        <li class="step3 ui-state-disabled"><a href="#step3">Step 3</a></li>
       	<button type="button" class="close" data-dismiss="modal" style="padding:10px;">×</button>
    </ul>


    <form class="form-horizontal text-center" role="form" method="POST" action="{{ URL::to('/home/newmeter_signup') }}" id="activation_steps" autocomplete="on">
		
        <div class="modal-body activation_steps_body">
            	<div class="tab-content">
                 	<input type="hidden" name="order_type" id="order_type" value="1" />
                    <!--<input type="hidden" name="exist_parking" id="exist_parking" value="" />-->
                   
                   
                   <div class="tab-pane" id="step3">
                    
                  <div class="form-group">
                         <div class="devidation_horizontl">
                            <div class="col-lg-12 nopadding_mobile">
                            	<div class="col-xs-1 col-sm-1 col-md-2 col-lg-2 park_view_back">
                              	<a class="btnBack pull-right" style='box-shadow:none;'> 
                                 	<i class="fa fa-angle-left icon cust_arw grey_color_text" aria-hidden="true"></i>
                                 	<span class="back_btn_cls hidden-xs hidden-sm" aria-hidden="true">BACK</span>
                                 </a>
                              </div>
                                <div class="col-sm-5 col-md-4 col-lg-4 hide_on_mobile" style="position:relative;">
                                    <img src="{{ asset('/images/custom/qr sign graphic.jpg') }}" class="signage_image" style="max-height:355px; width:100%;" />
                                    <div> 
                                        <!--<span class="lg_text green_text" style="position:absolute;">2</span> 
                                        <span class='dot green_text' style="position:absolute;">.</span>
                                        <span class="md_text green_text" style="position:absolute;">00</span> -->
                                        <span class="contact_no green_text" style="position:absolute;"> 
                                        	@if(!empty($user->towing_company_number)) {{$user->towing_company_number}} @endif
                                        </span> 
                                    </div>
                                </div>
                                <div id="paypal_form">
                                    <div class="col-xs-11 col-sm-5 col-md-4 col-lg-4 mobile_height_391" style="margin-top:14px;">
                                      <h3 class='order_summry_text margin-bottom0'> Order Summary </h3>
                                      <div class='col-md-12 bottom-border_text padding_left-right0'>
                                      
                                      	
                                        <div class="form-group col-md-12 padding_left-right0 margin_left-right0">
                                           
                                           <h5 class='margin-top-bottom8 text-align_left' style="margin-bottom:0;"> 
                                           	<span class="bold_text small_text">Quantity: </span>
                                            <span class="meter_count grey_text">1 Parking Sign</span>
                                            <span class="bold_text small_text"> (x {{config("meter_base_price")}})</span>
                                           </h5>
                                           <div class="col-xs-12 col-sm-12 col-md-12 text-align_left padding_left-right0">
                                                <h5 class='margin-top-bottom8'> 
                                                	<span class="bold_text small_text">Sub Total: </span>
                                                    <span class="sub_count grey_text">${{ config("meter_base_price") }}</span>
                                                    <span class="bold_text">@if(!empty($user->payable_amount) && ($vars["promo_code_discount"] !=0))(-<?php echo $vars["promo_code_discount"]+0; ?>)%@endif</span>	
                                                </h5>
                                                <h5 class='margin-top-bottom8'> 
                                                	<span class="bold_text small_text">Shipping: </span>
                                                    <span class="shipping_price grey_text">${{ $vars["ship_price_lo"] }}<?php  ?></span> <!-- $15.00 -->
                                                </h5>
                                            </div>
                                            @if(!empty($user->promo_code))  
                                            	<?php $readonly = "readonly = 'readonly'"; ?>
                                            @else
                                            	<?php $readonly = ""; ?>
                                            @endif 
                                            <div class="col-xs-12 col-sm-12 col-md-12 padding_left-right0 pull-right">
                                            	<span class='margin-top-bottom8'> 
                                                	<div class="col-lg-6 padding_left-right0"> <span class="bold_text small_text pull-left">Promo Code: </span> </div>
                                                    <div class="col-lg-6 padding_left-right0">
                                                    	<input type="text" class="form-control text-center disableAutoComplete pull-right" name="promo_code" value="@if(!empty($user->promo_code)) {{$user->promo_code}}@endif " placeholder="Promo Code" style='width:50%; margin-bottom:5px;' {{ $readonly }} />
                                                    </div>
                                                    <input type="hidden" name="refered_by" value="" />
                                                    <input type="hidden" name="commission" value="" />
                                                 </span>
                                            </div>
                                            
                                          </div>
                                       
                                      
                                        </div>
                                        
                                        
                                        <div class='row'>
                                        <div class="form-group col-md-12 margin_left-right0">
                                        <div class="col-md-12 text-align_left padding_left-right0">
                                                <h4 style='margin-top:15px;'>
                                                	<span class="bold_text">Total:</span> 
                                                    <span id="loading_img_total" style="display:none;">
                                                        <img src="{{ asset('images/load.gif') }}" width="30px" height="30px">
                                                    </span>
                                                    <span class="payable_amount grey_text">
	                                                    {{ $user->payable_amount }}
                                                    	<?php /* @if(!empty($user->payable_amount))
                                                        	{{ $user->payable_amount }}
                                                        @else
                                                        	$44.99
                                                        @endif */
														?>
                                                    	
                                                    </span>
                                                    
                                                 </h4>
                                                <input type="hidden" name="payable_amount" class="payable_amount" value="{{ $user->payable_amount }}" />
                                            </div>
                                        </div>
                                        </div>                                                          
                                        <div class="form-group">
                                            <label class="col-md-12 control-lable"></label>
                                            <div class="col-md-12 text-left"><img src="{{ asset('images/Credit Cards.png') }}" class="cart_types_img margin_none" border="0"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control text-center disableAutoComplete" name="cc" placeholder="Card Number"  />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <!--<input type="button" class="sleectText" value=" Expiry Month " style="font-size:16px; width:80%;">-->
                                                <div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i></div>
                                                <select name="expiry_month" class="form-control select_center_text padding_left-right0 disableAutoComplete" required="required">
            
                                                    <option value="">Expiry Month</option>
            
                                                    
                                                    <option>1</option>
            
                                                    
                                                    <option>2</option>
            
                                                    
                                                    <option>3</option>
            
                                                    
                                                    <option>4</option>
            
                                                    
                                                    <option>5</option>
            
                                                    
                                                    <option>6</option>
            
                                                    
                                                    <option>7</option>
            
                                                    
                                                    <option>8</option>
            
                                                    
                                                    <option>9</option>
            
                                                    
                                                    <option>10</option>
            
                                                    
                                                    <option>11</option>
            
                                                    
                                                    <option>12</option>
            
                                                    
                                                </select>
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <!--<input type="button" class="sleectText" value=" Expiry Year " style="font-size:16px; width:80%;">-->
                                                <div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i></div>
                                                <select name="expiry_year" class="form-control select_center_text padding_left-right0 disableAutoComplete" required="required">
                
                                                        <option value="">Expiry Year</option>
                
                                                        
                                                        <option>2016</option>
                
                                                        
                                                        <option>2017</option>
                
                                                        
                                                        <option>2018</option>
                
                                                        
                                                        <option>2019</option>
                
                                                        
                                                        <option>2020</option>
                
                                                        
                                                        <option>2021</option>
                
                                                        
                                                        <option>2022</option>
                
                                                        
                                                        <option>2023</option>
                
                                                        
                                                        <option>2024</option>
                
                                                        
                                                        <option>2025</option>
                
                                                        
                                                        <option>2026</option>
                
                                                        
                                                        <option>2027</option>
                
                                                        
                                                        <option>2028</option>
                
                                                        
                                                        <option>2029</option>
                
                                                        
                                                        <option>2030</option>
                
                                                        
                                                        <option>2031</option>
                
                                                        
                                                        <option>2032</option>
                
                                                        
                                                        <option>2033</option>
                
                                                        
                                                        <option>2034</option>
                
                                                        
                                                        <option>2035</option>
                
                                                        
                                                        <option>2036</option>
                
                                                        
                                                        <option>2037</option>
                
                                                        
                                                        <option>2038</option>
                
                                                        
                                                        <option>2039</option>
                
                                                        
                                                        <option>2040</option>
                
                                                        
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="form-group light_bold margin_left-right0 text-align_left " style="padding:3px;">
                                            <input type="checkbox" name="is_agreed" value="1" class="btn btn-success" style="margin:0;" required /> 			 																<a class="owner_agreement" style="color: rgb(126, 217, 50); cursor:pointer;"> Agree to Terms & Conditions </a>
                                            <!--<input type="hidden" name="agree" class="agree" value="0" />-->
                                        </div>
                                        
                                         <div class="form-group">
                                            <div class="col-md-12">
                                                <!--<input type="submit" class="btn btn-primary" name="place_order" value="Place Order" placeholder="Place Order" required="">													-->
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2 pull-right text-center"><input class="pull-right" type="image" src="{{ asset('/images/order.png') }}" alt="Submit" id="proceed_checkout" name="proceed_checkout" value="proceed" data-id="3" style="height: 90px !important;" /></div>
                            </div>
                         </div>
                     </div>
                  <!--<div class="form-group">
                  		<div class="col-lg-12">
                            <div class="col-xs-5 col-sm-3 col-md-3 col-lg-3"><a class="btnBack" style='box-shadow:none;'><img src="/images/back.png"></a></div>
                            <div class="col-sm-3 col-md-3 col-lg-5"></div>
                            <div class="col-xs-7 col-sm-6 col-md-6 col-lg-4">
                                <div class='f_right'>
                               
                                     <input type="image" src="/images/place_order.png" alt="Submit" id="proceed_checkout" name="proceed_checkout" value="proceed" data-id="3" />
                                 </div>    
                            </div>
                         </div>
                     </div>-->
                   
               </div>
                   
            
            		<input type="hidden" name="_token" value="{{ csrf_token() }}"> 
            
					<div id="step1" class="tab-pane new_parking">
                        
                    	<div class="form-group">
                            <div class='devidation_horizontl'>
                            	<div class="col-lg-12 nopadding_mobile">
                                	<div class="col-xs-1 col-sm-1 col-md-2 col-lg-2 park_view_back">
                                        <a class="showpark_view pull-right" style='box-shadow:none;'>
	                                        	<i class="fa fa-angle-left icon cust_arw grey_color_text" aria-hidden="true"></i>
                                             <span class="back_btn_cls hidden-xs hidden-sm" aria-hidden="true">BACK</span>
                                        </a>
                                    </div>
                                    <div class="col-sm-5 col-md-4 col-lg-4 hide_on_mobile" style="position:relative;">
                                        <img src="{{ asset('/images/custom/qr sign graphic.jpg') }}" style="max-height:355px; width:100%;" />
                                        <div> 
                                            <!--<span class="lg_text green_text" style="position:absolute;">2</span> 
                                            <span class='dot green_text' style="position:absolute;">.</span>
                                            <span class="md_text green_text" style="position:absolute;">00</span>-->
                                            <span class="contact_no green_text" style="position:absolute;">
                                            	@if(!empty($user->towing_company_number)) {{$user->towing_company_number}} @endif
                                            </span>  
                                        </div>
                                    </div> 
                                    <div class="new_parking_sign">
                                        <div class="col-xs-10 col-sm-5 col-md-4 col-lg-4 only_mobile_height" style="position:relative; margin-top:14px;"> <!-- mobile_height_391 -->
                                            
                                            <div class="form-group" style="">
                                                <label class="col-md-12">How many parking signs would you like to order?</label>
                                                <div class="col-md-12">
                                                    <div class="col-xs-8 col-xs-offset-2 col-lg-8 col-lg-offset-2">
                                                        <input type="number" class="form-control text-center" id="parking_meter_count" name="parking_meter_count" value="1" required placeholder="No. of Signs" min="1" />
                                                    </div>
                                                </div>
                                            </div>
                                        
                                   
                                    
                                            <div class="form-group" >
                
                                                <label class="col-md-12">
                                                    
                                                        What hourly rate will you charge?
                                                    
                                                </label>
                        
                                                <div class="col-md-12">
                        
                                                    <div class="col-xs-8 col-xs-offset-2 col-lg-8 col-lg-offset-2">
                        
                                                        <select class="form-control" name="price" required>
                        
                                                            <option value="">Hourly Rate</option>
                            
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
                            
                                                            <!--<option value="10.00">$10.00</option>-->
                            
                                                        </select>
                        
                                                    </div>
                        
                                                </div>
                        
                                            </div>
                                      
                                            <hr />
                                        
                                            <div class="form-group">
                                                <label class="col-md-12"> Estimated Monthly Earnings : </label><div class="font_22"> $ <span class="expected_amount">246.40</span></div><br />
                                                <span class="calculation_desc">1 meter x $2.00/hr x 8 hrs/day x 22 days/month (-30% Service Fee)</span>
                                            </div><!-- End of col-lg-6 -->

                                        </div>
                                    </div>
                                    <div class="replace_parking_sign" style="display:none;">
                                        <div class="col-xs-10 col-sm-5 col-md-4 col-lg-4 only_mobile_height" style="position:relative; margin-top:14px;">
                                            <div class="form-group" style="">
                                                <label class="col-md-12">Select parking signs to be replaced:</label> 
                                                <div class="checkbox all_meters">
                                                
                                                    <input id="all_meters" type="checkbox" name="all_meters" value="" />  
													
                                                    <label for="all_meters">ALL</label>
                                                     <span class="red-text meter_error" style="font-size:12px;"></span>
                                                </div>
                                                <table id="landowner_meter_ids" class="" style="width:79%; margin-left: 27px;">

                                                    @foreach($mymeters as $meter)
                                                        <tr>
                                                            <td class="checkbox">
                                                                <input id="{{$meter->meter_id}}" class="tset" type="checkbox" name="meter_id[]" value="{{$meter->meter_id}}" data-price="@if($meter->hour_price == '0.00') {{$meter->price}} @else {{$meter->hour_price}} @endif" />  

                                                                <label for="{{$meter->meter_id}}">
                                                                    {{$meter->meter_id}}
                                                                </label>
                                                            </td>
                                                        </tr>

                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                	<div class="col-xs-1 col-sm-1 col-md-2 col-lg-2 park_view_next">
                                        <a class="btnNext step1_next" data-id="1" style='box-shadow:none; '>
                                            <span class="cust_arw hidden-xs hidden-sm">NEXT</span><i class="fa fa-angle-right icon cust_arw"></i>
                                        </a>
                                    </div>
                            	</div>
                            </div>
                    	</div>
                     </div>
                    
            		<div id="step2" class="tab-pane">
                        

                    	 <div class="form-group">
                             <div class="devidation_horizontl">  
                             	<div class="col-lg-12 nopadding_mobile">
                                	<div class="col-xs-1 col-sm-1 col-md-2 col-lg-2 park_view_back">
                                 	<a class="btnBack pull-right" style='box-shadow:none;'>
                                    	<i class="fa fa-angle-left icon cust_arw grey_color_text" aria-hidden="true"></i>
                                       <span class="back_btn_cls hidden-xs hidden-sm" aria-hidden="true">BACK</span>
                                       
                                    </a>
                                 </div>
                                    <div class="col-sm-5 col-md-4 col-lg-4 hide_on_mobile" style="position:relative;">
                                        <img src="{{ asset('/images/custom/qr sign graphic.jpg') }}" style="max-height:355px; width:100%;" />
                                        <div> 
                                        	<!--<span class="lg_text green_text" style="position:absolute;">2</span> 
                                            <span class='dot green_text' style="position:absolute;">.</span>
                                            <span class="md_text green_text" style="position:absolute;">00</span> -->
                                            <span class="contact_no green_text" style="position:absolute;">
                                            	@if(!empty($user->towing_company_number)) {{$user->towing_company_number}} @endif
                                            </span>  
                                        </div>
                                    </div>
                                    <div class="col-xs-10 col-sm-5 col-md-4 col-lg-4 mobile_height_391" style="margin-top:14px;">
                                    	<p class="light_bold"> Confirm Shipping Information </p>
                                         
                                        <div class="form-group">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="text" class="form-control" name="address" value="@if(!empty($user->street)) {{$user->street}}@endif" placeholder="Address">
                                            </div>
                                        </div>
                                        <input type="hidden" name="country_list" value="2" id="country_list" />
                                        <div class="form-group">
                                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                <input type="text" class="form-control" name="postal_code" value="@if(!empty($user->zip)) {{$user->zip}}@endif" placeholder="Postal Code">
                                            </div>
                                            <div class="col-xs-6 col-sm-6 col-lg-6 col-md-6 ">
                                            	<select name="state" id="user_states" class="form-control">
                                                @foreach($states as $state)
                                                
                                                <option value="{{$state->state_code}}"  @if((!empty($user->state)) and ($state->state_code == $user->state)) selected="selected" @endif > {{ $state->state_code }}</option>
                                                
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
            							
                                        
                                        <div class="form-group">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                            	<input type="text" id="cities" class="form-control" placeholder="City" name="city" value="@if(!empty($user->city_name)) {{$user->city_name}}@endif"/>
                                            	
                                                <div class="html_content"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group" style="margin-top: 20%;">
                                            <div class="input-field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            	<p class="light_bold"> Confirm Towing Service </p>
                                                <span id="loading_img_landownwer" style="display:none;">
                                                    <img src="{{ asset('images/load.gif') }}" width="30px" height="30px">
                                                </span>
                                                <div class="towing_companies">
                                                	
                                                	@if(isset($user->towing_company) and ($user->towing_company != "0" and $user->towing_company != ""))
                                                        <select name='towing_companies' class='form-control' required> 
                                                            <option value=''> Select Towing Service </option>
                                                            @if( count($towing_companies) )
                                                                @foreach( $towing_companies as $company )
                                                                    <option value='{{ $company->id }}' @if($user->towing_company == $company->company ) selected = "selected" @endif> {{$company->company}} </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    @endif 
                                                </div>
                                            </div>
                                           <?php /* @if(!isset($user->towing_company) || $user->towing_company == "0" || $user->towing_company == "")*/ ?>
                                                <div class="company_contact_number  col-lg-12 col-md-12 col-sm-12 col-xs-12" @if(!isset($user->towing_company) || $user->towing_company == "0" || $user->towing_company == "") style="display:block;" @else style="display:none;" @endif >
                                                    <input type="text" name='towing_contact' class="form-control disableAutoComplete" placeholder="Towing Service Phone Number"  autocomplete="off" value="@if(!empty($user->towing_company_number)) {{$user->towing_company_number}}@endif"/>
                                                </div>
                                           <?php /*@endif*/ ?>
										   
                                        
										</div>
                                        
                                        
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-2 col-lg-2 park_view_next">
                                        <a class="btnNext" data-id="2" style='box-shadow:none;'>
                                            <span class="cust_arw hidden-xs hidden-sm">NEXT</span><i class="fa fa-angle-right icon cust_arw"></i>
                                        </a>
                                    </div>
                                </div>
                             </div>
                         </div>
            		</div>
                </div>
            	 
                <div class="clearfix"></div>
        </div>
        
    </form>

</div>

<style>
/*.tab-pane{
border:3px solid red;
}
.bottom_align{
border:3px solid green;
}
.btnNext{
border:1px solid blue;
}
.btnBack{
border:1px solid black;
}*/
</style>
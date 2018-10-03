<script>  var config_meter_base_price = {{ config("meter_base_price") }}; var promo_code_discount = {{ config("promo_code_discount") }}; </script>

			<?php $promo_code= ''; if(isset($user->promo_code) && !empty($user->promo_code)){ $promo_code = $user->promo_code; } ?>

			<!--<div class="modal-header">
			
				<h4 class="modal-title text-center"></h4>

			</div>-->
           
                <div class="tabbale"> <!-- Only required for left/right tabs -->
                    <ul class="nav nav-tabs steps_list">
                        <li class="step1 active"><a href="#step1" data-toggle="tab">Step 1</a></li>
                        <li class="step2 ui-state-disabled" ><a href="#step2" >Step 2</a></li><!-- data-toggle="tab" -->
                        <li class="step3 ui-state-disabled"><a href="#step3">Step 3</a></li>
                       	<button type="button" class="close" data-dismiss="modal" style="padding:10px;">×</button>
                    </ul>
           
            
                    <form class="form-horizontal text-center" role="form" method="POST" action="{{ URL::to('/home/newmeter_signup') }}" id="activation_steps" autocomplete="on">
        				
                        <div class="modal-body activation_steps_body">
                            
                            	<div class="tab-content">
                                
                                   <div class="tab-pane" id="step3">
                                      <div class="form-group">
                                             <div class="devidation_horizontl">
                                                <div class="col-lg-12">
                                                    <div class="col-sm-6 col-md-6 col-lg-6 hide_on_mobile" style="position:relative;">
                                                        <img src="{{ asset('images/custom/qr sign graphic.jpg') }}" class="signage_image" style="width:100%;" />
                                                        <div> 
                                                            <!--<span class="lg_text green_text" style="position:absolute;">2</span> 
                                                            <span class='dot green_text' style="position:absolute;">.</span>
                                                            <span class="md_text green_text" style="position:absolute;">00</span> -->
                                                            <span class="contact_no green_text" style="position:absolute;"></span> 
                                                        </div>
                                                    </div>
                                                    <div id="paypal_form">
                                                        <div class="col-sm-6 col-md-6 col-lg-6 mobile_height_391" style="margin-top:14px;">
                                                          <h3 class='order_summry_text margin-bottom0'> Order Summary </h3>
                                                          <div class='col-md-12 bottom-border_text padding_left-right0'>
                                                          
                                                          	
                                                            <div class="form-group col-md-12 padding_left-right0 margin_left-right0">
                                                               
                                                               <h5 class='margin-top-bottom8 text-align_left' style="margin-bottom:0;"> 
                                                               	<span class="bold_text small_text">Quantity: </span>
                                                                <span class="meter_count grey_text">1 Parking Sign</span>
                                                                <span class="bold_text small_text"> (x ${{config("meter_base_price")}})</span>
                                                               </h5>
                                                               <div class="col-xs-8 col-sm-8 col-md-8 text-align_left padding_left-right0">
                                                                    <h5 class='margin-top-bottom8'> 
                                                                    	<span class="bold_text small_text">Sub Total: </span>
                                                                        <span class="sub_count grey_text">${{config("meter_base_price")}}</span>
                                                                        <span class="bold_text"></span> 
                                                                    </h5>
                                                                    <h5 class='margin-top-bottom8'> 
                                                                    	<span class="bold_text small_text">Shipping: </span>
                                                                        <span class="shipping_price grey_text">${{config("ship_price_lo")}}</span>
                                                                    </h5>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-4 col-md-4 padding_left-right0 pull-right">
                                                                    <input type="text" class="form-control text-center disableAutoComplete" name="promo_code" value="{{ $promo_code }}" placeholder="Promo Code" style='margin-top:10px;font-size: 12px;padding: 3px;'>
                                                                    <input type="hidden" name="refered_by" value="" />
                                                                    <input type="hidden" name="commission" value="" />
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
	                                                                    <span class="payable_amount grey_text">$<?php echo $price = config("meter_base_price")+config("ship_price_lo"); ?></span>
                                                                        <span class="bold_text"></span> 
                                                                     </h4>
                                                                    <input type="hidden" name="payable_amount" class="payable_amount" value="{{$price}}" />
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
                                    
                                                                            
                                                                            <!--<option>2016</option>-->
                                    
                                                                            
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
                                                            <div class="form-group light_bold margin_left-right0 text-align_left " style="padding:3px; @if(isset($promo_code) && $promo_code != '') display:none; @endif">
                                                                <input type="checkbox" name="is_agreed" value="1" class="btn btn-success" style="margin:0;" checked="checked" required="required" /> 			 																<a class="owner_agreement" style="color: rgb(126, 217, 50); cursor:pointer;"> Agree to Terms & Conditions </a>
                                                                <!--<input type="hidden" name="agree" class="agree" value="0" />-->
                                                            </div>
                                                            
                                                             <div class="form-group">
                                                                <div class="col-md-12">
                                                                    <!--<input type="submit" class="btn btn-primary" name="place_order" value="Place Order" placeholder="Place Order" required="">													-->
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>
                                         </div>
                                      <div class="form-group">
                                      		<div class="col-lg-12">
                                                <div class="col-xs-5 col-sm-3 col-md-3 col-lg-3"><a class="btnBack" style='box-shadow:none;'><img src="{{ asset('images/back.png') }}"></a></div>
                                                <div class="col-sm-3 col-md-3 col-lg-5"></div>
                                                <div class="col-xs-7 col-sm-6 col-md-6 col-lg-4">
                                                    <div class='f_right checkout_process'>
                                                   
                                                         <input type="image" src="{{ asset('images/place_order.png') }}" alt="Submit" id="proceed_checkout" name="proceed_checkout" value="proceed" data-id="3" />
                                                         <!--<button id ="apple-pay-button" lang="en" style="-webkit-appearance: -apple-pay-button; -apple-pay-button-type: buy;"></button>-->
                                                     </div>    	
                                                </div>
                                             </div>
                                         </div>
                                       
                                   </div>
                                   
                            
                            		<input type="hidden" name="_token" value="{{ csrf_token() }}">
                            
                                   
        
        							<div id="step1" class="tab-pane active">
                                    	
                                        	<div class="form-group">
                                            <div class='devidation_horizontl'>
                                            	<div class="col-lg-12">
                                                    <div class="col-sm-6 col-md-6 col-lg-6 hide_on_mobile" style="position:relative;">
                                                        <img src="{{ asset('images/custom/qr sign graphic.jpg') }}" style="width:100%;" />
                                                        <div> 
                                                            <!--<span class="lg_text green_text" style="position:absolute;">2</span> 
                                                            <span class='dot green_text' style="position:absolute;">.</span>
                                                            <span class="md_text green_text" style="position:absolute;">00</span>-->
                                                            <span class="contact_no green_text" style="position:absolute;"></span>  
                                                        </div>
                                                    </div> 
                                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 only_mobile_height" style="position:relative; margin-top:14px;"> <!-- mobile_height_391 -->
                                                        <!--<h3> Activate Your Account </h3>
                                                        <hr />-->
                                                        <div class="form-group" style="">
                                                            <label class="col-md-12">How many parking signs would you like to order?</label>
                                                            <div class="col-md-12">
                                                                <div class="col-xs-8 col-xs-offset-2 col-lg-8 col-lg-offset-2">
                                                                    <input type="number" class="form-control text-center" id="parking_meter_count" name="parking_meter_count" value="1" required="required" placeholder="No. of Signs" min="1" />
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
                                                </div>
                                        	</div>
                                            <div class="form-group">
                                            	<div class="col-lg-12">
                                                    <div class="col-xs-5 col-sm-3 col-md-3 col-lg-3"></div>
                                                    <div class="col-xs-2 col-sm-6 col-md-6 col-lg-6"></div>
                                                    <div class="col-xs-5 col-sm-3 col-md-3 col-lg-3"><a class="btnNext step1_next" data-id="1" style='box-shadow:none; '><img src="{{ asset('images/next.png') }}"></a></div>
                                                </div>
                                             </div>
                                        
                						
                                    </div>
                                    
                            		<div id="step2" class="tab-pane">
                                    	
	                                        
                                             <div class="form-group">
                                                 <div class="devidation_horizontl">
                                                 	<div class="col-lg-12">
                                                        <div class="col-sm-6 col-md-6 col-lg-6 hide_on_mobile" style="position:relative;">
                                                            <img src="{{ asset('images/custom/qr sign graphic.jpg') }}" style="width:100%;" />
                                                            <div> 
                                                            	<!--<span class="lg_text green_text" style="position:absolute;">2</span> 
                                                                <span class='dot green_text' style="position:absolute;">.</span>
                                                                <span class="md_text green_text" style="position:absolute;">00</span> -->
                                                                <span class="contact_no green_text" style="position:absolute;"></span>  
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-md-6 col-lg-6 mobile_height_391" style="margin-top:14px;">
                                                        	<p class="light_bold"> Contact & Shipping Information </p>
                                                            <!--<hr />-->
                                                            <div class="form-group">
                                                                <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                    <input type="text" name="name" required="required" class="form-control" placeholder="First Name" value="">
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                                                                    <input type="text" name="l_name" required="required" class="form-control" placeholder="Last Name" value="">
                                                                </div>
                                                             </div>
                                                            
                                                            <div class="form-group">
                                                                <!--<label class="control-label">
                                                                    Email
                                                                </label>-->
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                    <input type="email" name="email" required="required" class="form-control" placeholder="Email" value="">
                                                                </div>
                                                             </div>
                                                            <div class="form-group">
                                                                <!--<label class="control-label">
                                                                    Confirm Email
                                                                </label>-->
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <input type="email" name="confirm_email" required="required" class="form-control" placeholder="Confirm Email" value="">
                                                                </div>
                                                             </div>
                                                             
                                                            <div class="form-group">
                                                                <!--<label class="control-label">ADDRESS</label>-->
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <input type="text" class="form-control" name="address" value="" placeholder="Address">
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="country_list" value="2" id="country_list" />
                                                            <div class="form-group">
                                                               <!-- <label class="control-label">POSTAL CODE</label>-->
                                                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                                                    <input type="text" class="form-control" name="postal_code" value="" placeholder="Postal Code">
                                                                </div>
                                                                <div class="col-xs-6 col-sm-6 col-lg-6 col-md-6 ">
                                                                	<select name="state" id="state_list" class="form-control">
                                                                    	<option value="">Province</option>  
                                                                    </select>
                                                                    <!--<input type="text" class="form-control" name="province" value="" placeholder="Province"> -->
                                                                </div>
                                                            </div>
                                                            
                                							
                                                            
                                                            <div class="form-group">
                                                                <!--<label class="control-label">CITY</label>-->
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                                	<input type="text" id="cities" class="form-control" placeholder="City" name="city" value="" />
                                                                	<!-- <select name="city" class="form-control" placeholder="City" id="city_list"> <option> Select City</option>
                                                                	<?php 
																	/*if(isset($data->cities)){
																		foreach($data->cities as $city)
																		{
																			echo "<option value='".$city["city_name"]."'>".$city["city_name"]."</option>";
																		}
																	}*/
																	?>-->
                                                                    </select>
                                                                   <div class="html_content"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> {{-- input-field  --}}
                                                                    <span id="loading_img_landownwer" style="display:none;">
                                                                        <img src="{{ asset('images/load.gif') }}" width="30px" height="30px">
                                                                    </span>
                                                                    <div class="towing_companies">
                                                                        <!--<select name='towing_companies' class="form-control" required> </select>
                                                                        <input type="text" name='towing_companies' class="form-control" placeholder="Towing Company" required />-->
                                                                    </div>
                                                                </div>
                                                               
                                                                <div class="company_contact_number  col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <!--<label class="control-label">Contact Number</label>-->
                                                                    <input type="text" name='towing_contact' class="form-control disableAutoComplete" placeholder="Towing Service Phone Number"  autocomplete="off" value="" />
                                                                </div>
                                                            
															</div>
                                                            
                                                           
                                                             
                                                        </div>
                                                    </div>
                                                 </div>
                                             </div>
                                        
                                        
                                       
                                        	 <div class="form-group">
                                             	<div class="col-lg-12">
                                                    <div class="col-xs-5 col-sm-3 col-md-3 col-lg-3"><a class="btnBack" style='box-shadow:none;'><img src="{{ asset('images/back.png') }}"></a></div>
                                                    <div class="col-xs-2 col-sm-6 col-md-6 col-lg-6"></div>
                                                    <div class="col-xs-5 col-sm-3 col-md-3 col-lg-3"><a class="btnNext" data-id="2" style='box-shadow:none;'><img src="{{ asset('images/next.png') }}"></a></div>
                                                 </div>
                                             </div>
                                        
                            		</div>
        
                                    
        
        
                             
                                 </div>
                            	 
                            
                            
                            <div class="clearfix"></div>
        
                        </div>
				        
                    </form>
			 
				</div>
           
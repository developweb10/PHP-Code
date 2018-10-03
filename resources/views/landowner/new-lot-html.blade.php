<div id="newLotModal" class="modal fade @if( isset( $first_meter ) and $first_meter ) landowner_first_UI @endif" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">
		@if( !(isset( $first_meter ) and $first_meter) )
			<div class="modal-header">

				@if( !(isset( $first_meter ) and $first_meter) )<button type="button" class="close" data-dismiss="modal">&times;</button>@endif

				<h4 class="modal-title text-center">

                	@if( isset( $first_meter ) and $first_meter )

                    	<!--Create Your First Meter-->

                    @else

                        Create New Group

                    @endif

                </h4>

			</div>
            @endif
            
            @if( isset( $first_meter ) and $first_meter )
                <div class="tabbable"> <!-- Only required for left/right tabs -->
                    <ul class="nav nav-tabs">
                        <li class="step1 active"><a href="#step1" data-toggle="tab">Step 1</a></li>
                        <li class="step2"><a href="#step2" data-toggle="tab">Step 2</a></li>
                        <li class="step3"><a href="#step3" data-toggle="tab">Step 3</a></li>
                        <li class="step4"><a href="#step4" data-toggle="tab">Step 4</a></li>
                    </ul>
            @endif
            
                    <form class="form-horizontal text-center" role="form" method="POST" action="{{ URL::to('/home/newlot') }}">
        
                        <div class="modal-body">
                            @if( isset( $first_meter ) and $first_meter )
                            	<div class="tab-content">
                                   <div class="tab-pane" id="step3">
                                       <div class="form-group">
                                         <div class="">
                                            <div class="col-lg-12">
                                                <div class="col-lg-6" style="position:relative;">
                                                    <img src="/images/custom/sign graphic.jpg" style="width:100%;" />
                                                    <div> 
                                                        <span class="lg_text green_text" style="position:absolute;"></span> 
                                                        <span class='dot green_text' style="position:absolute;"></span>
                                                        <span class="md_text green_text" style="position:absolute;"></span>
                                                        <span class="contact_no green_text" style="position:absolute;"></span>  
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                	<h3> Towing Company's Detail </h3>
                                                    <hr />
                                                    <div class="input-field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    	 <label class="control-label">Towing Company</label>
                                                        <div class="">
                                                            <!--<select name='towing_companies' class="form-control" required> </select>-->
                                                            <input type="text" name='towing_companies' class="form-control" placeholder="Towing Company" required />
                                                        </div>
                                                    </div>
                                                    <div class="input-field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    	 <label class="control-label">Contact Number</label>
                                                        <div class="">
                                                            <input type="text" name='towing_contact' class="form-control" placeholder="Contact Number" required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                         </div>
                                     </div>
                                       @if( isset( $first_meter ) and $first_meter )
                                       		<div class="form-group bottom_align">
                                             	<div class="col-lg-3"><a class="btn btn-primary btnBack" >Back</a></div>
                                                <div class="col-lg-6"></div>
	                                         	<div class="col-lg-3"><a class="btn btn-primary btnNext" >Next</a></div>
                                             </div>
                                       @endif
                                   </div>
                                   <div class="tab-pane" id="step4">
                                   		@if( isset( $first_meter ) and $first_meter )
	                                        
                                            <div class="form-group">
                                             <div class="">
                                                <div class="col-lg-12">
                                                    <div class="col-lg-6" style="position:relative;">
                                                        <img src="/images/custom/sign graphic.jpg" style="width:100%;" />
                                                        <div> 
                                                            <span class="lg_text green_text" style="position:absolute;"></span> 
                                                            <span class='dot green_text' style="position:absolute;"></span>
                                                            <span class="md_text green_text" style="position:absolute;"></span> 
                                                            <span class="contact_no green_text" style="position:absolute;"></span> 
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <h3> Payment Details </h3>
                                                        <hr />
                                                        <div class="form-group">
															<label class="col-md-12 control-lable"></label>
															<div class="col-md-12 text-left"><img src="/images/card_types.png" class="cart_types_img margin_none" border="0"></div>
                                                        </div>
                                                        <div class="form-group">
															<div class="col-md-12">
                    											<input type="text" class="form-control text-center" name="cc_number" value="" placeholder="Card Number" required="">
                    										</div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-6">
                                                                <!--<input type="button" class="sleectText" value=" Expiry Month " style="font-size:16px; width:80%;">-->
                                                                <div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i></div>
                                                                <select name="expiry_month" class="form-control select_center_text" style="text-align-last:center;" required="required">
                            
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
                                                            <div class="col-md-6">
                                                                <!--<input type="button" class="sleectText" value=" Expiry Year " style="font-size:16px; width:80%;">-->
                                                                <div class="drop_icon"><i class="fa fa-caret-down" aria-hidden="true"></i></div>
                                                                <select name="expiry_year" class="form-control select_center_text" style="text-align-last:center;" required="required">
                                
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
                                                        <div class="form-group">
															<div class="col-md-12">
                    											<input type="text" class="form-control text-center" name="promo_code" value="" placeholder="Promo Code" required="">
                    										</div>
                                                        </div>
                                                         <div class="form-group">
															<div class="col-md-12">
                    											<input type="submit" class="btn btn-primary" name="place_order" value="Place Order" placeholder="Place Order" required="">
                    										</div>
                                                        </div>
                                                   	</div>
                                                </div>
                                             </div>
                                         </div>
                                         <div class="form-group bottom_align">
                                            <div class="col-lg-3"><a class="btn btn-primary btnBack" >Back</a></div>
                                            <div class="col-lg-6"></div>
                                            <div class="col-lg-3"><a class="btn btn-primary btnNext" >Next</a></div>
                                         </div>
                                        @endif
                                       
                                   </div>
                                   
                            @endif
                            		<input type="hidden" name="_token" value="{{ csrf_token() }}">
                            
                                    @if( !(isset( $first_meter ) and $first_meter) )
                                    	<div class="form-group">
                
                                            <label class="col-md-4 control-label">Group Name</label>
                    
                                            <div class="col-md-6">
                    
                                                <input type="text" class="form-control" required name="lot_name" value="" placeholder="Group Name" >
                    
                                            </div>
                    
                                        </div>
                                    @endif
        
        							<div id="step1" class="tab-pane active">
                                    	@if( isset( $first_meter ) and $first_meter )
                                        	<div class="form-group">
                                            	<div class="col-lg-12">
                                                    <div class="col-lg-6" style="position:relative;">
                                                        <img src="/images/custom/sign graphic.jpg" style="width:100%;" />
                                                        <div> 
                                                            <span class="lg_text green_text" style="position:absolute;"></span> 
                                                            <span class='dot green_text' style="position:absolute;"></span>
                                                            <span class="md_text green_text" style="position:absolute;"></span>
                                                            <span class="contact_no green_text" style="position:absolute;"></span>  
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6" style="position:relative;">
                                                        <h3> Activate Your Account </h3>
                                                        <hr />
                                                        <div class="form-group" style="margin-top:30px;">
                                                            <label class="col-md-12">How many parking meters would you like to order?</label>
                                                            <div class="col-md-12">
                                                                <div class="col-lg-12">
                                                                    <input type="text" class="form-control text-center" id="parking_meter_count" name="parking_meter_count" value="" required="" placeholder="No. of Meters" min="1" max="999" pattern="[0-9]{3}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                @endif
                                                
                                                       <div class="form-group" >
                            
                                                    <label class="@if( isset( $first_meter ) and $first_meter ) col-md-12 @else col-md-4 control-label @endif">
                                                        @if( isset( $first_meter ) and $first_meter )
                                                            What Hour rate will you charge?
                                                        @else
                                                            Select an Hourly Rate
                                                        @endif
                                                    </label>
                            
                                                    <div class="@if( isset( $first_meter ) and $first_meter ) col-md-12 @else col-md-6 @endif">
                            
                                                        <div class="@if( isset( $first_meter ) and $first_meter ) col-lg-12 @else col-md-9 row @endif ">
                            
                                                            <select class="form-control" name="price" required>
                            
                                                            <option value="">Hourly Rate</option>
                            
                                                            <option value="2.00">$2.00</option>
                            
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
                            
                                                           <!-- <option value="10.00">$10.00</option>-->
                            
                                                        </select>
                            
                                                        </div>
                            
                                                    </div>
                            
                                                </div>
                                                    @if( isset( $first_meter ) and $first_meter )
                                                    <hr />
                                                    
                                                    @endif 
                                                
                                                @if( isset( $first_meter ) and $first_meter )
                                                    <div class="form-group">
                                                        <label class="col-md-12"> Estimated Monthly Earnings : </label> $<span class="expected_amount"></span><br />
                                                        <span class="calculation_desc"></span>
                                                    </div><!--- End of col-lg-6 --->
                                                </div>
                                            	</div>
                                        	</div>
                                            <div class="form-group bottom_align">
                                             	<div class="col-lg-3"><a class="btn btn-primary btnBack" >Back</a></div>
                                                <div class="col-lg-6"></div>
	                                         	<div class="col-lg-3"><a class="btn btn-primary btnNext" >Next</a></div>
                                             </div>
                                        @endif
                						
                                    </div>
                                    
                            		<div id="step2" class="tab-pane">
                                    	@if( isset( $first_meter ) and $first_meter )
	                                        
                                             <div class="form-group">
                                                 <div class="">
                                                 	<div class="col-lg-12">
                                                        <div class="col-lg-6" style="position:relative;">
                                                            <img src="/images/custom/sign graphic.jpg" style="width:100%;" />
                                                            <div> 
                                                            	<span class="lg_text green_text" style="position:absolute;"></span> 
                                                                <span class='dot green_text' style="position:absolute;"></span>
                                                                <span class="md_text green_text" style="position:absolute;"></span> 
                                                                <span class="contact_no green_text" style="position:absolute;"></span>  
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                        	<h3> Shipping Details</h3>
                                                            <hr />
                                                            
                                                            <div class="input-field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="control-label">ADDRESS</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" name="address" value="" placeholder="Address">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="input-field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="control-label">POSTAL CODE</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" name="postal_code" value="" placeholder="Postal Code">
                                                                </div>
                                                            </div>
                                							
                                                            <div class="input-field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="control-label">CITY</label>
                                                                <div class="">
                                                                	<select name="city" class="form-control" placeholder="City">
                                                                	<?php foreach($cities as $city)
                                                                    {
                                                                        echo "<option value='".$city["city_name"]."'>".$city["city_name"]."</option>";
                                                                    }
																	?>
                                                                    </select>
                                                                   <div class="html_content"></div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="input-field col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <label class="control-label">PROVINCE</label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" name="province" value="" placeholder="Province">
                                                                </div>
                                                            </div>
                                                            
                                                           
                                                             
                                                        </div>
                                                    </div>
                                                 </div>
                                             </div>
                                        @else
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Location</label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" required name="lot_address" value="" placeholder="Street Address" >
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if( isset( $first_meter ) and $first_meter )
                                        	 <div class="form-group bottom_align">
                                             	<div class="col-lg-3"><a class="btn btn-primary btnBack" >Back</a></div>
                                                <div class="col-lg-6"></div>
	                                         	<div class="col-lg-3"><a class="btn btn-primary btnNext" >Next</a></div>
                                             </div>
                                        @endif
                            		</div>
        
                                    
        
        
                             @if( isset( $first_meter ) and $first_meter )
                                 </div>
                            	 
                            @endif
                            
                            <div class="clearfix"></div>
        
                        </div>
				        @if( !(isset( $first_meter ) and $first_meter) )
                            <div class="modal-footer">    
                                <button type="submit" class="btn btn-primary"> Create </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
                            </div>
        		 		@endif
                    </form>
			 @if( isset( $first_meter ) and $first_meter )
				</div>
             @endif
		</div>

	</div>

</div>
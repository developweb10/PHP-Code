<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default"> 
		<div class="panel-heading">New Registration Email</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="automated_emails" class="btn btn-default">Save</button>
				</div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <strong>INSTRUCTIONS:</strong> Use following snippets to print dynamic content in body:
                    <br />
                    <p>1) Use [[user_email]] to print User email. </p>
                    <p>2) Use [[user_password]] to print User Password.</p>
                    <p>3) Use [[click_here_link]] to create Click here link.</p>
                    <p>4) Use [[user_name]] to print Username.</p>
                    <p>5) Use [[order_summary]] to print Order Summary.</p>
                    <br />
                </div>
                
                <div role="tabpanel" class="tab-pane hide @if ($vars['tab'] === 'landowners_emails') active @endif " id ="landowners_emails">
                    <!-- Register Email -->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Subject for Landowners (Login Details)</label>
                        <div>
                            <input type="text" name="registration[subject]" class="form-control" value="{{ $vars['automated_emails']['registration']['subject'] or "" }}" >
                        </div>
                    </div>
    
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Body for Landowners</label>
                        <div>
                            <textarea name="registration[body]" class="text-editor" style="height:200px;" >{{ $vars['automated_emails']['registration']['body'] or "" }}</textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <hr />
                
                    <!-- Register with Order Placement -->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Subject for Landowners (New Clients with Order Summary)</label>
                        <div>
                            <input type="text" name="registration_order[subject]" class="form-control" value="{{ $vars['automated_emails']['registration_order']['subject'] or "" }}" >
                        </div>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Body for Landowners (With Order Summary)</label>
                        <div>
                            <textarea name="registration_order[body]" class="text-editor" style="height:200px;" >{{ $vars['automated_emails']['registration_order']['body'] or "" }}</textarea>
                        </div>
                    </div>
                    
                     <div class="clearfix"></div>
                    <br />
                    <hr />
                
                    <!-- Order Placement -->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Subject for Landowners (New signs for existing Clients)</label>
                        <div>
                            <input type="text" name="order_placement[subject]" class="form-control" value="{{ $vars['automated_emails']['order_placement']['subject'] or "" }}" >
                        </div>
                    </div>
    
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Body for Landowners</label> 
                        <div>
                            <textarea name="order_placement[body]" class="text-editor" style="height:200px;" >{{ $vars['automated_emails']['order_placement']['body'] or "" }}</textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <hr />
                 
                    <!-- Order Replacement -->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Subject for Landowners (Replacement signs for existing Clients)</label>
                        <div>
                            <input type="text" name="order_replacement[subject]" class="form-control" value="{{ $vars['automated_emails']['order_replacement']['subject'] or "" }}" >
                        </div>
                    </div>
    
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Body for Landowners</label> 
                        <div>
                            <textarea name="order_replacement[body]" class="text-editor" style="height:200px;" >{{ $vars['automated_emails']['order_replacement']['body'] or "" }}</textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <hr />
                
                <!-- Hire Meter Start -->
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Subject for Landowners (Meter Hired)</label>
                        <div>
                            <input type="text" name="hire_meter[subject]" class="form-control" value="{{ $vars['automated_emails']['hire_meter']['subject'] or "" }}" >
                        </div>
                    </div>
                    
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Body for Landowners</label> 
                        <div>
                            <textarea name="hire_meter[body]" class="text-editor" style="height:200px;" >{{ $vars['automated_emails']['hire_meter']['body'] or "" }}</textarea>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br />
                    <hr />
                    </div>     
                
                <!--Hire Meter End -->
                
                <div role="tabpanel" class="tab-pane hide @if ($vars['tab'] === 'sa_emails') active @endif " id ="sa_emails">
                
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Subject for SA</label>
                        <div>
                            <input type="text" name="registration_sa[subject]" class="form-control" value="{{ $vars['automated_emails']['registration_sa']['subject'] or "" }}" >
                        </div>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Body for SA</label>
                        <div>
                            <textarea name="registration_sa[body]" class="text-editor" style="height:200px;" >{{ $vars['automated_emails']['registration_sa']['body'] or "" }}</textarea>
                        </div>
                    </div>
         
          			<div class="clearfix"></div>
                    <br />
                    <hr />
				
                </div>
                
                <div role="tabpanel" class="tab-pane hide @if ($vars['tab'] === 'sm_emails') active @endif " id ="sm_emails">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Subject for SM</label>
                        <div>
                            <input type="text" name="registration_sm[subject]" class="form-control" value="{{ $vars['automated_emails']['registration_sm']['subject'] or "" }}" >
                        </div>
                    </div>
    
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label">Body for SM</label>
                        <div>
                            <textarea name="registration_sm[body]" class="text-editor" style="height:200px;" >{{ $vars['automated_emails']['registration_sm']['body'] or "" }}</textarea>
                        </div>
                    </div>
         		</div>
                
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="automated_emails" class="btn btn-default">Save</button>
				</div>


			</form>
		</div>
	</div>
</div>
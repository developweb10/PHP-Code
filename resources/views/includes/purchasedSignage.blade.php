@if( !isset($vars['show_layout']) or ( isset($vars['show_layout']) and $vars['show_layout'] === true ) )
<div class="container-fluid">
	<div class="container-fluid">
    	<div class="row">
            <div class="col-md-12">
            	<div class="panel panel-default">
                	<div class="panel-body">
                    	<div class="col-md-10 col-md-offset-1">
                        	<div class="col-md-5">
                            	<div class="col-md-2">
                                	<input type="checkbox" name="signage_images" value="img1" />
                                    <img src="https://my-meter.com/images/custom/20160517082012signage_image.jpg" width="100px" height="100px" border="0">
                                </div>
                                <div class="col-md-2">
                                	<input type="checkbox" name="signage_images" value="img2" />
                                    <img src="https://my-meter.com/images/custom/signage_latest.jpg" width="100px" height="100px" border="0">
                                </div>
                            </div>
                            <div class="col-md-5">
                            	
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
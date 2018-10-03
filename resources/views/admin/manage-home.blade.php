<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">Manage content for Home Page</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal" enctype="multipart/form-data" >
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="home" class="btn btn-default">Save</button>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="{{ URL::to('/landing-home') }}" target="_blank" class="btn btn-default" title="Changes will reflect after you save them.">View Page</a>
				</div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <strong>INSTRUCTIONS:</strong> Use following snippet to activate the order form:
                    <br>
                    <p>1) Wrap the text with [[get_form_data]] [[/get_form_data]]. </p>
                    <br>
                </div>

				<div class="clearfix"></div>
				




				<!-----SECTION 1----->
				<!--<div class="panel panel-default">
					<div class="panel-heading">Top Section</div>
					<div class="panel-body">
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Heading</label>
							<div>
								<input type="text" name="section1[heading]" class="form-control" value="{{ $vars['home']['section1']['heading'] or "" }}" >
							</div>
						</div>
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Description</label>
							<div>
								<textarea name="section1[description]" class="text-editor" style="width:100%; height:100px;" >{{ $vars['home']['section1']['description'] or "" }}</textarea>
							</div>
						</div>
					</div>
				</div>
				-->

				<!-----SECTION 2----->
				<div class="panel panel-default">
					<div class="panel-heading">Section 1</div>
					<div class="panel-body">
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Heading</label>
							<div>
								<input type="text" name="section2[heading]" class="form-control" value="{{ $vars['home']['section2']['heading'] or "" }}" >
							</div>
						</div>
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Description</label>
							<div>
								<textarea name="section2[description]" class="text-editor" style="width:100%; height:100px;" >{{ $vars['home']['section2']['description'] or "" }}</textarea>
							</div>
						</div>
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Image</label>
							<div>
								<input type="file" name="section2_image"  >
								<input type="hidden" name="section2[image]" value="{{ $vars['home']['section2']['image'] or "" }}" />
								@if( isset($vars['home']['section2']['image']) and $vars['home']['section2']['image'] != '' )<br /><img alt="Home Section2" src="{{ $vars['home']['section2']['image'] }}" style="max-width:200px;" border="0"  />@endif
							</div>
						</div>
					</div>
				</div>
					
					
					<!-----SECTION 3----->
				<div class="panel panel-default">
					<div class="panel-heading">Section 2</div>
					<div class="panel-body">
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Heading</label>
							<div>
								<input type="text" name="section3[heading]" class="form-control" value="{{ $vars['home']['section3']['heading'] or "" }}" >
							</div>
						</div>
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Description</label>
							<div>
								<textarea name="section3[description]" class="text-editor" style="width:100%; height:100px;" >{{ $vars['home']['section3']['description'] or "" }}</textarea>
							</div>
						</div>
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Image</label>
							<div>
								<input type="file" name="section3_image"  >
								<input type="hidden" name="section3[image]" value="{{ $vars['home']['section3']['image'] or "" }}" />
								@if( isset($vars['home']['section3']['image']) and $vars['home']['section3']['image'] != '' )<br /><img alt="Home Section" src="{{ $vars['home']['section3']['image'] }}" style="max-width:200px;" border="0"  />@endif
							</div>
						</div>
					</div>
				</div>
                

                <!---- Section 4 ---->
                <div class="panel panel-default">
					<div class="panel-heading">Section 3</div>
					<div class="panel-body">
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Heading</label>
							<div>
								<input type="text" name="section6[heading]" class="form-control" value="{{ $vars['home']['section6']['heading'] or "" }}" >
							</div>
						</div>
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Description</label>
							<div>
								<textarea name="section6[description]" class="text-editor" style="width:100%; height:100px;" >{{ $vars['home']['section6']['description'] or "" }}</textarea>
							</div>
						</div>
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Image</label>
							<div>
								<input type="file" name="section6_image"  >
								<input type="hidden" name="section6[image]" value="{{ $vars['home']['section6']['image'] or "" }}" />
								@if( isset($vars['home']['section6']['image']) and $vars['home']['section6']['image'] != '' )<br /><img alt="Home Section6" src="{{ $vars['home']['section6']['image'] }}" style="max-width:200px;" border="0"  />@endif
							</div>
						</div>
					</div>
				</div>
                
                <!-----Home Slider----->
				<div class="panel panel-default">
					<div class="panel-heading">Home Slider</div>
					<div class="panel-body">
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Select Image (Please choose the images with min dimensions 1349x848) </label>
							<div>
	                            <!------------------------------------ For uploading multiple images for slider -------------------------------------------->

								<input type="file" name="section0_image" multiple  /> <!-- section0_image[] -->

                                <?php   
							//	if(isset($vars['home']['section0'])) 
							//	print_r($vars['home']['section0']); 
								/*$img_urls = "";
								if(isset($vars['home']['section0']))
								{
									foreach($vars['home']['section0'] as $img)
									{
										if(count($vars['home']['section0']) == 1)
										{
											$img_urls = $img;	
										}
										else
										{
											$img_urls .= $img.",";	
										}
									}
								}*/

								?>
								<input type="hidden" name="section0" id="section0_image" value="<?php if(isset($vars['home']['section0'])) echo $vars['home']['section0']; ?>" /> <?php //if(isset($img_urls)) echo $img_urls; ?>
                                <label class="control-label">Transition in seconds : </label>
                                <input type="text" name="transition" placeholder="20" value="<?php if(isset($vars['home']['transition'])) echo $vars['home']['transition']; ?>" />
                                <?php
								if(isset($vars['home']['section0']))
								{
									$header_slider_url = explode(",", $vars['home']['section0']);
									/*echo '<pre>';
									print_r($header_slider_url);
																		echo '</pre>';*/
									foreach($header_slider_url as $key=>$img)
									{
										if(!empty($img))
										{
										?>
											<div id="section0_image_tag<?php echo $key;?>" class="hedaer_img">
												<br /><img alt='Home Slider' src="<?php echo $img; ?>"  style="max-width:200px;" border="0"  />
												<button type="button" class="btn btn-default btn-sm" onclick="uploaded_images('<?php echo $key;?>')">Remove</button>
											</div>
										<?php
										}
									}







								}
								/*
								if(isset($vars['home']['section0']))
								{
									foreach($vars['home']['section0'] as $key=>$img)
									{

									?>
										<div id="section0_image_tag<?php echo $key;?>" class="hedaer_img">
											<br /><img src="<?php echo $img; ?>"  style="max-width:200px;" border="0"  />



											<button type="button" class="btn btn-default btn-sm" onclick="uploaded_images('<?php echo $key;?>')">Remove</button>
										</div>
									<?php
									}

								}
								*/
									?>
							</div> 
						</div>
					</div>
				</div>
                
                
					<!-----FULL WIDTH IMAGE----->
				<div class="panel panel-default">
					<div class="panel-heading">Full Width Image</div>
					<div class="panel-body">
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Select Image</label>
							<div>
								<input type="file" name="section5_image"  >
								<input type="hidden" name="section5[image]" id="section5_image" value="{{ $vars['home']['section5']['image'] or "" }}" />
								@if( isset($vars['home']['section5']['image']) and $vars['home']['section5']['image'] != '' )
                                	<div id="section5_image_tag">
                                        <br /><img alt="Home Section5" src="{{ $vars['home']['section5']['image'] }}"  style="max-width:200px;" border="0"  />
                                        <button type="button" class="btn btn-default btn-sm" onclick="document.getElementById('section5_image').value='';document.getElementById('section5_image_tag').remove();">Remove</button>
                                    </div>
                                @endif
							</div>
						</div>
					</div>
				</div>
				
				<!-----BOTTOM SECTION----->
				<div class="panel panel-default">
					<div class="panel-heading">Bottom Section</div>
					<div class="panel-body">
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Heading</label>
							<div>
								<input type="text" name="section4[heading]" class="form-control" value="{{ $vars['home']['section4']['heading'] or "" }}" >
							</div>
						</div>
						<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label class="control-label">Description</label>
							<div>
								<textarea name="section4[description]" class="text-editor" style="width:100%; height:100px;" >{{ $vars['home']['section4']['description'] or "" }}</textarea>
							</div>
						</div>
					</div>
				</div>





				<div class="clearfix"></div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="home" class="btn btn-default">Save</button>
				</div>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>

<script>
	function uploaded_images(img_key)
	{
		var updated_slider_urls = [];
		var urls = document.getElementById('section0_image').value; 
	//	alert(urls);
        var urls_array = urls.split(',');
		console.log(urls_array);
		var urls_array = urls_array.filter(function(x){
		  return (x !== (undefined || ''));
		});
		//alert(Object.prototype.toString.call(urls_array)); 
		//alert(Object.keys(urls_array).length);
		console.log(urls_array);
		//alert("count -- ".urls_array.size());
		//console.log("_____"+urls_array["1"]);
		
		//console.log("++++"+urls_array[1]);
		console.log(urls_array.length);
		for(var i = 0; i < urls_array.length; i++) {
		   //alert(urls_array.i);
		  //console.log("++++"+urls_array[1]);
		   urls_array[i] = urls_array[i].replace(/^\s*/, "").replace(/\s*$/, ""); 
		   console.log("Key_ - "+i+" Value is -- "+urls_array[i]);
		  
		}
		
		
		if(urls_array[img_key] !== (undefined || ''))
	   {
		  // alert("Image Key -- "+img_key);
			//alert("delete this -- "+urls_array[img_key]);
		   urls_array[img_key] = "";
	   }
		   
		var urls_array = urls_array.filter(function(x){
		  return (x !== (undefined || ''));
		});
		
		//alert("Count -- "+urls_array.length);
		
		var updated_url = "";
		
		for(var i = 0; i < Object.keys(urls_array).length; i++) {
			 updated_slider_urls.push(urls_array[i]);
			
		}
		updated_url = updated_slider_urls.join(", ")
		//console.log(urls_array);
		document.getElementById('section0_image').value = updated_url;
		document.getElementById('section0_image_tag'+img_key).remove();
	}
</script>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">Manage content for Testimonials</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal" enctype="multipart/form-data" >
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="testimonials" class="btn btn-default">Save</button>
					&nbsp;&nbsp;&nbsp;
					<a href="{{ URL::to('/landing-home#testimonials') }}" target="_blank" class="btn btn-default" title="Changes will reflect after you save them.">View Page</a>
				</div>
				<div class="clearfix"></div>

				<div id="testimonials_container">
					@if( isset( $vars["testimonials"]["testimonials"] ) && is_array( $vars["testimonials"]["testimonials"] ) && count( $vars["testimonials"]["testimonials"] ) > 0 )
						
						@foreach( $vars["testimonials"]["testimonials"] as $key=>$testimonial )

							<!-----TESTIMONIALs----->
							<div class="panel panel-default" id="testimonial_{{ $key }}">
								<div class="panel-heading">Testimonial {{ $key }}</div>
								<div class="panel-body">
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="control-label">Author Name</label>
										<div>
											<input type="text" name="testimonials[{{ $key }}][auther_name]" class="form-control" value="{{ $testimonial['auther_name'] or '' }}" >
										</div>
									</div>
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="control-label">Quotes</label>
										<div>
											<textarea name="testimonials[{{ $key }}][quotes]" style="width:100%; height:100px;" >{{ $testimonial['quotes'] or '' }}</textarea>
										</div>
									</div>
									<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="control-label">Image</label>
										<div>
											<input type="file" name="testimonial{{ $key }}_image"  >
											<input type="hidden" name="testimonials[{{ $key }}][image]" value="{{ $testimonial['image'] or '' }}" />
											
											@if( $testimonial['image'] != '' ) <br /><img src="{{ $testimonial['image'] }}" style="max-width:200px;" border="0"  /> @endif
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						@endforeach
					
					@else
					
					
						<!-----TESTIMONIAL Default----->
						<div class="panel panel-default" id="testimonial_1">
							<div class="panel-heading">Testimonial 1</div>
							<div class="panel-body">
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label class="control-label">Author Name</label>
									<div>
										<input type="text" name="testimonials[1][auther_name]" class="form-control" value="" >
									</div>
								</div>
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label class="control-label">Quotes</label>
									<div>
										<textarea name="testimonials[1][quotes]" style="width:100%; height:100px;" ></textarea>
									</div>
								</div>
								<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label class="control-label">Image</label>
									<div>
										<input type="file" name="testimonial1_image"  >
										<input type="hidden" name="testimonials[1][image]" value="" />
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						
					@endif
				
				</div>
				
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
					<button type="button" onclick="get_new_testimonial()" class="btn btn-default">Add More +</button>
				</div>
				<br />
				
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="testimonials" class="btn btn-default">Save</button>
				</div>
				
				
			</form>
		</div>
	</div>
</div>
<script>
	var test_count = {{ ( isset( $vars["testimonials"]["testimonials"] ) && is_array( $vars["testimonials"]["testimonials"] ) && count( $vars["testimonials"]["testimonials"] ) > 0 ) ? count($vars["testimonials"]["testimonials"]) : 1 }};
	function get_new_testimonial(){
		
		test_count = test_count+1;
		
		$("#testimonials_container").append('<div class="panel panel-default" id="testimonial_'+test_count+'">	\
					<div class="panel-heading">Testimonial '+test_count+'</div>	\
						<div class="panel-body">	\
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">	\
								<label class="control-label">Author Name</label>	\
								<div>	\
									<input type="text" name="testimonials['+test_count+'][auther_name]" class="form-control" value="" >	\
								</div>	\
							</div>	\
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">	\
								<label class="control-label">Quotes</label>	\
								<div>	\
									<textarea name="testimonials['+test_count+'][quotes]" style="width:100%; height:100px;" ></textarea>	\
								</div>	\
							</div>	\
							<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">		\
								<label class="control-label">Image</label>	\
								<div>	\
									<input type="file" name="testimonial'+test_count+'_image"  >	\
									<input type="hidden" name="testimonials['+test_count+'][image]" value="" />		\
								</div>	\
							</div>		\
						</div>	\
					</div>');
	}
</script>
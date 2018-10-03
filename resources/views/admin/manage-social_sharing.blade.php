<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">Manage content for Social Sharing</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="social_sharing" class="btn btn-default">Save</button>
					<a href="https://developers.facebook.com/tools/debug/sharing/?q={{ URL::to('/') }}" target="_blank" class="btn btn-default" >Scrape Facebook Data</a>
					<a href="https://cards-dev.twitter.com/validator" target="_blank" class="btn btn-default" >Twitter Validator</a>
				</div>
				<div class="clearfix">
					<strong>Note:</strong>After updating content,
					<br />
					1) Facebook: Use "Scrape Facebook Data" and then click on "Scrape Again" to update content on facebook. 
					<br />
					2) Twitter: Click on button "Twitter Validator" and enter "{{ URL::to('/') }}" to check if data is valid.
					
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Title</label>
					<div>
						<input name="title" class="form-control" value="{{ $vars['social_sharing']['title'] or '' }}"  />
					</div>
				</div>
				
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Description</label>
					<div>
						<textarea name="description" class="form-control" style="height:100px;" maxlength="500" >{{ $vars['social_sharing']['description'] or "" }}</textarea>
					</div>
				</div>
				
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label"><b>Image ( Minimum Dimentions: 200x200 )</b></label>
					<div>
						<input type="file" name="social_img"  >
						<input type="hidden" name="image" value="{{ $vars['social_sharing']['image'] or "" }}" />
						@if( isset($vars['social_sharing']['image']) and $vars['social_sharing']['image'] != '' )<br /><img src="{{ $vars['social_sharing']['image'] }}" style="max-width:200px;" border="0"  />@endif
					</div>
				</div>


				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label"><b>LinedIn Image ( Dimensions: 180x150 )</b></label>
					<div>
						<input type="file" name="social_img_linkedIn"  >
						<input type="hidden" name="image_linkedIn" value="{{ $vars['social_sharing']['image_linkedIn'] or "" }}" />
						@if( isset($vars['social_sharing']['image_linkedIn']) and $vars['social_sharing']['image_linkedIn'] != '' )<br /><img src="{{ $vars['social_sharing']['image_linkedIn'] }}" style="max-width:200px;" border="0"  />@endif
					</div>
				</div>
				
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="social_sharing" class="btn btn-default">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
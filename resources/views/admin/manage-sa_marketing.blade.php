<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">Manage content for Associate Marketing Page</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="sa_marketing" class="btn btn-default">Save</button>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Text</label>
					<div>
						<textarea name="marketing_text" class="text-editor" style="height:200px;" >{{ $vars['sa_marketing']['marketing_text'] or "" }}</textarea>
					</div>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Brochure Image</label>
					<div>
						<input type="file" name="brochure_img"  >
						<input type="hidden" name="brochure_image" value="{{ $vars['sa_marketing']['brochure_image'] or "" }}" />
						@if( isset($vars['sa_marketing']['brochure_image']) and $vars['sa_marketing']['brochure_image'] != '' )<br /><img src="{{ $vars['sa_marketing']['brochure_image'] }}" style="max-width:200px;" border="0"  />@endif
					</div>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Business Card Image</label>
					<div>
						<input type="file" name="business_card_img"  >
						<input type="hidden" name="business_card_image" value="{{ $vars['sa_marketing']['business_card_image'] or "" }}" />
						@if( isset($vars['sa_marketing']['business_card_image']) and $vars['sa_marketing']['business_card_image'] != '' )<br /><img src="{{ $vars['sa_marketing']['business_card_image'] }}" style="max-width:200px;" border="0"  />@endif
					</div>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="sa_marketing" class="btn btn-default">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
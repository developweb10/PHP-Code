<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">Manage content for Landowner Area</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="owner_content" class="btn btn-default">Save</button>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Signage Text</label>
					<div>
						<textarea name="signage_content" class="text-editor" style="height:200px;" >{{ $vars['owner_content']['signage_content'] or "" }}</textarea>
					</div>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Image</label>
					<div>
						<input type="file" name="image"  >
						<input type="hidden" name="signage_image" value="{{ $vars['owner_content']['signage_image'] or "" }}" />
						@if( isset($vars['owner_content']['signage_image']) and $vars['owner_content']['signage_image'] != '' )<br /><img src="{{ $vars['owner_content']['signage_image'] }}" style="max-width:200px;" border="0"  />@endif
					</div>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="owner_content" class="btn btn-default">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
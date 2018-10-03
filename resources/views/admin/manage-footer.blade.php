<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">Manage content for Footer</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="footer" class="btn btn-default">Save</button>
					&nbsp;&nbsp;&nbsp;
					<a href="{{ URL::to('/landing-home#footer') }}" target="_blank" class="btn btn-default" title="Changes will reflect after you save them.">View Page</a>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Footer Text</label>
					<div>
						<input type="text" name="footer_text" class="form-control" value="{{ $vars['footer']['footer_text'] or "" }}" >
					</div>
				</div>

				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Author</label>
					<div>
						<input type="text" name="meta_author" class="form-control" value="{{ $vars['footer']['meta_author'] or "" }}" >
					</div>
				</div>

				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Meta Keywords</label>
					<div>
						<input type="text" name="meta_keywords" class="form-control" value="{{ $vars['footer']['meta_keywords'] or "" }}" >
					</div>
				</div>

				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Meta Description ( max-lenght: 160  )</label>
					<div>
						<input type="text" name="meta_description" class="form-control" maxlength="160" value="{{ $vars['footer']['meta_description'] or "" }}" >
					</div>
				</div>

			</form>
		</div>
	</div>
</div>
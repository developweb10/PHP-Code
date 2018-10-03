<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">Manage content for Owner Agreement Page</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="sa_agreement" class="btn btn-default">Save</button>
					&nbsp;&nbsp;&nbsp;
					<a href="{{ URL::to('/sa-agreement') }}" target="_blank" class="btn btn-default" title="Changes will reflect after you save them.">View Page</a>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Page Title</label>
					<div>
						<input type="text" name="page_title" class="form-control" value="{{ $vars['sa_agreement']['page_title'] }}" >
					</div>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Page Title</label>
					<div>
						<textarea name="page_content" class="text-editor" style="height:600px;" >{{ $vars['sa_agreement']['page_content'] }}</textarea>
					</div>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="sa_agreement" class="btn btn-default">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
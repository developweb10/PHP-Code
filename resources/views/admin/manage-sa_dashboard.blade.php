<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">Manage content for Associate Dashboard Page</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="sa_dashboard" class="btn btn-default">Save</button>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Text</label>
					<div>
						<textarea name="dashboard_text" class="text-editor" style="height:200px;" >{{ $vars['sa_dashboard']['dashboard_text'] or "" }}</textarea>
					</div>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Image</label>
					<div>
						<input type="file" name="image"  >
						<input type="hidden" name="dashboard_image" value="{{ $vars['sa_dashboard']['dashboard_image'] or "" }}" />
						@if( isset($vars['sa_dashboard']['dashboard_image']) and $vars['sa_dashboard']['dashboard_image'] != '' )<br /><img src="{{ $vars['sa_dashboard']['dashboard_image'] }}" style="max-width:200px;" border="0"  />@endif
					</div>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="sa_dashboard" class="btn btn-default">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">Manage content for Landowner FAQ page</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="faq_landowner" class="btn btn-default">Save</button>
					&nbsp;&nbsp;&nbsp;
					<a href="{{ URL::to('/faq_landowner') }}" target="_blank" class="btn btn-default" title="Changes will reflect after you save them.">View Page</a>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Page Title</label>
					<div>
						<input type="text" name="page_title" class="form-control" value="{{ $vars['faq_landowner']['page_title'] }}" >
					</div>
				</div>
				<?php /*?><div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Page Title</label>
					<div>
						<textarea name="page_content" class="text-editor" style="height:200px;" >{{ $vars['faq_landowner']['page_content'] }}</textarea>
					</div>
				</div><?php */?>
				<br />

				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="question-answers_faq_landowner">
					@if( is_array($vars['faq_landowner']['questions']) AND count( $vars['faq_landowner']['questions'] ) )

						@foreach( $vars['faq_landowner']['questions'] as $key=>$ques )
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div>
									<label class="control-label">Question {{ $key+1 }}</label>
									<input name="questions[]" class="form-control" value="{{ $ques }}"  placeholder="Question {{ $key+1 }}" />
								</div>
								<div>
									<label class="control-label">Answer {{ $key+1 }}</label>
									<textarea name="answers[]" class="text-editor" style="height:100px;" placeholder="Answer {{ $key+1 }}" >{{ $vars['faq_landowner']['answers'][$key] }}</textarea>
								</div>
							</div>
						@endforeach
						
					@else
						
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<div>
								<label class="control-label">Question 1</label>
								<input name="questions[]" class="form-control" value=""  placeholder="Question 1" />
							</div>
							<div>
								<label class="control-label">Answer 1</label>
								<textarea name="answers[]" class="text-editor" style="height:100px;" placeholder="Answer 1" ></textarea>
							</div>
						</div>
						
					@endif
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
					<button type="button" onclick="get_faq_landowner_ques_ans()" class="btn btn-default">Add More +</button>
				</div>
				<br />
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="faq_landowner" class="btn btn-default">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	var ques_count_faq_landowner = {{ is_array($vars['faq_landowner']['questions']) ? count($vars['faq_landowner']['questions']) : 1 }};
	function get_faq_landowner_ques_ans(){
		ques_count_faq_landowner = ques_count_faq_landowner+1;
		$("#question-answers_faq_landowner").append(' \
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">	\
				<div>	\
					<label class="control-label">Question '+ques_count_faq_landowner+'</label>	\
					<input name="questions[]" class="form-control" value=""  placeholder="Question '+ques_count_faq_landowner+'" />	\
				</div>	\
				<div>	\
					<label class="control-label">Answer '+ques_count_faq_landowner+'</label>	\
					<textarea name="answers[]" class="text-editor" style="height:100px;" placeholder="Answer '+ques_count_faq_landowner+'" ></textarea>	\
				</div>	\
			</div>'
		);
		tinymce.init({ selector: ".text-editor" });
	}
</script>
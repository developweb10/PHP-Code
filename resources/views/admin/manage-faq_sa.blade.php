<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
		<div class="panel-heading">Manage content for Sales Associate FAQ page</div>
		<div class="panel-body">
			<form action="" method="post" class="form-horizontal">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="faq_sa" class="btn btn-default">Save</button>
					&nbsp;&nbsp;&nbsp;
					<a href="{{ URL::to('/faq_sa') }}" target="_blank" class="btn btn-default" title="Changes will reflect after you save them.">View Page</a>
				</div>
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Page Title</label>
					<div>
						<input type="text" name="page_title" class="form-control" value="{{ $vars['faq_sa']['page_title'] }}" >
					</div>
				</div>
				<?php /*?><div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<label class="control-label">Page Title</label>
					<div>
						<textarea name="page_content" class="text-editor" style="height:200px;" >{{ $vars['faq_sa']['page_content'] }}</textarea>
					</div>
				</div><?php */?>
				<br />

				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12" id="question-answers_faq_sa">
					@if( is_array($vars['faq_sa']['questions']) AND count( $vars['faq_sa']['questions'] ) )

						@foreach( $vars['faq_sa']['questions'] as $key=>$ques )
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div>
									<label class="control-label">Question {{ $key+1 }}</label>
									<input name="questions[]" class="form-control" value="{{ $ques }}"  placeholder="Question {{ $key+1 }}" />
								</div>
								<div>
									<label class="control-label">Answer {{ $key+1 }}</label>
									<textarea name="answers[]" class="text-editor" style="height:100px;" placeholder="Answer {{ $key+1 }}" >{{ $vars['faq_sa']['answers'][$key] or "" }}</textarea>
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
					<button type="button" onclick="get_faq_sa_ques_ans()" class="btn btn-default">Add More +</button>
				</div>
				<br />
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
					<button type="submit" name="form_type" value="faq_sa" class="btn btn-default">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	var ques_count_faq_sa = {{ is_array($vars['faq_sa']['questions']) ? count($vars['faq_sa']['questions']) : 1 }};
	function get_faq_sa_ques_ans(){
		ques_count_faq_sa = ques_count_faq_sa+1;
		$("#question-answers_faq_sa").append(' \
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">	\
				<div>	\
					<label class="control-label">Question '+ques_count_faq_sa+'</label>	\
					<input name="questions[]" class="form-control" value=""  placeholder="Question '+ques_count_faq_sa+'" />	\
				</div>	\
				<div>	\
					<label class="control-label">Answer '+ques_count_faq_sa+'</label>	\
					<textarea name="answers[]" class="text-editor" style="height:100px;" placeholder="Answer '+ques_count_faq_sa+'" ></textarea>	\
				</div>	\
			</div>'
		);
		tinymce.init({ selector: ".text-editor" });
	}
</script>
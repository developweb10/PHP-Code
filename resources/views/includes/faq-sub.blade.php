@if( !isset($faq_page) )
<div class="panel panel-default">
	<div class="panel-heading"><i class="fa fa-file"></i> {{ $data->page_title }}</div>
	<div class="panel-body">
		
		<?php /*?><div id="body_container">
			{{ app('App\Http\Controllers\UtilsController')->html_decode($data->page_content) }}
		</div><?php */?>
		<br />
@endif       
		<div class='searchquestions'>
			@if( is_array($data->questions) )
				@foreach( $data->questions as $key=>$ques )
					<div class='mainquestion'>
						<div class='firstquestion' data-toggle="collapse" data-target="#demo{{ isset($count)?$count+$key:$key }}">{{ $ques }}
						<i class="fa fa-angle-down icon" style="font-size:24px; float:right;"></i></div>
						<div id="demo{{ isset($count)?$count+$key:$key }}" class="collapse questionsanswers">
							{{ app('App\Http\Controllers\UtilsController')->html_decode($data->answers[$key]) }}
						</div>
					</div>
				@endforeach
			@endif
		
		</div>
@if( !isset($faq_page) )

		
	</div>
</div>
@endif
<?php /*?><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"><?php */?>
<style>
	.firstquestion {
		cursor: pointer;
		font-size: 18px;
		color: #484D56;
		padding-bottom: 10px;
		font-weight:bold;
	}
	.mainquestion {
		border-bottom: 2px solid #484D56;
		padding: 25px 0 25px 10px;
	}
	.transform-class {
		display: inline-block;
		transform: rotateX(180deg) !important;
	}
</style>

<script>
	document.addEventListener('DOMContentLoaded' ,function(){
		$('.firstquestion').click(function(){
			$(this).find('.icon').toggleClass('transform-class');
		});
	
	}, false );
</script>

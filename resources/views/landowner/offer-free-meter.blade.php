@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		
		@include("landowner.new-lot-html")
		
		<div class="clearfix"></div>
	</div>
</div>

<script>
	document.addEventListener('DOMContentLoaded', function(){
		
		$("#newLotModal").modal({backdrop: 'static', keyboard: false});
		
	}, false);
</script>

@endsection  

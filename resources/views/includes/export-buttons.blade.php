<?php
	$mobile = config('is_mobile');
	$Ipad = config('is_Ipad');
?>
<div class="text-right export-buttons">
	<br clear="all">
	@if ((!$Ipad) && $mobile)
		<br />
	@endif
    <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2 @if ((!$Ipad) && $mobile) @else pull-right @endif col-lg-offset-0 col-sm-offset-0 col-xs-offset-3"> 
    	<input type="image" class="" src="{{asset('images/download_blue.png')}}"  value="PDF" onClick="document.getElementById('export').value='PDF';document.getElementById('filter-form').submit();document.getElementById('export').value='';" style="width:100%;" />
    </div>
	<!--<input type="button" class="btn btn-danger"  value="PDF" onClick="document.getElementById('export').value='PDF';document.getElementById('filter-form').submit();document.getElementById('export').value='';" />
	<input type="button" class="btn btn-success" value="Excel" onClick="document.getElementById('export').value='Excel';document.getElementById('filter-form').submit();document.getElementById('export').value='';" />-->
    
</div>

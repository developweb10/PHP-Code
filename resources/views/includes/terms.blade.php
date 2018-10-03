@extends('welcome-app')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<?php /*?><div class="panel-heading"><i class="fa fa-book"></i> {{ $data->page_title }}</div><?php */?>
					<div class="panel-body" id="body_container">
                    	<ul class="nav nav-tabs" role="tablist">
            				<li class="active"><a href="#drivers"  aria-controls="drivers" role="tab" data-toggle="tab">Drivers</a></li>
                           <!-- <li><a href="#landowners" aria-controls="landowners" role="tab" data-toggle="tab">Landowners</a></li>-->
            			</ul>
                        
                        <div class="tab-content">
          					  <div role="tabpanel" class="tab-pane active" id="drivers">
                              		<br />
									{{ app('App\Http\Controllers\UtilsController')->html_decode($data->page_content) }}
                              </div>
                             <?php /*?> <div role="tabpanel" class="tab-pane" id="landowners">
                              	<br />
                              	@if( isset($vars['lo_data']['page_content']) )
                                    {{ app('App\Http\Controllers\UtilsController')->html_decode($vars['lo_data']['page_content']) }}
                                 @endif
                              </div><?php */?>
                        </div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
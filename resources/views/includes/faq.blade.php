@extends('welcome-app')

@section('content')
	<?php $faq_page = true; ?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
                    <div class="panel-body" id="body_container">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#drivers"  aria-controls="drivers" role="tab" data-toggle="tab">Drivers</a></li>
                            <li><a href="#landowners" aria-controls="landowners" role="tab" data-toggle="tab">Landowners</a></li>
                        </ul>
                        
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="drivers">
                                <br />
                                @include('includes.faq-sub')
                            </div>
                            <div role="tabpanel" class="tab-pane" id="landowners">
                                <br />
                                <?php $data = $vars; $count = count($data->questions); ?>
                                @include('includes.faq-sub')
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
@endsection
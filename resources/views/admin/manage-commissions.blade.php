@extends('app')

@section('content')
<?php
if($vars['id'] != 0){
	 //echo "<pre>"; print_r($vars['users']["commission"]); exit(); 
}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Manage Commissions</div>
				<div class="panel-body">
                	<div class="row">
                        <div class="col-md-5">
                            @if ( count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            @if( Session::has('success'))
                                <div class="alert alert-success text-left">
                                    <strong>Success!</strong> {{ Session::get('success') }}
                                </div>
                            @endif
                        </div>
                    </div>
					
                    <form role="form" class="form-horizontal" action="{{ URL::to('/admin/commissions') }}" method="post">
                    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="form_action" value="{{ $vars['form_action'] }}">
                        <hr />
                        <div class="row">
                            <div class="col-md-5">
                                Select {{ $vars['user_type'] }} : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <select name="id" class="form-control " onchange="window.location='{{ $vars['form_action'] }}?id='+this.value" >
                                    <option value="">Select {{ $vars['user_type'] }}</option>
                                    @foreach( $vars['users'] as $u )
                                    	@if( $u->id === $vars["id"] ) <?php  $assoc_comm = $u->commission; ?> @endif
                                        <option value="{{ $u->id }}" @if( $u->id === $vars["id"] ) selected="selected"  @endif >{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr />
                         @if( $vars["id"] > 0 && $assoc_comm == '0.00' )
                            @if( count($vars["settings"]) )
                           		<?php $assoc_comm = $vars["settings"]->sa_commission; ?>
                            @endif
                        @endif
                        <div class="row">
                            <div class="col-md-5">
                                Commission %
                                <input name="{{ $vars['id'] }}" value="@if(isset($assoc_comm) && $assoc_comm != '0.00') {{ $assoc_comm }} @endif" class="form-control" />
                            </div>
                         </div>
                         <hr />
                        @if( $vars["id"] > 0 )
                            <table id="commissons_table" cellspacing="0" cellpadding="20" width="515" border="1" >
                                <thead>
                                    <tr>
                                        <th>Referrals( {{ $vars['ref_type'] }} )</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @if( count($vars["referrals"]) )
                                        @foreach( $vars["referrals"] as $ref )
                                            <tr>
                                                <td>{{ $ref->name }}</td>
                                               
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2">No referrals by this associate.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>      
                            <br />
                            <button type="submit" class="btn btn-default btn-lg" name="update_commissions" >Update</form>
                        @endif
                    
                    </form>
					
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<style type="text/css">
	#commissons_table{ border-color: rgba(169, 169, 169, 0.14); }
	#commissons_table th, #commissons_table td{  
		padding:10px;
	}
</style>
@endsection
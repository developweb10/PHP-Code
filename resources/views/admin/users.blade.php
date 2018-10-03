@extends('app')

@section('content')

<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Users
					<div class="pull-right">
						<select name="role" onchange="window.location='{{ URL::to('/admin/users') }}?role='+this.value+'&status={{ $vars['status'] }}'" >
							<option value="">Select Role</option>
							@foreach( $roles as $role )
								<option value="{{ $role->id }}" @if( $vars['role_id'] == $role->id ) selected="selected" @endif >{{ $role->name }}</option>
							@endforeach
						</select>
						<select name="status" onchange="window.location='{{ URL::to('/admin/users') }}?status='+this.value+'&role={{ $vars['role_id'] }}'" >
							<option value="">Status ( Default:All )</option>
							<option value="0" @if( $vars['status'] == "0" ) selected="selected" @endif >Active</option>
							<option value="1" @if( $vars['status'] == "1" ) selected="selected" @endif >Inactive</option>
						</select>
						
						<a href="{{ URL::to('/admin/createuser') }}?show_layout=false" class="btn btn-default btn-sm" data-toggle="modal" data-target="#createNewUserModal" data-static="false" data-keyboard="false" onclick="openModalLoader()"  >Create New User</a>
                       <!-- <a href="{{ URL::to('/admin/commissions') }}" class="btn btn-default btn-sm">Manage SA Commissions</a>
                        <a href="{{ URL::to('/admin/sm-commissions') }}" class="btn btn-default btn-sm">Manage SM Commissions</a> -->
					</div>
				</div>
				<div class="panel-body">
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
						<div class="alert alert-success">
							<strong>Success!</strong> {{ Session::get('success') }}
						</div>
					@endif
					
					<table id="datatable" width="100%" class="display compact" cellspacing="0" >
						<thead>
							<tr>
								<th class="col-md-2 text-center">First Name</th>
                                <th class="col-md-2 text-center">Last Name</th>
								<th class="col-md-2 text-center">Email</th>
								<th class="col-md-2 text-center">Role</th>
								<th class="col-md-1 text-center">Reg. Date</th>
								<th class="col-md-1 text-center">$ Balance</th>
                                <th class="col-md-1 text-center">Status</th>
								<th class="col-md-2 text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach( $users as $user )
								<tr>
									<td class="col-md-2 text-center">{{ $user->name }}</td>
                                    <td class="col-md-2 text-center">{{ $user->last_name }}</td>
									<td class="col-md-2 text-center">{{ $user->email }}</td>
									<td class="col-md-2 text-center">{{ $user->role_name }}</td>
									<td class="col-md-1 text-center">{{ (new DateTime($user->created_at))->format("Y-m-d") }}</td>
									<td class="col-md-1 text-center">{{ $user->balance }}</td>
                                    <td class="col-md-1 text-center">{{ $user->deleted == 1 ? "Inactive" : "Active"  }}</td>
									<td class="col-md-2 text-center">
                                    	<div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Options
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ URL::to('/admin/edituser') }}?id={{ $user->id }}">Edit</a></li>
                                                <li>
                                                	@if( $user->deleted == 1 )
                                                        <a href="{{ URL::to('/admin/userstatus') }}?id={{ $user->id }}&status=0" >Activate</a>
                                                    @else
                                                        <a href="javascript:if( confirm('Are you sure you want to deactivate this user?') ){ window.location='{{ URL::to('/admin/userstatus') }}?id={{ $user->id }}&status=1'};void(0);" >Deactivate</a>
                                                    @endif
                                                </li>
                                                <li><a href="javascript:if( confirm('Are you sure you want to delete this user permanentaly from system?') ){ window.location='{{ URL::to('/admin/userdelete') }}?id={{ $user->id }}'};void(0);" >Delete</a></li>
                                            </ul>
                                        </div>
										 
										
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>


<div id="createNewUserModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content"></div>
	</div>
</div>


<style>.panel-heading{ height: 51px; }</style>
@endsection
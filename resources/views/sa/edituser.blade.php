@if( !isset($vars['show_layout']) or ( isset($vars['show_layout']) and $vars['show_layout'] === true ) )

	

	@extends('app')



	@section('content')



        <div class="container-fluid">

            <div class="row">

                <div class="col-md-8 col-md-offset-2">

                    <div class="panel panel-default">

                        <div class="panel-heading">@if( $vars['user_id'] )  Edit Landowner: {{ $user['name'] }} @else Create Landowner @endif </div>

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

        

                            <form class="form-horizontal" role="form" method="POST" action="">

                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                

                                                            

                                

                                <div class="form-group">

                                    <label class="col-md-4 control-label">Name</label>

                                    <div class="col-md-6">

                                        <input type="text" class="form-control" name="name" value="{{ $user['name'] }}" placeholder="Name" >

                                    </div>

                                </div>

        

                                <div class="form-group">

                                    <label class="col-md-4 control-label">E-Mail Address</label>

                                    <div class="col-md-6 to-text">

                                        @if( $vars['user_id'] )

                                        {{ $user['email'] }}

                                            <input type="hidden" name="email" value="{{ $user['email'] }}" >

                                        @else

                                            <input type="email" class="form-control" name="email" value="{{ $user['email'] }}" placeholder="Email" >

                                        @endif

                                    </div>

                                </div>

        

                               <?php /*?> <div class="form-group">

                                    <label class="col-md-4 control-label">Address</label>

                                    <div class="col-md-6">

                                        <input type="text" class="form-control" name="street" placeholder="Street" value="{{ $user['street'] }}" >

                                    </div>

                                </div>

        

                                <div class="form-group">

                                    <label class="col-md-4 control-label">Country</label>

                                    <div class="col-md-6">

                                        <select name="country" id="country_list_sm" class="form-control">

                                            @foreach( $countries as $country )

                                                <option value="{{ $country->id }}" @if( $user['country'] == $country->id ) selected="selected" @endif >{{ $country->nicename }}</option>

                                            @endforeach

                                        </select>

                                    </div>

                                </div>



                                <div class="form-group">

                                    <label class="col-md-4 control-label">State/Province</label>

                                    <div class="col-md-6">

                                        <select name="state" id="state_list_sm" class="form-control">

                                            <option value="">Select State/Province</option>

                                            @if( count($states) )

                                                @foreach( $states as $state )

                                                    <option value="{{ $state->state_code }}" @if( $user['state'] == $state->state_code ) selected="selected" @endif >{{ $state->state_code }}</option>

                                                @endforeach

                                            @endif

                                        </select>

                                    </div>

                                </div>



                                <div class="form-group">

                                    <label class="col-md-4 control-label">City</label>

                                    <div class="col-md-6">

                                        <select name="city" id="city_list_sm" class="form-control">

                                            <option value="">Select City</option>
                                         

                                            @if( count($cities) )

                                                @foreach( $cities as $city )

                                                    <option value="{{ $city->city_name }}" @if( $user['city'] == $city->city_name ) selected="selected" @endif >{{ $city->city_name }}</option>

                                                @endforeach

                                            @endif

                                        </select>

                                    </div>

                                </div><?php */?>

                                

                                <?php /*?><div class="form-group">

                                    <label class="col-md-4 control-label"></label>

                                    <div class="col-md-6">

                                        <input type="text" class="form-control" name="country" placeholder="Country" value="{{ $user['country'] }}" >

                                    </div>

                                </div><?php */?>

                                

                                <div class="form-group">

                                    <label class="col-md-4 control-label">Password</label>

                                    <div class="col-md-6">

                                        <input type="password" class="form-control" name="password" placeholder="****" >

                                    </div>

                                </div>

        

                                <div class="form-group">

                                    <label class="col-md-4 control-label">Confirm Password</label>

                                    <div class="col-md-6">

                                        <input type="password" class="form-control" name="password_confirmation" placeholder="****" >

                                    </div>

                                </div>

                                @if( $vars['user_id'] )

                                    <div class="form-group">

                                        <label class="col-md-4 control-label"></label>

                                        <div class="col-md-6">

                                            <small><strong>NOTE:</strong> Leave Passwords empty if you don't want to change.</small>

                                        </div>

                                    </div>

                                @endif

                                

                                <?php /*?><div class="form-group">

                                    <label class="col-md-4 control-label">Email to receive payments</label>

                                    <div class="col-md-6">

                                        <input type="text" class="form-control" name="email_payments" placeholder="Email to receive payments" value="{{ $user['email_payments'] }}" >

                                    </div>

                                </div><?php */?>

                                @if( $vars['inspectionsURL'] != '' )

                                <div class="form-group">

                                    <label class="col-md-4 control-label">Inspections URL</label>

                                    <div class="col-md-6">

                                        <a href="{{ $vars['inspectionsURL'] }}" target="_blank">{{ $vars['inspectionsURL'] }}</a>

                                    </div>

                                </div>

                                @endif

                                <div class="form-group">

                                    <div class="col-md-6 col-md-offset-4">

                                        <button type="submit" class="btn btn-default">

                                            Submit

                                        </button>

                                    </div>

                                </div>

                                

                                <div class="clearfix"></div>

                            </form>

                        </div>

                    </div>

                </div>

                <div class="clearfix"></div>

            </div>

        </div>



    @endsection



@else

	<div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title text-center">New Client</h4>

    </div>

    <div class="modal-body">

            

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

    	
        <?php /*?>

        <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/sa/createnewuser') }}">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            

                     

            <div class="form-group">

                <label class="col-md-4 control-label">Name</label>

                <div class="col-md-6">

                    <input type="text" class="form-control" name="name" value="{{ $user['name'] }}" placeholder="Name" >

                </div>

            </div>

    

            <div class="form-group">

                <label class="col-md-4 control-label">E-Mail Address</label>

                <div class="col-md-6 to-text">

                    <input type="email" class="form-control" name="email" value="{{ $user['email'] }}" placeholder="Email" >

                </div>

            </div>

    

            

            <div class="form-group">

                <label class="col-md-4 control-label">Country</label>

                <div class="col-md-6">

                    <select name="country" id="country_list_sm" class="form-control">

                        @foreach( $countries as $country )

                            <option value="{{ $country->id }}" @if( $user['country'] == $country->id ) selected="selected" @endif >{{ $country->nicename }}</option>

                        @endforeach

                    </select>

                </div>

            </div>



            <div class="form-group">

                <label class="col-md-4 control-label">State/Province</label>

                <div class="col-md-6">

                    <select name="state" id="state_list_sm" class="form-control">

                        <option value="">Select State/Province</option>

                        @if( count($states) )

                            @foreach( $states as $state )

                                <option value="{{ $state->state_code }}" @if( $user['state'] == $state->state_code ) selected="selected" @endif >{{ $state->state_code }}</option>

                            @endforeach

                        @endif

                    </select>

                </div>

            </div>



            <div class="form-group">

                <label class="col-md-4 control-label">City</label>

                <div class="col-md-6">

                    <select name="city" id="city_list_sm" class="form-control">

                        <option value="">Select City</option>

                        @if( count($cities) )

                            @foreach( $cities as $city )

                                <option value="{{ $city->city_name }}" @if( $user['city'] == $city->city_name ) selected="selected" @endif >{{ $city->city_name }}</option>

                            @endforeach

                        @endif

                    </select>

                </div>

            </div>

            

            <div class="form-group">

                <div class="col-md-6 col-md-offset-4">

                    <button type="submit" class="btn btn-default">

                        Submit

                    </button>

                </div>

            </div>

            

            <div class="clearfix"></div>

        </form>

		<?php */?>
      

    </div>

    

    <?php die(); ?>

@endif
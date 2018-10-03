@extends('app')

@section('content')
	
    <div class="container-fluid">

            <div class="row">

                <div class="col-md-8 col-md-offset-2">

                    <div class="panel panel-default">

                        <div class="panel-heading">Edit Company :</div>
                        
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
    
                                    <label class="col-md-4 control-label">State/Province</label>
                                
                                    <div class="col-md-6">
                                
                                        <select name="state" id="state_list" class="form-control">
                                
                                            <option value="">Select State/Province</option>
                                
                                            @if( count($states) )
                                
                                                @foreach( $states as $state )
                                
                                                    <option value="{{ $state->state_code }}" @if( $company_data["state_code"] == $state->state_code ) selected="selected" @endif  >{{ $state->state_code }}</option> 
                                
                                                @endforeach
                                
                                            @endif
                                
                                        </select>
                                
                                    </div>
                                
                                </div>
                                
                                <div class="form-group">

                                    <label class="col-md-4 control-label">City</label>

                                    <div class="col-md-6">

                                        <select name="city" id="city_list" class="form-control">

                                            <option value="">Select City</option>

                                            @if( count($cities) )

                                                @foreach( $cities as $city )

                                                    <option value="{{ $city->id }}" @if( $company_data['city_id'] == $city->id ) selected="selected" @endif >{{ $city->city_name }}</option>

                                                @endforeach

                                            @endif

                                        </select>

                                    </div>

                                </div>
                                
                                <div class="form-group">

                                    <label class="col-md-4 control-label">Towing Company</label>

                                    <div class="col-md-6">

                                        <input type="text" class="form-control" name="company" placeholder="Towing Company" value="{{ $company_data['company'] }}" >

                                    </div>

                                </div>
                                
                                <div class="form-group">

                                    <label class="col-md-4 control-label">Contact Number</label>

                                    <div class="col-md-6">
										<?php 
											if(isset($company_data['contact_no']) && !empty($company_data['contact_no'])){	
												$number = preg_replace("/[^\d]/","",$company_data['contact_no']); 
												$length = strlen($number);
												$number = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
											}else{
												$number = "";
											}
										?>
                                        <input type="text" class="form-control" name="contact_no" value="{{ $number }}" placeholder="Contact Number">

                                    </div>

                                </div>
                                
                                <div class="form-group">

                                    <div class="col-md-6 col-md-offset-4">

                                        <button type="submit" class="btn btn-default">

                                            Submit

                                        </button>

                                    </div>

                                </div>
                                
    						</form>
    
                        </div>
                    </div>
                 
                 </div>
            </div>
    </div>
    
    
@endsection

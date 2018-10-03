<div class="modal-header">

    <button type="button" class="close" data-dismiss="modal">&times;</button>

    <h4 class="modal-title text-center">Create Sales Associate</h4>

</div>

<div class="modal-body">


    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/sm/createnewuser') }}">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        

                 

        <div class="form-group">

            <label class="col-md-4 control-label">Name</label>

            <div class="col-md-6">

                <input type="text" class="form-control" name="name" value="" placeholder="Name" >

            </div>

        </div>

    

        <div class="form-group">

            <label class="col-md-4 control-label">E-Mail Address</label>

            <div class="col-md-6 to-text">

                <input type="email" class="form-control" name="email" value="" placeholder="Email" >

            </div>

        </div>

        <div class="form-group">

            <label class="col-md-4 control-label">Country</label>

            <div class="col-md-6">

                <select name="country" id="country_list_sm" class="form-control" required="required">
                    <option value="">Select Country</option>
                    @foreach( $countries as $country )

                        <option value="{{ $country->id }}">{{ $country->nicename }}</option>

                    @endforeach

                </select>

            </div>

        </div>


        <div class="form-group">

            <label class="col-md-4 control-label">State/Province</label>

            <div class="col-md-6">

                <select name="state" id="state_list_sm" class="form-control">

                    <option value="">Select State/Province</option>

                </select>

            </div>

        </div>



        <div class="form-group">

            <label class="col-md-4 control-label">City</label>

            <div class="col-md-6">

                <select name="city" id="city_list_sm" class="form-control">

                    <option value="">Select City</option>

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



</div>
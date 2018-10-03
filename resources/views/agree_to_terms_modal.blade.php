<div class="container-fluid">
    <div class="row">
        
        <!-- Agree to terms -->
        <div id="termsDialog_UI" class="modal fade" role="dialog" style="display:none; z-index:9999;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center"></h4>
                        
                    </div>
                    <div class="modal-body" style="max-height:436px; overflow-y:scroll;">
                        

                    </div>
                    <div class="modal-header text-right">
                        <form method="post" action="" class="form-inline" >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <!--<input type="button" name="accept_is_agreed" value="Accept Terms & Conditions" class="btn btn-success" /> -->
                           <!-- <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
       <div class="clearfix"></div>
    </div>
</div>